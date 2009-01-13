<?php
/*-----------------------------------------------------------------------------
    Modification Installer for phpBB 3
  ----------------------------------------------------------------------------
    install.php
       SQL Installer Script
    Generation Date: January 01, 2009  
  ----------------------------------------------------------------------------
	This file is released under the GNU General Public License Version 2.
-----------------------------------------------------------------------------*/

define('IN_PHPBB', true);
//$phpbb_root_path = './';
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'common.'.$phpEx);

// Session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('acp/common');

if ($user->data['user_id'] == ANONYMOUS)
{
	login_box("install.$phpEx", $user->lang['LOGIN_ADMIN'], $user->lang['LOGIN_ADMIN_SUCCESS']);
}

// no FOUNDER? no party! o_O
if ($user->data['user_type'] != USER_FOUNDER)
{
	$message = $user->lang['NO_FOUNDER'] . '<br /><br />' . sprintf($user->lang['CLICK_RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');
	trigger_error($message);
}

$mod = array(
	'name'			=>	'IP Country Flag Olympus',
	'version'		=>	'BETA 0.5.0',
	'copy_year'		=>	'2009',
	'author'		=>	'3Di',
	'website'		=>	'http://gold.io3di.com/',
	'sitename'		=>	'IP Country Flag Olympus WHQs',
	'prev_version'	=>	'BETA 0.1.8a',
);

$install = $uninstall = $upgrade = FALSE;
$sql = $module_data = array();
switch( $dbms )
{
	case 'postgres':
		$sql["install"] = array(
	"CREATE TABLE " . $table_prefix . "cf_ip_to_iso3661_1 (
    ip_id SERIAL,
    ip_from INTEGER NOT NULL DEFAULT 0,
    ip_to INTEGER NOT NULL DEFAULT 0,
    iso3661_1 CHAR(2) NOT NULL DEFAULT '',
    ip_prefix CHAR(3) NOT NULL DEFAULT '',
    CONSTRAINT phpbb_cf_ip_to_iso3661_1_pk PRIMARY KEY (ip_id),
    CHECK (ip_from>=0),
    CHECK (ip_to>=0)
)",
	"CREATE INDEX " . $table_prefix . "cf_ip_to_iso3661_1ipfrom ON phpbb_cf_ip_to_iso3661_1 (ip_from, ip_to)",
	"INSERT INTO " . $table_prefix . "cf_ip_to_iso3661_1(ip_from,ip_to,iso3661_1,ip_prefix) VALUES('2130706433','2130706433','LH','LOC')",
	"INSERT INTO " . $table_prefix . "config(config_name,config_value,is_dynamic) VALUES('newest_user_reg_ip','127.0.0.1','1')");
		$sql["uninstall"] = array(
	"DROP TABLE " . $table_prefix . "cf_ip_to_iso3661_1",
	"DELETE FROM " . $table_prefix . "config WHERE config_name = 'newest_user_reg_ip'");
		$sql["upgrade"] = array(
	"ALTER TABLE " . $table_prefix . "posts DROP COLUMN post_edit_ip CASCADE",
	"ALTER TABLE " . $table_prefix . "topics DROP COLUMN topic_first_poster_ip CASCADE",
	"ALTER TABLE " . $table_prefix . "topics DROP COLUMN topic_last_poster_ip CASCADE",
	"ALTER TABLE " . $table_prefix . "forums DROP COLUMN forum_last_poster_ip CASCADE");
	break;

	case 'mssql':
		$sql["install"] = array(
	"CREATE TABLE " . $table_prefix . "cf_ip_to_iso3661_1 (
    ip_id INTEGER NOT NULL IDENTITY(1, 1),
    ip_from INTEGER NOT NULL DEFAULT (0),
    ip_to INTEGER NOT NULL DEFAULT (0),
    iso3661_1 CHAR(2) NOT NULL DEFAULT (''),
    ip_prefix CHAR(3) NOT NULL DEFAULT (''),
    CONSTRAINT phpbb_cf_ip_to_iso3661_1_pk PRIMARY KEY (ip_id) ON [PRIMARY],
    CHECK (ip_from>=0),
    CHECK (ip_to>=0)
)  ON [PRIMARY]",
	"CREATE INDEX " . $table_prefix . "cf_ip_to_iso3661_1ipfrom ON phpbb_cf_ip_to_iso3661_1 (ip_from, ip_to) ON [PRIMARY]",
	"INSERT INTO " . $table_prefix . "cf_ip_to_iso3661_1(ip_from,ip_to,iso3661_1,ip_prefix) VALUES('2130706433','2130706433','LH','LOC')",
	"INSERT INTO " . $table_prefix . "config(config_name,config_value,is_dynamic) VALUES('newest_user_reg_ip','127.0.0.1','1')");
		$sql["uninstall"] = array(
	"DROP TABLE " . $table_prefix . "cf_ip_to_iso3661_1",
	"DELETE FROM " . $table_prefix . "config WHERE config_name = 'newest_user_reg_ip'");
		
	break;

	case 'mysql':
	case 'mysql4':
	default:
		$sql["install"] = array(
	"CREATE TABLE " . $table_prefix . "cf_ip_to_iso3661_1 (
	ip_id int(6) NOT NULL auto_increment,
	ip_from int(11) unsigned NOT NULL default '0',
	ip_to int(11) unsigned NOT NULL default '0',
	iso3661_1 char(2) NOT NULL default '',
	ip_prefix char(3) NOT NULL default '',
	PRIMARY KEY  (ip_id),
	KEY ip_from (ip_from, ip_to)
)",
	"INSERT INTO " . $table_prefix . "cf_ip_to_iso3661_1 (ip_from, ip_to, iso3661_1, ip_prefix) VALUES ('2130706433', '2130706433', 'LH', 'LOC')",
	"INSERT INTO " . $table_prefix . "config (config_name, config_value, is_dynamic) VALUES ('newest_user_reg_ip', '127.0.0.1', '1')");
		$sql["uninstall"] = array(
	"DROP TABLE " . $table_prefix . "cf_ip_to_iso3661_1",
	"DELETE FROM " . $table_prefix . "config WHERE config_name = 'newest_user_reg_ip'");
		$sql["upgrade"] = array(
	"ALTER TABLE " . $table_prefix . "posts DROP post_edit_ip",
	"ALTER TABLE " . $table_prefix . "topics DROP topic_first_poster_ip",
	"ALTER TABLE " . $table_prefix . "topics DROP topic_last_poster_ip",
	"ALTER TABLE " . $table_prefix . "forums DROP forum_last_poster_ip");
	break;
}






