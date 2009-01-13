<?php
/** 
*
* @package install
* @version $Id: ip_cf_olympus_db_update.php,v 1.002 2009/01/10 14:30:00 3Di Exp $
* @copyright (c) 2007, 2008, 2009 -  3Di (Marco T.) 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);

$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'common.'.$phpEx);


// Session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

// here we check if the user has already modified the board, else stop.
if( !defined('CF_ISO_TABLE') )
{
	trigger_error($user->lang['TABLE_DOES_NOT_EXISTS']);
}

// Page Title
page_header($user->lang['IP_CF_DB_POP_HEADER']);

// first you need to login here, if you are a guest
if ($user->data['user_id'] == ANONYMOUS)
{
	login_box("install_ip/db_update.$phpEx", $user->lang['LOGIN_ADMIN'], $user->lang['LOGIN_ADMIN_SUCCESS']);
}

// no FOUNDER? no party! o_O
if ($user->data['user_type'] != USER_FOUNDER)
{
	$message = $user->lang['NO_FOUNDER'] . '<br /><br />' . sprintf($user->lang['CLICK_RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');
	trigger_error($message);
}

//
// Number of data files.
//
$datafiles_count = 21;

//
// This is the file that contains all IP to Country data.
// It should be located in the same directory where this script lives.
//
$datafile_mask = 'db_ip2country_part_%d.dat';

//
// INSERT Mask
$insert_mask = 'INSERT INTO '.CF_ISO_TABLE.' ( ip_from, ip_to, iso3661_1, ip_prefix ) VALUES ( %u, %u, \'%s\', "" )';

//
// Check input args
//
$confirm		= isset($_POST['confirm']) ? true : false;
$autorefresh	= isset($_POST['autorefresh']) ? true : false;
$step			= isset($_POST['step']) ? intval($_POST['step']) : 0;

if( $step < 0 )
{
	$step = 0;
}

//
// If it is the last step, just say bye
//
if( $confirm && $step > $datafiles_count )
{
	$message = $user->lang['DELETED_THIS_FILE'] . ' ' . $user->lang['DELETE_THIS_FILE'] . '<br /><br />' . sprintf($user->lang['CLICK_RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');
	trigger_error($message);
}

//
// Build the status report
//
$status_report = array();
for( $i = 0; $i < $datafiles_count; $i++ )
{
	$x = $i + 1;
	$datafile = sprintf($datafile_mask, $x);
	if( $step >= $x )
	{
		$status = '<strong style="color:blue;">' . $user->lang['PROCESSED'] . '</strong>';
	}
	else
	{
		$status = '<strong style="color:red;">' . $user->lang['PENDING'] . '</strong>';
	}
	$status_report[] = sprintf($user->lang['DATAFILE_STATUS'] . ' ' . $datafile . ' ' . $status);
}

//
// Should we update the database now?
//
if( $confirm && $step > 0 && $step <= $datafiles_count )
{

// Borrowed from Olympus CVS http://phpbb.cvs.sourceforge.net/phpbb/phpBB2/install/index.php?r1=1.58&r2=1.59
// Try to override some limits - maybe it helps some...

@set_time_limit(0);
@ini_set('memory_limit', '128M');
$mem_limit = @ini_get('memory_limit');
if (!empty($mem_limit))
	{
		$unit = strtolower(substr($mem_limit, -1, 1));
		$mem_limit = (int)$mem_limit;
		if ($unit == 'k')
		{
			$mem_limit = floor($mem_limit/1024);
		}
		elseif ($unit == 'g')
		{
			$mem_limit *= 1024;
		}
		elseif (is_numeric($unit))
		{
			$mem_limit = floor($mem_limit/1048576);
		}
			$mem_limit = max(128, $mem_limit) . 'M';
	}
	else
	{
		$mem_limit = '128M';
	}
@ini_set('memory_limit', $mem_limit );

// END: Borrowed from Olympus CVS http://phpbb.cvs.sourceforge.net/phpbb/phpBB2/install/index.php?r1=1.58&r2=1.59

	//
	// Let's read the IP to Country data
	//
	$datafile = sprintf($datafile_mask, $step);
	if( !($fp = @fopen($datafile, "rb")) )
	{
		trigger_error($user->lang['ERROR_OPEN_DATAFILE']);
	}
	$block_size = 1024 * 10;
	$data = '';
	while( !@feof($fp) )
	{
		$data .= @fread($fp, $block_size);
	}
	@fclose($fp);

	// Split data into an array of lines (dealing with LF, CRLF and CR).
	$data = preg_split("/\r?\n|\r/", $data);
	$data_lines = count($data);

	//
	// Build Array of SQL Statements.
	//
	// doubleval compatibility: PHP 3, PHP 4, PHP 5 (became an Alias of FLOATVAL since PHP 4.2.0)
	$sql = array();
	for( $i = 0; $i < $data_lines; $i++ )
	{
		list($ip_from, $ip_to, $iso3661_1) = explode(',', $data[$i]);
		$sql[] = sprintf($insert_mask, doubleval($ip_from), doubleval($ip_to), $iso3661_1);
	}
	$sql_count = count($sql);

	//
	// Execute SQL and get Results.
	//
	$sql_rows = '';
	for( $i = 0; $i < $sql_count; $i++ )
	{
		if( !$db->sql_query($sql[$i]) )
		{
			trigger_error($user->lang['FAILED_TO_UPDATE_THE_IP_TO_COUNTRY_TABLE']);
		}
	}
}

//
// Finally, build the installation panel...
//
$message = $user->lang['UPDATE_CONFIRM'];

if( $step == 0 )
{
	$message .= '<br /><br /<strong style="color:blue;">' . $user->lang['BACKUP_WARNING'] . '</strong>' . "\n";
}

$message .= '<br /><hr />' . "\n"
	. '<table><tr><td><pre>' . implode("\n", $status_report) . '</pre></td></tr></table>' . "\n"
	. '<hr /><br />' . "\n"
	. '<form name="dbupdate" method="post" action="' . append_sid(basename(__FILE__)) . '">' . "\n"
	. '<input class="mainoption" type="submit" name="confirm" value="' . $user->lang['PROCEED'] . '" />' . "\n"
	. '<br /><br />' . "\n"
	. '<label for="autorefresh">' . $user->lang['AUTO_REFRESH'] . '</label>' . "\n"
	. '<input type="checkbox" name="autorefresh" id="autorefresh" value="1"' . ( $autorefresh ? ' checked="checked"' : '' ) . ' />' . "\n"
	. '<input type="hidden" name="step" value="' . ($step+1) . '" />' . "\n"
	. '</form>' . "\n";

if( $autorefresh )
{
	$message .= '<script type="text/javascript"><!--' . "\n"
		. 'window.onload = function() {' . "\n"
		. 'setTimeout(\'if(document.forms.dbupdate.autorefresh.checked){document.forms.dbupdate.confirm.click();}\', 1500);' . "\n"
		. '}' . "\n"
		. '//--></script>' . "\n";
}

trigger_error($message);

?>