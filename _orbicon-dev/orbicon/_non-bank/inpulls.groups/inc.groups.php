<?php
/**
 * Inpulls groups main include
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconMOD
 * @subpackage Inpulls
 * @version 1.0
 * @link http://www.inpulls.com
 * @license http://www.inpulls.com
 * @since 2007-12-20
 * @todo translation
 */

define('TABLE_INPULLS_GROUPS', 'orbx_mod_inpulls_groups');
define('TABLE_INPULLS_GROUP_MEMBERS', 'orbx_mod_inpulls_group_members');

define('INPULLS_GRP_MEMBER_DISABLED', 	0);
define('INPULLS_GRP_MEMBER_LIVE', 		1);
define('INPULLS_GRP_MEMBER_WAITING', 	2);

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param string $group
 * @return resource
 */
function get_group($group = '', $sort_by = '', $limit = false)
{
	global $dbc;

	if(!$group && isset($_GET['config'])) {
		return false;
	}

	$group_sql = ($group) ? sprintf(' AND (permalink = %s) LIMIT 1', $dbc->_db->quote($group)) : '';

	if($limit) {
		$q = sprintf('	SELECT 		*
						FROM 	' . TABLE_INPULLS_GROUPS .'
						WHERE 		(live = 1) ' .
									$group_sql . $sort_by .'
						LIMIT		%s, %s',
						$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']), $dbc->_db->quote($_GET['pp']));
	}
	else {
		$q = '	SELECT 		*
				FROM 	' . TABLE_INPULLS_GROUPS .'
				WHERE 		(live = 1) ' .
				$group_sql . $sort_by;
	}

	return $dbc->_db->query($q);
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param unknown_type $group_id
 * @return unknown
 */
function get_grp_total_live_members($group_id)
{
	global $dbc;

	$r = $dbc->_db->query(sprintf('	SELECT 		COUNT(id)
									AS 			total
									FROM 	' . TABLE_INPULLS_GROUP_MEMBERS . '
									WHERE 		(group_id = %s) AND
												(status = %s)', $dbc->_db->quote($group_id), $dbc->_db->quote(INPULLS_GRP_MEMBER_LIVE)));

	$a = $dbc->_db->fetch_assoc($r);

	// add group owner as well
	return ($a['total'] + 1);
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param unknown_type $group_id
 * @return unknown
 */
function get_grp_all_members($group_id)
{
	global $dbc;

	return $dbc->_db->query(sprintf('	SELECT 		*
										FROM 	' . TABLE_INPULLS_GROUP_MEMBERS . '
										WHERE 		(group_id = %s)
										ORDER BY	member_since DESC', $dbc->_db->quote($group_id)));
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param unknown_type $permalink
 * @return unknown
 */
function get_grp_id_from_permalink($permalink)
{
	global $dbc;
	$r = $dbc->_db->query(sprintf('	SELECT 		id
									FROM 	' . TABLE_INPULLS_GROUPS . '
									WHERE 		(permalink = %s)', $dbc->_db->quote($permalink)));
	$a = $dbc->_db->fetch_assoc($r);

	return $a['id'];
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param unknown_type $id
 * @return unknown
 */
function get_grp_permalink_from_id($id)
{
	global $dbc;
	$r = $dbc->_db->query(sprintf('	SELECT 		permalink
									FROM 	' . TABLE_INPULLS_GROUPS . '
									WHERE 		(id = %s)', $dbc->_db->quote($id)));
	$a = $dbc->_db->fetch_assoc($r);

	return $a['permalink'];
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return unknown
 */
function new_group()
{
	global $dbc;

	$permalink = get_permalink($_POST['title']);

	if(!get_grp_id_from_permalink($permalink)) {
		$q = sprintf('
					INSERT INTO 	'.TABLE_INPULLS_GROUPS.'
									(title, permalink,
									owner_id, intro_txt, live_from,
									disable_new_users, require_auth_new_users)
					VALUES			(%s, %s,
									%s, %s, UNIX_TIMESTAMP(),
									%s, %s)',
		$dbc->_db->quote($_POST['title']),	$dbc->_db->quote($permalink),
		$dbc->_db->quote($_SESSION['user.r']['id']), $dbc->_db->quote($_POST['intro_txt']),
		$dbc->_db->quote($_POST['disable_new']), $dbc->_db->quote($_POST['auth']));

		$dbc->_db->query($q);

		$new_id = $dbc->_db->insert_id();

		upload_grp_gfx('intro_gfx', 'intro_gfx', $new_id);
		upload_grp_gfx('members_gfx', 'members_gfx', $new_id);

		return $new_id;
	}
	else {
		echo '<script>window.alert(\'Grupa naziva "'.str_sanitize($_POST['title'], STR_SANITIZE_JAVASCRIPT).'" već postoji!\')</script>';
		return false;
	}
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $group_id
 * @return bool
 */
function edit_group($group_id)
{
	global $dbc;

	$permalink = get_permalink($_POST['title']);

	if((get_grp_id_from_permalink($permalink) && ($_POST['id'] != $group_id))) {
		echo '<script>window.alert(\'Grupa naziva "'.str_sanitize($_POST['title'], STR_SANITIZE_JAVASCRIPT).'" već postoji!\')</script>';
		return false;
	}
	else {
		$q = sprintf('	UPDATE 	' . TABLE_INPULLS_GROUPS . '
				SET 	title=%s, permalink=%s,
						intro_txt=%s, disable_new_users=%s,
						require_auth_new_users=%s
				WHERE 	(id = %s)',
				$dbc->_db->quote($_POST['title']), $dbc->_db->quote($permalink),
				$dbc->_db->quote($_POST['intro_txt']), $dbc->_db->quote($_POST['disable_new']),
				$dbc->_db->quote($_POST['auth']),
						$dbc->_db->quote($group_id));

		$dbc->_db->query($q);

		upload_grp_gfx('intro_gfx', 'intro_gfx', $group_id);
		upload_grp_gfx('members_gfx', 'members_gfx', $group_id);

		return true;
	}
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param string $type
 * @param string $sql_column
 * @param int $group_id
 * @return string
 */
function upload_grp_gfx($type, $sql_column, $group_id)
{

	if(validate_upload($_FILES[$type]['tmp_name'], $_FILES[$type]['name'], $_FILES[$type]['size'], $_FILES[$type]['error'])) {

		list($width, $height, $img_type, $attr) = getimagesize($_FILES[$type]['tmp_name']);

		require_once DOC_ROOT . '/orbicon/venus/class.venus.php';
		$venus = new Venus;

		if($type == 'members_gfx') {
			if(($width <= 50) && ($height <= 50)) {
				$file = $venus->_insert_image_to_db($_FILES[$type]['name'], $_FILES[$type]['tmp_name'], 'inpulls_grp');
			}
		}
		else {
			$file = $venus->_insert_image_to_db($_FILES[$type]['name'], $_FILES[$type]['tmp_name'], 'inpulls_grp');
			_inpulls_img_size_fix($file, $venus);
			$venus->watermark_image(DOC_ROOT . '/site/venus/' . $file, DOC_ROOT . '/site/gfx/watermark.png');
		}

		$venus = null;

		global $dbc;
		$q = sprintf('	UPDATE 	' . TABLE_INPULLS_GROUPS . '
						SET 	'.$sql_column.'=%s
						WHERE 	(id = %s)', $dbc->_db->quote($file), $dbc->_db->quote($group_id));
		$dbc->_db->query($q);

		return $file;
	}

	return null;
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param unknown_type $percent
 * @param unknown_type $group_id
 */
function modify_grp_activity($percent, $group_id)
{
	global $dbc;

	$q = sprintf('	UPDATE 	' . TABLE_INPULLS_GROUPS . '
					SET 	activity = (activity + %s)
					WHERE 	(id = %s)',
					$dbc->_db->quote($percent), $dbc->_db->quote($group_id));

	$dbc->_db->query($q);
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param unknown_type $user_id
 * @param unknown_type $group_id
 * @return unknown
 */
function get_grp_is_owner($user_id, $group_id)
{
	global $dbc;
	$r = $dbc->_db->query(sprintf('	SELECT 		id
									FROM 	' . TABLE_INPULLS_GROUPS . '
									WHERE 		(id = %s) AND
												(owner_id = %s)', $dbc->_db->quote($group_id), $dbc->_db->quote($user_id)));
	$a = $dbc->_db->fetch_assoc($r);

	return $a['id'];
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param unknown_type $user_id
 * @param unknown_type $group_id
 * @return unknown
 */
function get_grp_is_member($user_id, $group_id)
{
	$owner = get_grp_is_owner($user_id, $group_id);
	if($owner) {
		return $owner;
	}

	global $dbc;
	$r = $dbc->_db->query(sprintf('	SELECT 		id
									FROM 	' . TABLE_INPULLS_GROUP_MEMBERS . '
									WHERE 		(user_reg_id = %s) AND
												(group_id = %s) AND
												(status != %s)', $dbc->_db->quote($user_id), $dbc->_db->quote($group_id),
												$dbc->_db->quote(INPULLS_GRP_MEMBER_DISABLED)));
	$a = $dbc->_db->fetch_assoc($r);

	return $a['id'];
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param unknown_type $owner_id
 * @param unknown_type $group_id
 */
function set_group_owner($owner_id, $group_id)
{
	global $dbc;

	$q = sprintf('	UPDATE 	' . TABLE_INPULLS_GROUPS . '
					SET 	owner_id = %s
					WHERE 	(id = %s)',
					$dbc->_db->quote($owner_id), $dbc->_db->quote($group_id));

	$dbc->_db->query($q);
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param unknown_type $user_id
 * @param unknown_type $group_id
 * @return unknown
 */
function new_group_member($user_id, $group_id)
{
	if(!get_grp_is_member($user_id, $group_id)) {
		global $dbc;

		$status = get_group(get_grp_permalink_from_id($group_id));
		$status = $dbc->_db->fetch_assoc($status);

		// exit here
		if($status['disable_new_users']) {
			return -1;
		}

		$status = $status['require_auth_new_users'];
		$status = ($status) ? INPULLS_GRP_MEMBER_WAITING : INPULLS_GRP_MEMBER_LIVE;

		$q = sprintf('
					INSERT INTO 	'.TABLE_INPULLS_GROUP_MEMBERS.'
									(group_id, user_reg_id,
									status, member_since)
					VALUES			(%s, %s,
									%s, UNIX_TIMESTAMP())',
		$dbc->_db->quote($group_id), $dbc->_db->quote($user_id),
		$dbc->_db->quote($status));

		$dbc->_db->query($q);

		return  $dbc->_db->insert_id();
	}
	return false;
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param unknown_type $user_id
 * @param unknown_type $group_id
 */
function leave_group($user_id, $group_id)
{
	if(get_grp_is_member($user_id, $group_id)) {
		global $dbc;

		$q = sprintf('
					DELETE FROM 	'.TABLE_INPULLS_GROUP_MEMBERS.'
					WHERE			(group_id=%s) AND
									(user_reg_id=%s)',
		$dbc->_db->quote($group_id), $dbc->_db->quote($user_id));

		$dbc->_db->query($q);
	}
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param unknown_type $status
 * @param unknown_type $user_id
 * @param unknown_type $group_id
 */
function set_grp_member_status($status, $user_id, $group_id)
{
	global $dbc;

	$q = sprintf('	UPDATE 	' . TABLE_INPULLS_GROUP_MEMBERS . '
					SET 	status = %s
					WHERE 	(user_reg_id = %s) AND
							(group_id = %s)',
					$dbc->_db->quote($status),
					$dbc->_db->quote($user_id), $dbc->_db->quote($group_id));

	$dbc->_db->query($q);
}

/**
 * Get member's status
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $user_id
 * @param int $group_id
 * @return int
 */
function get_grp_member_status($user_id, $group_id)
{
	global $dbc;
	$r = $dbc->_db->query(sprintf('	SELECT 		status
									FROM 	' . TABLE_INPULLS_GROUP_MEMBERS . '
									WHERE 		(user_reg_id = %s) AND
												(group_id = %s)', $dbc->_db->quote($user_id), $dbc->_db->quote($group_id)));
	$a = $dbc->_db->fetch_assoc($r);

	return (int) $a['status'];
}

/**
 * Resize image if larger than 200Kb
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param string $file
 * @param object $venus
 * @access private
 */
function _inpulls_img_size_fix($file, $venus)
{
	$file = DOC_ROOT . '/site/venus/' . $file;
	list($w, $h) = getimagesize($file);

	if($w > 480) {
		exec('mogrify -resize 480x ' . $file);
		//$venus->generate_thumbnail($file, $file, 640);
		update_sync_cache_list($file);
	}

/*	if(filesize($file) > 204800) {

		list($w, $h) = getimagesize($file);
		$w = intval($w * (75 / 100));
		$h = intval($h * (75 / 100));

		$venus->generate_thumbnail($file, $file, $w, $h, null, 75);
		update_sync_cache_list($file);
	}*/
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param unknown_type $user_id
 * @return unknown
 */
function get_user_group_id($user_id)
{
	global $dbc;
	$r = $dbc->_db->query(sprintf('	SELECT 		id
									FROM 	' . TABLE_INPULLS_GROUPS . '
									WHERE 		(owner_id = %s)', $dbc->_db->quote($user_id)));
	$a = $dbc->_db->fetch_assoc($r);

	return $a['id'];
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $user_id
 * @return resource
 */
function get_user_groups($user_id)
{
	global $dbc;
	return $dbc->_db->query(sprintf('	SELECT 		*
										FROM 	' . TABLE_INPULLS_GROUPS . '
										WHERE 		(id IN (SELECT group_id FROM '.TABLE_INPULLS_GROUP_MEMBERS.' WHERE (user_reg_id = %s) AND (status = %s)))', $dbc->_db->quote($user_id), $dbc->_db->quote(INPULLS_GRP_MEMBER_LIVE)));
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $user_id
 * @return string
 */
function print_users_groups($user_id)
{
	global $dbc, $orbicon_x;
	$r = get_user_groups($user_id);
	$group = $dbc->_db->fetch_object($r);

	$groups = array();

	$my_group = get_user_group_id($user_id);
	if($my_group) {
		$my_group = get_group(get_grp_permalink_from_id($my_group));
		$my_group = $dbc->_db->fetch_object($my_group);

		$badge = DOC_ROOT . '/site/venus/' . $my_group->members_gfx;
		$badge = is_file($badge) ? '<img src="'.ORBX_SITE_URL.'/site/venus/'.$my_group->members_gfx.'" /> ' : '';

		$groups[] = '<a title="'.$my_group->title.'" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.urlencode($my_group->permalink).'">'.$badge.$my_group->title.'</a>';
	}

	while($group) {
		$badge = DOC_ROOT . '/site/venus/' . $group->members_gfx;
		$badge = is_file($badge) ? '<img src="'.ORBX_SITE_URL.'/site/venus/'.$group->members_gfx.'" /> ' : '';

		$groups[] = '<a title="'.$group->title.'" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.urlencode($group->permalink).'">'.$badge.$group->title.'</a>';
		$group = $dbc->_db->fetch_object($r);
	}

	$groups = array_unique($groups);
	$groups = implode(', ', $groups);
	return $groups;
}

/**
 * Calculate group activity
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $group_id
 * @return int
 */
function get_grp_activity($group_id)
{
	global $dbc;
	$group = get_group(get_grp_permalink_from_id($group_id));
	$group = $dbc->_db->fetch_object($group);

	$days = ((time() - $group->live_from) / 86400);
	$weeks = (intval($days / 7) * 5);
	$activity = $group->activity - $weeks;

	// sanitize - it's better without sanitize
	/*if($activity < 0) {
		$activity = 0;
	}
	elseif ($activity > 100) {
		$activity = 100;
	}*/

	return $activity;
}

?>