if( !empty($sql['install']) || !empty($module_data['install']) )
{
	$install = TRUE;
}

if( !empty($sql['uninstall']) || !empty($module_data['uninstall']) )
{
	$uninstall = TRUE;
}

if( !empty($sql['upgrade']) || !empty($module_data['upgrade']) )
{
	$upgrade = TRUE;
}

require_once($phpbb_root_path . 'includes/acp/acp_modules.' . $phpEx);
$acp_modules	= new acp_modules();

$page_title = $mod['name'] . ' BETA 0.5.0 Installer';
$action = request_var('action', '');

$version_string = $of_name = $for_name = '';
if( !empty($mod['name']) )
{
	$of_name  = ' of ' . '<b>' . $mod['name'] . '</b>';
	$for_name = ' for ' . '<b>' . $mod['name'] . '</b>';
}

if( !empty($mod['prev_version']) && !empty($mod['version']) )
{
	$version_string = " from <i>{$mod['name']}</i> <b>{$mod['prev_version']}</b> to <i>{$mod['name']}</i> <b>{$mod['version']}</b>";
}

$page_head = <<<EOH
<html>
<head>
<title>$page_title</title>
<style type="text/css">
<!--
body
{
	color: darkblue;
	background-color: lightblue;
	margin: 0;
	padding: 0;
	font-size: 16px;
	font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;
}

a
{
	background-color: inherit;
	text-decoration: none;
	font-size: 1.0em;
}

a:hover
{
	color: darkblue;
	background-color: inherit;
	text-decoration: underline;
}

div#header
{
	width: 100%;
	color: blue;
	background-position: right bottom;
	border: none;
	margin-top: 0.4em;
	margin-bottom: 0.5em;
}

#logo
{
	font-size: 1.8em;
	font-weight: bold;
	padding-left: 0.5em;
}

#logo a, #logo a:hover
{
	color: purple;
	font-size: 1em;
}

#footer
{
	clear: both;
	border: none;
	border-top: 1px solid black;
	margin: 0;
	padding: 0.3em;
	font-size: 0.7em;
	text-align: right;
}

#content
{
	background: yellow;
	border-top: 1px solid black;
	margin-top: 0;
	padding: 0.5em 1em 0.1em;
	text-align: justify;
}

