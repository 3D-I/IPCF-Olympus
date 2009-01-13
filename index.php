<?php
/**
*
* @package phpBB3
* @version $Id: index.php 8987 2008-10-09 14:17:02Z acydburn $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*/

/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('viewforum');

display_forums('', $config['load_moderators']);

// Set some stats, get posts count from forums data if we... hum... retrieve all forums data
$total_posts	= $config['num_posts'];
$total_topics	= $config['num_topics'];
$total_users	= $config['num_users'];

$l_total_user_s = ($total_users == 0) ? 'TOTAL_USERS_ZERO' : 'TOTAL_USERS_OTHER';
$l_total_post_s = ($total_posts == 0) ? 'TOTAL_POSTS_ZERO' : 'TOTAL_POSTS_OTHER';
$l_total_topic_s = ($total_topics == 0) ? 'TOTAL_TOPICS_ZERO' : 'TOTAL_TOPICS_OTHER';

// Grab group details for legend display
if ($auth->acl_gets('a_group', 'a_groupadd', 'a_groupdel'))
{
	$sql = 'SELECT group_id, group_name, group_colour, group_type
		FROM ' . GROUPS_TABLE . '
		WHERE group_legend = 1
		ORDER BY group_name ASC';
}
else
{
	$sql = 'SELECT g.group_id, g.group_name, g.group_colour, g.group_type
		FROM ' . GROUPS_TABLE . ' g
		LEFT JOIN ' . USER_GROUP_TABLE . ' ug
			ON (
				g.group_id = ug.group_id
				AND ug.user_id = ' . $user->data['user_id'] . '
				AND ug.user_pending = 0
			)
		WHERE g.group_legend = 1
			AND (g.group_type <> ' . GROUP_HIDDEN . ' OR ug.user_id = ' . $user->data['user_id'] . ')
		ORDER BY g.group_name ASC';
}
$result = $db->sql_query($sql);

$legend = array();
while ($row = $db->sql_fetchrow($result))
{
	$colour_text = ($row['group_colour']) ? ' style="color:#' . $row['group_colour'] . '"' : '';
	$group_name = ($row['group_type'] == GROUP_SPECIAL) ? $user->lang['G_' . $row['group_name']] : $row['group_name'];

	if ($row['group_name'] == 'BOTS' || ($user->data['user_id'] != ANONYMOUS && !$auth->acl_get('u_viewprofile')))
	{
		$legend[] = '<span' . $colour_text . '>' . $group_name . '</span>';
	}
	else
	{
		$legend[] = '<a' . $colour_text . ' href="' . append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=group&amp;g=' . $row['group_id']) . '">' . $group_name . '</a>';
	}
}
$db->sql_freeresult($result);

$legend = implode(', ', $legend);

// Generate birthday list if required ...
$birthday_list = '';
if ($config['load_birthdays'] && $config['allow_birthdays'])
{
	$now = getdate(time() + $user->timezone + $user->dst - date('Z'));
// --- IP Country Flag Olympus start --------------------
// add: , user_ip
	$sql = 'SELECT user_id, username, user_colour, user_birthday, user_ip
		FROM ' . USERS_TABLE . "
		WHERE user_birthday LIKE '" . $db->sql_escape(sprintf('%2d-%2d-', $now['mday'], $now['mon'])) . "%'
			AND user_type IN (" . USER_NORMAL . ', ' . USER_FOUNDER . ')';
	$result = $db->sql_query($sql);
// --- IP Country Flag Olympus end ----------------------
	while ($row = $db->sql_fetchrow($result))
	{
// --- IP Country Flag Olympus start --------------------
// add
		$flag = ip_country_to_flag($row['user_ip']);
		$country = strtoupper($flag);
// replace
/*
		$birthday_list .= (($birthday_list != '') ? ', ' : '') . get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']);
*/
		$birthday_list .= (($birthday_list != '') ? ', ' : '') . get_username_string('full', $row['user_id'], '<img src="images/flags/small/' .  $flag . '.png" width="14" height="9" alt="' . $user->lang['country'][$country] . '" title="' . $user->lang['country'][$country] . '" />' . ' ' . $row['username'], $row['user_colour']);
// --- IP Country Flag Olympus end ----------------------
		if ($age = (int) substr($row['user_birthday'], -4))
		{
			$birthday_list .= ' (' . ($now['year'] - $age) . ')';
		}
	}
	$db->sql_freeresult($result);
}

//
//	Past guests based on session lenght (3600 = 1 hrs)
//
$online_guests = '';

$time = (time() - (intval($config['session_length']))); 

$sql = 'SELECT session_ip, session_time, session_user_id
	FROM ' . SESSIONS_TABLE . '
	WHERE session_user_id = ' . ANONYMOUS . '
		AND session_time >= ' . $time . '
	ORDER BY session_time DESC';
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result))
{
	$flag = ip_country_to_flag($row['session_ip']);
	$country = strtoupper($flag);

	$online_guests .= ( $online_guests != '' ) ? ', ' . '<img src="images/flags/small/' .  $flag . '.png" width="14" height="9" alt="' . $user->lang['country'][$country] . '" title="' . $user->lang['country'][$country] . '" />' : '<img src="images/flags/small/' .  $flag . '.png" width="14" height="9" alt="' . $user->lang['country'][$country] . '" title="' . $user->lang['country'][$country] . '" />';
}
if (!$online_guests)
{
	$online_guests .= $user->lang['GUEST_ONLINE_NONE'];
}

