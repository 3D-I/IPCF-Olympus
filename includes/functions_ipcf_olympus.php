<?php
/**
*
* @package phpBB3
* @author 3Di ( Marco ) http://gold.io3di.com
* @copyright (c) 2008 3Di
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit();
}

/**
 * Display extra stats, activity over the last 24 hours for logged-in users.
 *
 * @return bool
 */
function ipcf_activity_stats()
{
	global $template, $user, $auth;

	// if the user is a bot, we won’t even process this function...
	if ($user->data['is_bot'])
	{
		return false;
	}

	// obtain user activity data
	$active_users = ipcf_obtain_active_user_data();

	// 24 hour users online list, assign to the template block: lastvisit
    $users_online = 0;
    foreach ($active_users as $row)
    {
        if (!$row['user_viewonline'] && !$auth->acl_get('u_viewonline'))
        {
            // user does not have permission to view hidden users.
            continue;
        }
        
        $users_online++;

		// IP on registration for this
		$flag = ip_country_to_flag($row['user_ip']);
		$country = strtoupper($flag);

        $username_string = get_username_string((($row['user_type'] == USER_IGNORE) ? 'no_profile' : 'full'), $row['user_id'], '<img src="./images/flags/small/' .  $flag . '.png" width="14" height="9" alt="' . $user->lang['country'][$country] . '" title="' . $user->lang['country'][$country] . '" />' . ' ' . $row['username'], $row['user_colour']);
        
        $username_string = (!$row['user_viewonline']) ? '<em>' . $username_string . '</em>' : $username_string;
        
        $template->assign_block_vars('lastvisit', array(
            'USERNAME_FULL'    => $username_string,
        ));
    }    

	// assign the stats to the template.
	$template->assign_vars(array(
		'USERS_24HOUR_TOTAL'    => sprintf($user->lang['USERS_24HOUR_TOTAL'], $users_online),    
		//'USERS_24HOUR_TOTAL'	=> sprintf($user->lang['USERS_24HOUR_TOTAL'], sizeof($active_users)),
	));

	return true;
}

/**
 * Obtain an array of active users over the last 24 hours.
 *
 * @return array
 */
function ipcf_obtain_active_user_data()
{
	global $cache;

	if (($active_users = $cache->get('_active_users')) === false)
	{
		global $db;

		$active_users = array();

		// grab a list of users who are currently online
		// and users who have visited in the last 24 hours
		$sql_ary = array(
			'SELECT'	=> 'u.user_id, u.user_colour, u.username, u.user_type, u.user_ip, u.user_allow_viewonline, s.session_viewonline',
			'FROM'		=> array(USERS_TABLE => 'u'),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array(SESSIONS_TABLE => 's'),
					'ON'	=> 's.session_user_id = u.user_id',
				),
			),
			'WHERE'		=> 'u.user_lastvisit > ' . (time() - 86400) . ' OR s.session_user_id <> ' . ANONYMOUS,
			'GROUP_BY'	=> 'u.user_id',
			'ORDER_BY'	=> 'u.user_lastvisit DESC',
		);

		$result = $db->sql_query($db->sql_build_query('SELECT', $sql_ary));

		while ($row = $db->sql_fetchrow($result))
		{
			$active_users[$row['user_id']] = array(
				'user_viewonline'	=> ($row['session_viewonline']) ? $row['session_viewonline'] : $row['user_allow_viewonline'],
				'user_id'			=> $row['user_id'],
				'user_type'			=> $row['user_type'],
				'username'			=> $row['username'],
				'user_colour'		=> $row['user_colour'],
				'user_ip'			=> $row['user_ip'],
			);
		}
		$db->sql_freeresult($result);

		// cache this data for 15 minutes, this improves performance
		$cache->put('_active_users', $active_users, 900);
	}

	return $active_users;
}

?>