p, ul
{
	font-size: 0.8em;
}

.error
{
	font-weight: bold;
	color: red;
}

.success
{
	font-weight: bold;
	color: darkgreen;
}
-->
</style>
</head>
<body>
<div id="header">
	<div id="logo">$page_title</div>
</div>
<div id="content">
<p>
	Welcome to the <b>{$page_title}</b>.
	<br /><br />This is a script that you can use to install, uninstall, or upgrade the DB changes required for the <b>{$mod['name']}</b> <i><b>MOD</b>ification</I> to operate correctly on your phpBB3 forum.
</p>
<p>
	Please remember that this script only works with the DB changes required $for_name on phpBB3.
	<br /><br />Any file alterations or additions must be installed, uninstalled, or upgraded separately.
	<br /><br />Check the documentation $of_name for details on such steps.
</p>
EOH;

$page_tail = <<<EOH
	</div>
	<div id="footer">
		{$mod['name']} Copyright &copy; {$mod['copy_year']} by <a href="{$mod['website']}" title="Visit this web site for support">{$mod['author']}</a>
	</div>
</body>
</html>
EOH;

$url_append = $phpEx . '?sid=' . $user->data['session_id'];
$page_postaction = <<<EOH
	</p>

	<p class="alert"><b>Be sure to visit the <i><a href="{$phpbb_root_path}install_ip/db_update.$url_append">IP Country Flag Olympus DB Populator</a></i></b> that will populate the ISO_TABLE newly created with the latest IPS available at the moment.</p>
	
	<p class="alert"><b>Be sure to visit the <i><a href="{$phpbb_root_path}adm/index.$url_append">Administration Control Panel</a></i></b> to check and update any configuration options that may have been affected by this process.</p>

	<p><u>You should now delete install.php from your forum.</u></p>
EOH;

$results = array();
$db_errors = FALSE;
$db->sql_return_on_error(true);
$page_text = '';
if( empty($action) || !in_array($action, array('install', 'upgrade', 'uninstall')) )
{
	$page_text = <<<EOH
	<p class="alert">Please note that before proceeding, you should have or create a current full backup of your DB.
		<br /><br />A backup can be used to restore your forum to a state prior to the results of any actions taken by this installer, if necessary.</p>
	<p>
	<br /><br />Please select an option below to continue:
EOH;
	if( $install )
	{
		$page_text .= <<<EOH
	<ul>
		<li><a href="install.$url_append&amp;action=install">Install DB Changes</a></li><br />
EOH;
	}
	if( $uninstall )
	{
		$page_text .= <<<EOH
		<li><a href="install.$url_append&amp;action=uninstall">Uninstall DB Changes</a></li><br />
EOH;
	}
	if( $upgrade )
	{
		$page_text .= <<<EOH
		<li><a href="install.$url_append&amp;action=upgrade">Upgrade DB Changes $version_string</a></li><br />
EOH;
	}
	$page_text .= <<<EOH
	</ul>
	</p>
EOH;
}
else
{
	run_installer();
}

function run_installer()
{
	global $action, $page_text, $for_name, $results, $install, $uninstall, $upgrade;

	if( !$$action )
	{
		$page_text .= '<p>';
		switch( $action )
		{
			case 'install':
				$page_text .= 'Installation';
			break;
			case 'uninstall':
				$page_text .= 'Uninstallation';
			break;
			case 'upgrade':
				$page_text .= 'Upgrading';
			break;
		}
		$page_text .= ' is not supported for ' . $for_name . '.</p>';
	}
	else
	{
		$page_text = "
				<br /><p>This installer will now attempt to make the database changes{$for_name}.</p>";
		process_sql();
		process_modules();

		if( empty($results) )
		{
			$results[] = '<li>No changes were attempted! You may have already run the installer successfully.</li>';
		}
		$page_text .= '<ul>' . implode("\n", $results) . '</ul>
		<p>
		<br />The installer process is now complete.';
	}
}

function process_modules()
{
	global $action, $module_data;
	if( empty($module_data[$action]) )
	{
		return;
	}
	switch( $action )
	{
		case 'install':
			add_modules($module_data['install']);
		break;
		case 'upgrade':
			remove_modules($module_data['upgrade']['remove']);
			add_modules($module_data['upgrade']['add']);
		break;
		case 'uninstall':
			remove_modules($module_data['uninstall']);
		break;
	}
}