$db->sql_freeresult($result);

//
//	Guests online thinghy
//
	
$time = (time() - (intval($config['load_online_time']) * 60));

$guests_online_ip = '';

// Get online guests
$sql = 'SELECT DISTINCT s.session_ip
	FROM ' . SESSIONS_TABLE . ' s
	WHERE s.session_user_id = ' . ANONYMOUS . '
		AND s.session_time >= ' . $time . '
	ORDER BY session_time DESC';
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	// IP on sessions here..
	$flag = ip_country_to_flag($row['session_ip']);
	$country = strtoupper($flag);

	$guests_online_ip .= ( $guests_online_ip != '' ) ? ', ' . '<img src="images/flags/small/' .  $flag . '.png" width="14" height="9" alt="' . $user->lang['country'][$country] . '" title="' . $user->lang['country'][$country] . '" />' : '<img src="images/flags/small/' .  $flag . '.png" width="14" height="9" alt="' . $user->lang['country'][$country] . '" title="' . $user->lang['country'][$country] . '" />';
}
if (!$guests_online_ip)
{
	$guests_online_ip .= $user->lang['GUEST_ONLINE_NONE'];
}
$db->sql_freeresult($result);
//
// let's give the appropriate flag to the newest user
// 
$flag = ip_country_to_flag($config['newest_user_reg_ip']);
$country = strtoupper($flag);
// --- IP Country Flag Olympus end ----------------------

// Assign index specific vars
$template->assign_vars(array(
	'TOTAL_POSTS'	=> sprintf($user->lang[$l_total_post_s], $total_posts),
	'TOTAL_TOPICS'	=> sprintf($user->lang[$l_total_topic_s], $total_topics),
	'TOTAL_USERS'	=> sprintf($user->lang[$l_total_user_s], $total_users),
// --- IP Country Flag Olympus start --------------------
// replace
/*
	'NEWEST_USER'	=> sprintf($user->lang['NEWEST_USER'], get_username_string('full', $config['newest_user_id'], $config['newest_username'], $config['newest_user_colour'])),
*/
	'NEWEST_USER'	=> sprintf($user->lang['NEWEST_USER'], get_username_string('full', $config['newest_user_id'], '<img src="images/flags/small/' .  $flag . '.png" width="14" height="9" alt="' . $user->lang['country'][$country] . '" title="' . $user->lang['country'][$country] . '" />' . ' ' . $config['newest_username'], $config['newest_user_colour'])),
// --- IP Country Flag Olympus end ----------------------

	'LEGEND'		=> $legend,
	'BIRTHDAY_LIST'	=> $birthday_list,
// --- IP Country Flag Olympus start --------------------
// add
	'L_GUESTS_ONLINE'	=> $user->lang['GUESTS_ONLINE'],
	'GUESTS_ONLINE'		=> $guests_online_ip,
	'ONLINE_GUESTS'		=> $online_guests,
	'L_PAST_GUESTS'		=> $user->lang['PAST_GUESTS'],
// --- IP Country Flag Olympus end ----------------------
	'FORUM_IMG'				=> $user->img('forum_read', 'NO_NEW_POSTS'),
	'FORUM_NEW_IMG'			=> $user->img('forum_unread', 'NEW_POSTS'),
	'FORUM_LOCKED_IMG'		=> $user->img('forum_read_locked', 'NO_NEW_POSTS_LOCKED'),
	'FORUM_NEW_LOCKED_IMG'	=> $user->img('forum_unread_locked', 'NO_NEW_POSTS_LOCKED'),

	'S_LOGIN_ACTION'			=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=login'),
	'S_DISPLAY_BIRTHDAY_LIST'	=> ($config['load_birthdays']) ? true : false,

	'U_MARK_FORUMS'		=> ($user->data['is_registered'] || $config['load_anon_lastread']) ? append_sid("{$phpbb_root_path}index.$phpEx", 'hash=' . generate_link_hash('global') . '&amp;mark=forums') : '',
	'U_MCP'				=> ($auth->acl_get('m_') || $auth->acl_getf_global('m_')) ? append_sid("{$phpbb_root_path}mcp.$phpEx", 'i=main&amp;mode=front', true, $user->session_id) : '')
);

// --- IP Country Flag Olympus start -------------------- 
include($phpbb_root_path . 'includes/functions_ipcf_olympus.' . $phpEx);
ipcf_activity_stats();
// --- IP Country Flag Olympus end ----------------------

// Output page
page_header($user->lang['INDEX']);

$template->set_filenames(array(
	'body' => 'index_body.html')
);

page_footer();

?>