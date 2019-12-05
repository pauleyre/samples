<?php

/**
 * Class for private mails
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemMOD
 * @subpackage PeopleRing
 * @version 1.00
 * @link http://
 * @license http://
 * @since 2007-09-10
 */

define('TABLE_PR_MAILS', 		'pring_mails');

define('PR_MAILBOX_INBOX', 		'INBOX');
define('PR_MAILBOX_SENT', 		'SENT');
define('PR_MAILBOX_TRASH', 		'TRASH');
define('PR_MAIL_UNREAD',		1);
define('PR_MAIL_READ', 			2);
define('PR_MAIL_ALL', 			4);

class PrivateMailer
{
	/**
	 * Mailer user ID (the owner)
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @var int
	 */
	var $user_id;

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $user_id
	 */
	function __construct($user_id)
	{
		if(!is_int($user_id)) {
			trigger_error('PrivateMailer() expects parameter 1 to be integer, '.gettype($user_id).' given', E_USER_NOTICE);
		}

		$this->user_id = $user_id;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $user_id
	 * @return PrivateMailer
	 */
	function PrivateMailer($user_id)
	{
		$this->__construct($user_id);
	}

	/**
	 * Emtpy trash
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 */
	function empty_trash()
	{
		$this->empty_all(PR_MAILBOX_TRASH);
	}

	/**
	 * Delete all from mailbox
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $mailbox
	 */
	function empty_all($mailbox)
	{
		global $dbc;

		$dbc->_db->query(sprintf('	DELETE
									FROM 	'.TABLE_PR_MAILS.'
									WHERE 	(mailbox = %s) AND
											(owner_id = %s)',
		$dbc->_db->quote($mailbox), $dbc->_db->quote($this->user_id)));
	}

	/**
	 * Sends private message
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return bool
	 */
	function send($to, $subject, $message)
	{
		if(($to == '') || ($message == '')) {
			trigger_error('send() expects parameters 1 and 3 to be non-empty', E_USER_WARNING);
			return false;
		}

		if($subject == '') {
			$subject = '(no subject)';
		}

		global $dbc, $orbx_log;

		require_once DOC_ROOT . '/orbicon/magister/class.magister.php';
		$magister = new Magister();

		$message = utf8_html_entities(trim(stripslashes($message)));
		$message = $magister->close_tags($message);
		$message = $magister->hyperlinks_add($message);
		$magister = null;

		if($_FILES['atch']) {
			$attachments = $this->upload_attachments();
			$attachments = implode(',', $attachments);
		}

		// add to senders SENT mailbox
		$id = $this->_send_helper(PR_MAILBOX_SENT, $this->user_id, $to, $subject, $message, $attachments);
		$this->set_read($id);

		$contacts = explode(',', $to);

		if(!is_array($contacts) || ($contacts[0] == '')) {
			$orbx_log->ewrite('No contacts found for private mail', __LINE__, __FUNCTION__);
			return false;
		}

		$contacts = array_map('trim', $contacts);
		$contacts = array_unique($contacts);

		// spam check
		$target_mailbox = ($this->is_spam($subject, $message)) ? PR_MAILBOX_TRASH : PR_MAILBOX_INBOX;

		foreach ($contacts as $contact) {

			$q = sprintf('	SELECT 		id
							FROM 		'.TABLE_REG_USERS.'
							WHERE 		(username = %s)
							LIMIT 		1',
							$dbc->_db->quote($contact)
							);

			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			if(!empty($a['id'])) {
				$this->_send_helper($target_mailbox, $a['id'], $to, $subject, $message, $attachments);
			}
		}

		return true;
	}

	/**
	 * Helper function for send
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $mailbox
	 * @param int $owner_id
	 * @param string $to
	 * @param string $subject
	 * @param string $message
	 * @return int
	 */
	function _send_helper($mailbox, $owner_id, $to, $subject, $message, $attachments = '')
	{
		global $dbc;
		$q = sprintf('	INSERT INTO 	'.TABLE_PR_MAILS.'
										(mail_to, mail_from,
										subject, body,
										mailbox, mail_date,
										owner_id, attachment)
						VALUES 			(%s, %s,
										%s, %s,
										%s, %s,
										%s, %s)',
		$dbc->_db->quote($to), $dbc->_db->quote($this->user_id),
		$dbc->_db->quote($subject), $dbc->_db->quote($message),
		$dbc->_db->quote($mailbox), $dbc->_db->quote(time()),
		$dbc->_db->quote($owner_id), $dbc->_db->quote($attachments));

		$dbc->_db->query($q);

		return $dbc->_db->insert_id();
	}

	/**
	 * Count mails of PR_MAIL_* type in mailbox
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $mailbox
	 * @param int $type
	 * @return int
	 */
	function count_mails($mailbox, $type = PR_MAIL_ALL)
	{
		global $dbc;

		$extra_sql = '';
		if($type != PR_MAIL_ALL) {
			if($type == PR_MAIL_READ) {
				$extra_sql = ' AND (mail_read = 1)';
			}
			else if($type == PR_MAIL_UNREAD) {
				$extra_sql = ' AND (mail_read = 0)';
			}
			else {
				trigger_error('count_mails() expects parameter 2 to be one of PR_MAIL_* constants', E_USER_WARNING);
				return false;
			}
		}

		$q = sprintf('	SELECT 		COUNT(id) AS total
						FROM 		'.TABLE_PR_MAILS.'
						WHERE 		(mailbox = %s) AND
									(owner_id = %s)' . $extra_sql,
						$dbc->_db->quote(trim($mailbox)),
						$dbc->_db->quote($this->user_id)
					);

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		return $a['total'];
	}

	/**
	 * Print table with mails
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $mailbox
	 * @return unknown
	 */
	function mailbox_table($mailbox)
	{
		global $dbc, $orbicon_x;
		$table = '<table id="table_msg_'.$mailbox.'" class="mailbox_table" style="width:100%">';

		$q = sprintf('	SELECT 		*
						FROM 		'.TABLE_PR_MAILS.'
						WHERE 		(mailbox = %s) AND
									(owner_id = %s)
						ORDER BY	mail_date DESC',
						$dbc->_db->quote(trim($mailbox)),
						$dbc->_db->quote($this->user_id)
					);

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		if(empty($a) || !$r) {
			return _L('pr-no_mail');
		}

		include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.peoplering.php';

		$i = 0;
		$pr = new Peoplering();

		while($a) {

			$class = (($i % 2) == 0) ? 'class="odd"' : '';

			$pr_id = $pr->get_prid_from_rid($a['mail_from']);
			$name = $pr->get_username($a['mail_from']);
			$name = $name['username'];
			$picture = $pr->get_picture($pr_id);

			if(is_file(DOC_ROOT . '/site/venus/thumbs/t-' . $picture)) {
				$picture = ORBX_SITE_URL.'/site/venus/thumbs/t-' . $picture;
			}
			elseif(is_file(DOC_ROOT . '/site/venus/' . $picture)) {
				$picture = ORBX_SITE_URL.'/site/venus/' . $picture;
			}
			else {
				$picture = ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/unknownUser.gif';
			}

			$style = ($a['mail_read'] == 1) ? 'font-weight:normal' : 'font-weight:bold';

			$attach_icon = ($a['attachment']) ? '<img src="'.ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/attach.gif" /> ' : '';

			$table .= '<tr '.$class.' xonclick="javascript: $(\'prv_mail_'.$a['id'].'\').checked = !($(\'prv_mail_'.$a['id'].'\').checked);">
	                    <td style="text-align:center"><a href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$name, ORBX_SITE_URL . '/~' . $name).'"><img src="'.$picture.'" style="border: none;" class="pr_mail_avatar" /></a></td>
	                    <td><a style="'.$style.'" href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$name, ORBX_SITE_URL . '/~' . $name).'">'.$name.'</a></td>
	                    <td><a style="'.$style.'" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=read&amp;mail='.$a['id'].'">'.$attach_icon.$a['subject'].'</a></td>
	                    <td style="text-align:right;'.$style.'">'.date($_SESSION['site_settings']['date_format'] . ' H:i', $a['mail_date']).'</td>
	                    <td style="text-align:right;"><input id="prv_mail_'.$a['id'].'" name="prv_mail[]" value="'.$a['id'].'" type="checkbox" /></td>
	                  </tr>';
			$a = $dbc->_db->fetch_assoc($r);
			$i ++;
		}