function get_cat_ids($details, $module_class)
{
	global $db;

	$parents = array();
	if( !isset($details['cat']) )
	{
		return array(0);
	}
	$cats = $details['cat'];
	$sql = 'SELECT module_id FROM ' . MODULES_TABLE . '
			WHERE ' . $db->sql_in_set('module_langname', $cats) . "
				AND module_class = '" . $db->sql_escape($module_class) . "'";
	$result = $db->sql_query($sql);
	while( $row = $db->sql_fetchrow($result) )
	{
		$parents[] = $row['module_id'];
	}
	$db->sql_freeresult($result);

	if( empty($parents) )
	{
		$parents = array(0);
	}

	return $parents;
}

function remove_modules($modules)
{
	global $db, $phpbb_root_path, $phpEx, $acp_modules;

	if( empty($modules) )
	{
		return;
	}
	foreach($modules as $k=>$v)
	{
		// Check if module name and mode exist...
		$module_basename	= $v['basename'];
		$module_class		= $v['class'];
		if( !check_for_info($module_class, $module_basename) )
		{
			continue;
		}
		$fileinfo = $acp_modules->get_module_infos($module_basename, $module_class);
		$fileinfo = $fileinfo[$module_basename];

		if( !empty($fileinfo['modes']) )
		{
			uninstall_modules($fileinfo['modes'], $module_class, $module_basename);
		}

		if( isset($fileinfo['new_parents']) )
		{
			uninstall_modules($fileinfo['new_parents'], $module_class, '', true);
		}
	}
}

function uninstall_modules($modules, $module_class, $module_basename, $cats_only = FALSE)
{
	global $acp_modules, $db, $user;
	$modules = array_reverse($modules);
	foreach($modules as $module_mode => $mode_details)
	{
		// We need to get the module_id for each mode.
		$sql = 'SELECT module_id FROM ' . MODULES_TABLE . "
				WHERE module_langname = '" . $db->sql_escape($mode_details['title']) . "'
					AND module_class = '" . $db->sql_escape($module_class) . "'";
		$result = $db->sql_query($sql);
		$rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		$acp_modules->module_class = $module_class;

		$msg = "Removal of module $module_basename, mode $module_mode";
		if( $cats_only )
		{
			$cat_name = ( isset($user->lang[$mode_details['title']]) ) ? $user->lang[$mode_details['title']] : $mode_details['title'];
			$msg = "Removal of menu category $cat_name";
		}

		if( empty($rows) )
		{
			add_result($msg, "This could not be removed because it was not already installed.");
			continue;
		}
		foreach($rows as $v)
		{
			$result = $acp_modules->delete_module($v['module_id']);
			if( !empty($result) )
			{
				add_result($msg, $result[0]);
			}
			else
			{
				add_result($msg);
			}
		}
	}
}

function check_for_info($module_class, $module_basename)
{
	global $phpbb_root_path, $phpEx;
	$module_file = $phpbb_root_path . 'includes/' . $module_class . '/info/' . $module_class . '_' .$module_basename . '.' . $phpEx;
	if( !@file_exists($module_file) )
	{
		add_result("Module $module_basename", "The module's info file has not been uploaded. The module cannot be edited without the info file.");
		return FALSE;
	}
	return TRUE;
}

function check_for_installed($mode_details, $module_class, $parents)
{
	global $db;

	$parent_sql = '';
	if( !empty($parents) )
	{
		if( !is_array($parents) )
		{
			$parents = array($parents);
		}
		$parent_sql = ' AND ' . $db->sql_in_set('parent_id', $parents);
	}
	$sql = 'SELECT module_id FROM ' . MODULES_TABLE . "
			WHERE module_langname = '" . $db->sql_escape($mode_details['title']) . "'
				AND module_class = '" . $db->sql_escape($module_class) . "'";
	$sql .= $parent_sql;
	$result = $db->sql_query($sql);
	$rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	if( empty($rows) )
	{
		return FALSE;
	}
	
	return TRUE;
}

function add_modules($new_modules)
{
	global $db, $phpbb_root_path, $phpEx, $acp_modules;

	if( empty($new_modules) )
	{
		return;
	}
	foreach($new_modules as $k=>$v)
	{
		// Check if module name and mode exist...
		$module_class		= $v['class'];
		$module_basename	= $v['basename'];
		if( !check_for_info($module_class, $module_basename) )
		{
			continue;
		}
		$fileinfo = $acp_modules->get_module_infos($module_basename, $module_class);
		$fileinfo = $fileinfo[$module_basename];

		if( isset($fileinfo['new_parents']) )
		{
			install_modules($fileinfo['new_parents'], $module_class, '', true);
		}

		if( !empty($fileinfo['modes']) )
		{
			install_modules($fileinfo['modes'], $module_class, $module_basename);
		}
	}
}

function install_modules($modules, $module_class, $module_basename, $cats_only = FALSE)
{
	global $acp_modules, $user;
	$install_basename = $module_basename;
	foreach($modules as $module_mode => $mode_details)
	{
		$install_mode = $module_mode;
		// We need to get the parent for the mode.
		$parents = get_cat_ids($mode_details, $module_class);

		$msg = "Addition of module $module_basename, mode $module_mode";
		if( $cats_only )
		{
			$module_mode			= '';
			$mode_details['auth']	= '';
			$install_basename		= '';
			$install_mode			= '';
			$cat_name = ( isset($user->lang[$mode_details['title']]) ) ? $user->lang[$mode_details['title']] : $mode_details['title'];
			$msg = "Addition of menu category $cat_name";
		}

		foreach($parents as $parent_id)
		{
			// Check for an already installed instance of this module
			// under this parent. If it is already present, we don't install
			// again.
			if( check_for_installed($mode_details, $module_class, $parent_id) )
			{
				break;
			}
			$module_data = array(
				'module_basename'	=> $install_basename,
				'module_enabled'	=> 1,
				'module_display'	=> (isset($mode_details['display'])) ? $mode_details['display'] : 1,
				'parent_id'			=> $parent_id,
				'module_class'		=> $module_class,
				'module_langname'	=> $mode_details['title'],
				'module_mode'		=> $install_mode,
				'module_auth'		=> $mode_details['auth'],
			);

			$errors = $acp_modules->update_module_data($module_data, true);
			if( !empty($errors) )
			{
				add_result($msg, $errors[0]);
				break;
			}

			add_result($msg);
		}
	}
}

function process_sql()
{
	global $action, $sql, $db, $db_errors;
	if( empty($sql[$action]) )
	{
		return;
	}

	foreach($sql[$action] as $v)
	{
		if( !$result = $db->sql_query($v) )
		{
			$error = $db->sql_error();
			add_result($v, $error['message']);
		}
		else
		{
			add_result($v);
		}
	}
}

function wrap_up()
{
	global $cache, $action, $mod;
	add_log('admin', "<strong>Executed a modification database installer</strong><br />{$mod['name']} $action");

	// Now we will purge the cache.
	// This is necessary for any inserted or removed configuration settings
	// to take affect.
	$cache->purge();
	add_log('admin', 'LOG_PURGE_CACHE');
}

function add_result($item, $msg = '')
{
	global $results, $db_errors;
	$str = '<li>' . htmlspecialchars($item) . '<br /><span class="';
	if( !empty($msg) )
	{
		$str .= 'error">Failed due to error: ' . $msg;
		$db_errors = TRUE;
	}
	else
	{
		$str .= 'success">Completed successfully!';
	}
	$str .= '</span></li>';
	$results[] = $str;
}

function add_error_note()
{
	global $mod, $db_errors, $action;

	if( !$db_errors )
	{
		return '';
	}

	$site_string = $error_text = '';
	if( !empty($mod['website']) && !empty($mod['sitename']) )
	{
		$site_string = '<a href="' . $mod['website'] . '">' . $mod['sitename'] . '</a> or ';
	}

	$error_text .= ' If any error messages are listed above, a problem was encountered during the ';
	
	switch( $action )
	{
		case 'install':
			$error_text .= 'install. Any errors mentioning that a table already exists or duplicate entries or column names are often the result of running the install a second time by accident. Usually these errors can be ignored unless other problems appear when using the modification.';
		break;
		case 'upgrade':
			$error_text .= 'upgrade and some portions of the modification may not have been upgraded.';
		break;
		case 'uninstall':
			$error_text .= 'uninstall and some portions of the modification may not have been uninstalled.';
		break;
	}
	$error_text .= ' If you need assistance in troubleshooting these errors, please visit the support forums at ' . $site_string . ' <a href="http://www.phpbbhacks.com">phpBBHacks.com</a>.';

	return $error_text;
}

echo $page_head;
echo $page_text;
if( !empty($action) )
{
	wrap_up();
	echo add_error_note();		
	echo $page_postaction;
}
echo $page_tail;
?>