		$table .= '</table>';

		$table .= '
		<a href="javascript:void(null)" onclick="javascript: checkUncheck(true, \'table_msg_'.$mailbox.'\');">'._L('pr_select_all').'</a> |
		<a href="javascript:void(null)" onclick="javascript: checkUncheck(false, \'table_msg_'.$mailbox.'\');">'._L('pr_unselect_all').'</a>';

		return $table;
	}

	/**
	 * Move emails to mailbox. Returns total messages moved
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param array $arr
	 * @param string $mailbox
	 * @return int
	 */
	function move($arr, $mailbox)
	{
		if(!is_array($arr)) {
			trigger_error('move() expects parameter 1 to be array, ' . gettype($arr) . 'given', E_USER_NOTICE);
			$arr = array($arr);
		}

		global $dbc;

		$mailbox = trim($mailbox);

		foreach($arr as $mail_id) {
			$q = sprintf('	UPDATE 		'.TABLE_PR_MAILS.'
							SET 		mailbox = %s
							WHERE		(id = %s) AND
										(owner_id = %s)',
							$dbc->_db->quote($mailbox),
							$dbc->_db->quote($mail_id),
							$dbc->_db->quote($this->user_id)
						);

			$dbc->_db->query($q);
		}

		return mysql_affected_rows($dbc->_db->get_link());
	}

	/**
	 * Deletes mails. Returns total messages deleted
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param array $arr
	 * @return int
	 */
	function delete($arr)
	{
		if(!is_array($arr)) {
			trigger_error('delete() expects parameter 1 to be array, ' . gettype($arr) . 'given', E_USER_NOTICE);
			$arr = array($arr);
		}

		global $dbc;

		foreach($arr as $mail_id) {

			$this->delete_attachments(intval($mail_id));

			$q = sprintf('	DELETE FROM 	'.TABLE_PR_MAILS.'
							WHERE			(id = %s) AND
											(owner_id = %s)',
							$dbc->_db->quote($mail_id),
							$dbc->_db->quote($this->user_id)
						);

			$dbc->_db->query($q);
		}

		return mysql_affected_rows($dbc->_db->get_link());
	}

	/**
	 * Get message identified by ID
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $id
	 * @return array
	 */
	function get($message_id)
	{
		if(!is_int($message_id)) {
			trigger_error('get() expects parameter 1 to be integer, '.gettype($message_id).' given', E_USER_NOTICE);
		}

		global $dbc;

		$q = sprintf('	SELECT 		*
						FROM 		'.TABLE_PR_MAILS.'
						WHERE 		(id = %s) AND
									(owner_id = %s)
						LIMIT		1',
						$dbc->_db->quote($message_id),
						$dbc->_db->quote($this->user_id)
					);

		$r = $dbc->_db->query($q);
		return $dbc->_db->fetch_assoc($r);
	}

	/**
	 * Get attachments
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $id
	 * @return array
	 */
	function get_attachments($message_id)
	{
		if(!is_int($message_id)) {
			trigger_error('get() expects parameter 1 to be integer, '.gettype($message_id).' given', E_USER_NOTICE);
		}

		global $dbc;

		$q = sprintf('	SELECT 		attachment
						FROM 		'.TABLE_PR_MAILS.'
						WHERE 		(id = %s) AND
									(owner_id = %s)
						LIMIT		1',
						$dbc->_db->quote($message_id),
						$dbc->_db->quote($this->user_id)
					);

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		if($a['attachment']) {
			return explode(',', $a['attachment']);
		}

		return false;
	}

	/**
	 * Delete attachments
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $id
	 * @return bool
	 */
	function delete_attachments($message_id)
	{
		if(!is_int($message_id)) {
			trigger_error('get() expects parameter 1 to be integer, '.gettype($message_id).' given', E_USER_NOTICE);
		}

		$attachments = $this->get_attachments($message_id);

		if($attachments) {
			foreach ($attachments as $attachment) {

				list($attch_filename, $attch_name) = explode('|', $attachment);

				if($this->count_attachment_mails($attch_filename) < 2) {
					if(is_file(DOC_ROOT . '/site/mercury/' . $attch_filename)) {
						unlink(DOC_ROOT . '/site/mercury/' . $attch_filename);
					}
				}
			}
		}
		else {
			return false;
		}

		return true;
	}

	/**
	 * Helper function for set_read / set_unread
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $message_id
	 * @param int $status
	 */
	function _set_read_helper($message_id, $status)
	{
		global $dbc;

		$q = sprintf('	UPDATE 		'.TABLE_PR_MAILS.'
						SET 		mail_read = %s
						WHERE		(id = %s) AND
									(owner_id = %s)',
						$dbc->_db->quote($status),
						$dbc->_db->quote($message_id),
						$dbc->_db->quote($this->user_id)
					);

		$dbc->_db->query($q);
	}

	/**
	 * Sets message status to read
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $message_id
	 */
	function set_read($message_id)
	{
		$this->_set_read_helper($message_id, 1);
	}

	/**
	 * Sets message status to unread
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $message_id
	 */
	function set_unread($message_id)
	{
		$this->_set_read_helper($message_id, 0);
	}

	function format_mail_to($to)
	{
		global $orbicon_x, $orbx_log;
		$contacts = explode(',', $to);

		if(!is_array($contacts) || ($contacts[0] == '')) {
			$orbx_log->ewrite('No contacts found for private mail', __LINE__, __FUNCTION__);
			return false;
		}

		$contacts = array_map('trim', $contacts);
		$contacts = array_unique($contacts);
		$contacts = array_remove_empty($contacts);

		foreach ($contacts as $contact) {
			$txt[] = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$contact.'">' . $contact . '</a>';
		}

		$txt = implode(', ', $txt);
		return $txt;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return array
	 */
	function upload_attachments()
	{
		$max = count($_FILES['atch']['name']);

		require_once DOC_ROOT . '/orbicon/mercury/class.mercury.php';
		$mercury = new Mercury();

		for($i = 0; $i < $max ; $i++) {
			// security check
			if(validate_upload($_FILES['atch']['tmp_name'][$i], $_FILES['atch']['name'][$i], $_FILES['atch']['size'][$i], $_FILES['atch']['error'][$i])) {
				$attch = $mercury->insert_file_into_db($_FILES['atch']['name'][$i], true, $_FILES['atch']['tmp_name'][$i], false, "pring_u_mailattch");

				$files[] = $attch . '|' . $_FILES['atch']['name'][$i];
			}
		}

		$mercury = null;
		return $files;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $mail_id
	 * @return unknown
	 */
	function format_attch($mail_id)
	{
		$attachments = $this->get_attachments($mail_id);

		$format = array();

		require_once DOC_ROOT . '/orbicon/mercury/class.mercury.php';
		$mercury = new Mercury();

		if($attachments) {
			foreach ($attachments as $attachment) {

				list($attch_filename, $attch_name) = explode('|', $attachment);

				$icon = $mercury->get_document_icon(get_extension($attch_filename));

				$format[] = '<a href="'.ORBX_SITE_URL.'/site/mercury/'.$attch_filename.'">'.$icon.' '.$attch_name.'</a>';
			}
		}

		$mercury = null;

		return implode(', ', $format);
	}

	/**
	 * How many mails are refering to attachment
	 *
	 * @param string $attachment
	 * @return int
	 */
	function count_attachment_mails($attachment)
	{
		global $dbc;

		$q = sprintf('	SELECT 		COUNT(id) AS total
						FROM 		'.TABLE_PR_MAILS.'
						WHERE 		(attachment LIKE %s)',
						$dbc->_db->quote("%$attachment%")
					);

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		return intval($a['total']);
	}

	function is_spam($subject, $msg)
	{
		if(strpos($subject, '@') !== false) {
			return true;
		}

		$i = 0;
		$msg = strtolower($msg);
		$badwords = array('hello', 'profile', 'mail', 'address', 'picture');

		foreach ($badwords as $badword) {
			if(strpos($msg, $badword) !== false) {
				$i ++;
			}
		}

		if($i >= 2) {
			return true;
		}

		return false;
	}
}

	/**
	 * Create RSS for mailbox
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $category
	 * @return string
	 */
	function print_mbox_rss($hash, $mailbox = PR_MAILBOX_INBOX)
	{
		global $dbc, $orbicon_x;

		$rss = '<?xml version="1.0" encoding="UTF-8"?>
	<?xml-stylesheet href="http://www.w3.org/2000/08/w3c-synd/style.css" type="text/css"?>
	<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<atom:link href="'.ORBX_SITE_URL.'/orbicon/modules/peoplering/rss.mbox.php?mbox='.urlencode($hash).'" rel="self" type="application/rss+xml" />
		<title>'._L('pr-inbox').'@'.DOMAIN_NAME.'</title>
		<link>'.ORBX_SITE_URL.'/</link>
		<description>'.DOMAIN_DESC.'</description>
		<lastBuildDate>'.date('r').'</lastBuildDate>
		<generator>'.ORBX_FULL_NAME.'</generator>
		<language>'.$orbicon_x->ptr.'</language>
		<copyright>Copyright '.date('Y').', '.DOMAIN_OWNER.'</copyright>
		<managingEditor>'.DOMAIN_EMAIL.' ('.DOMAIN_NAME.')</managingEditor>
		<webMaster>'.DOMAIN_EMAIL.' ('.DOMAIN_NAME.')</webMaster>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>' . "\n";

		$q = sprintf('	SELECT 		*
						FROM 		'.TABLE_PR_MAILS.'
						WHERE 		(mailbox = %s) AND
									(SHA1(MD5(POW(owner_id, 5) * 999123123.999)) = %s)
						ORDER BY	mail_date DESC
						LIMIT		10',
						$dbc->_db->quote($mailbox),
						$dbc->_db->quote($hash)
					);

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.peoplering.php';

		$pr = new Peoplering();
		$desc = '';

		while($a) {
			$desc = strip_tags($a['body']);
			$desc = truncate_text($desc, 30, '...');
			$desc = trim(str_replace(array("\n", '<', '&nbsp;', '\''), array('', '&lt;', ' ', '&#039;'), $desc));

			$name = $pr->get_username($a['mail_from']);
			$name = $name['username'];

			$url = ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=read&amp;mail='.$a['id'];

			$rss .= '<item>
		<title>'.utf8_html_entities($a['subject']).'</title>
		<link>'.$url.'</link>
		<description>'.$desc.'</description>
		<pubDate>'.date('r', $a['mail_date']).'</pubDate>
		<guid isPermaLink="true">'.$url.'</guid>
		<author>'.$name.'</author>
		<source url="'.ORBX_SITE_URL.'/orbicon/modules/peoplering/rss.mbox.php/?mbox='.$hash.'">'._L('pr-inbox').'@'.DOMAIN_NAME.'</source>
	</item>'."\n";
			$a = $dbc->_db->fetch_assoc($r);
		}

		$rss .= ' </channel>
	</rss>';

		return $rss;
	}

?>