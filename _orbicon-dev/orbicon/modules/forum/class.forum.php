<?php
/**
 * Forum class
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemMOD
 * @subpackage Forum
 * @uses Peoplering
 * @version 1.00
 * @link http://
 * @license http://
 * @since 2006-07-01
 */

define('TABLE_FORUM', 'orbx_mod_forum');

require_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.peoplering.php';

class Forum
{
	var $ptitle;
	var $react;              // number of answers in a thread
	var $pansw;
	var $title;
	var $_pr;
	var $text;
	var $forum_grp;

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $forum_grp
	 * @return Forum
	 */
	function Forum($forum_grp = 'default')
	{
		$this->__construct($forum_grp);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $forum_grp
	 */
	function __construct($forum_grp = 'default')
	{
		$this->forum_grp = (!$forum_grp) ? 'default' : $forum_grp;
		$this->ptitle = null;
		$this->pansw = 0;
		$this->_pr = new Peoplering();
	}

	/**
	 * displays the form
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $ptitle
	 * @return unknown
	 */
	function print_forum_form($ptitle = null)
	{
		global $orbx_mod, $orbicon_x;
		if(!get_is_member() && $orbx_mod->validate_module('inpulls')) {
			return 'Morate biti prijavljeni ako želite pisati po forumu. <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.reg">Prijavite se</a>';
		}

		$this->ptitle = ($ptitle != null) ? "Re: $ptitle" : $ptitle;

		$form = '
<style type="text/css"><!--

#form_forum input[type="text"],
#form_contact .input-text {
	width:99%;
}
#form_forum img {
	vertical-align:bottom;
}
#rte_lite_content {
	height: 250px !important;
}
--></style>
<script type="text/javascript"><!-- // --><![CDATA[

	YAHOO.util.Event.addListener(window, "load", rte_lite_load);
	YAHOO.util.Event.addListener(window, "load", __rte_lite_attach);

	function __rte_lite_attach() {
		YAHOO.util.Event.addListener($(\'form_forum\'), "submit", function () {$(\'content\').value = rte_lite.body.innerHTML;});
	}

// ]]></script>
<form id="form_forum" action="" method="post">
<script type="text/javascript"><!-- // --><![CDATA[
	document.write(\'<input type="hidden" id="content" name="content" />\');
// ]]></script>
			<table>
				<tr>
					<td width="33%"><label for="title">'._L('forum_title').'</label></td>
					<td><input type="text" id="title" name="title" value="'.$this->ptitle.'" /></td>
				</tr>
		<tr>
			<td style="vertical-align:top;">
				<label for="rte_lite_content">'._L('message').'</label>
				<br /><br />
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/smile.png" alt=":)" title=":)" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/dunno.png" alt=":/" title=":/" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/wink.png" alt=";)" title=";)" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/veryhappy.png" alt=":D" title=":D" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/sad.png" alt=":(" title=":(" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/serious.png" alt=":|" title=":|" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/tongue.png" alt=":P" title=":P" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/yelling.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/zipped.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/angel.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/badhairday.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/cool.png" alt="8)" title="8)" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/crying.png" alt=":\')" title=":\')" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/embarrassed.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/evil.png" alt=">:)" title=">:)" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/huh.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/lmao.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/nerd.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/oooh.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/retard.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/sarcastic.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/sleepy.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/teeth.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/beer.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/gift.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/love.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/cd.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/note.png" /></a>

			</td>
			<td>
			<a href="javascript:void(null);" onclick="javascript:rte_lite_bold();"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_bold.png" alt="text_bold.png" title="Bold (CTRL + B)" /></a>
			<a href="javascript:void(null);" onclick="javascript:rte_lite_italic();"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_italic.png" alt="text_italic.png" title="Italic (CTRL + I)" /></a>
			<a href="javascript:void(null);" onclick="javascript:rte_lite_underline();"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_underline.png" alt="text_underline.png" title="Underline (CTRL + U)" /></a>
			<a href="javascript:void(null);" onclick="javascript:rte_lite_strikethrough();"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_strikethrough.png" alt="text_strikethrough.png" title="Strikethrough" /></a>
			<a href="javascript:void(null);" onclick="javascript:rte_lite_link();"><img src="'.ORBX_SITE_URL.'/orbicon/rte/rte_buttons/link.gif" alt="link.gif" title="Link (CTRL + K)" /></a>
			<br />

<script type="text/javascript"><!-- // --><![CDATA[
	document.write(\'<iframe id="rte_lite_content" class="input-text"></iframe>\');
// ]]></script>
			<noscript>
				<div style="width: 99%;"><textarea name="content" style="width: 100%; height: 250px;"></textarea></div>
			</noscript>
			</td>
		</tr>
				<tr>
					<td colspan="2"><input id="forum_submit" type="submit" name="submit" value="'._L('submit').'"></td>
				</tr>
			</table>
			<input type="hidden" name="thread_id" value="'.$this->pansw.'">
			</form>';
		return $form;
	}

    // Add_new_post: Adds new record to DB
    /**
     * Enter description here...
     *
     * @author Pavle Gardijan <pavle.gardijan@gmail.com>
     * @param string $title
     * @param string $content
     * @param string $name
     * @param string $thread_id
     * @return bool
     */
    function add_new_post($title, $content, $name, $thread_id)
	{
		global $dbc, $orbx_mod;

        if(($title == '') || ($content == '')) {
        	trigger_error('add_new_post() expects parameters 1 and 2 to be non-empty', E_USER_WARNING);
            return false;
	    }

        $this->ptitle = trim(htmlspecialchars($title));
		$permalink = get_permalink($this->ptitle);
        $this->text = trim(strip_tags($content, '<p><b><span><strong><i><u><em><br><img><a><u><h1><h2><h3><h4><h5><h6><abbr><acronym><address><blockquote><hr><big><font><center><ul><ol><li><small><q><strike><sub><sup><table><tr><td><th><thead>'));

        // passthru safehtml
        if(!defined('XML_HTMLSAX3')) {
			define('XML_HTMLSAX3', DOC_ROOT . '/orbicon/3rdParty/safehtml/classes/');
		}
		require_once XML_HTMLSAX3 . 'safehtml.php';
		$safehtml = new SafeHTML();
		$this->text = $safehtml->parse($this->text);
		$safehtml = null;

		require_once DOC_ROOT . '/orbicon/magister/class.magister.php';
		$magister = new Magister();

		$this->ptitle = utf8_html_entities($this->ptitle);
		$this->text = utf8_html_entities(trim(stripslashes($this->text)));
		$this->text = $magister->close_tags($this->text);
		$this->text = $magister->hyperlinks_add($this->text);
		$magister = null;

        if($thread_id == 0) {

        	if($this->forum_grp != 'default' && $orbx_mod->validate_module('inpulls.groups')) {
				require_once DOC_ROOT . '/orbicon/modules/inpulls.groups/inc.groups.php';
	        	modify_grp_activity(3, get_grp_id_from_permalink($this->forum_grp));
        	}

			$q = sprintf('	INSERT INTO 	'.TABLE_FORUM.'
											(thread_id, title,
											permalink, content,
											time, thread_time,
											ip, user,
											forum_group)
							VALUES 			(%s, %s,
											%s, %s,
											UNIX_TIMESTAMP(), UNIX_TIMESTAMP(),
											%s, %s,
											%s)',
							$dbc->_db->quote($thread_id), $dbc->_db->quote($this->ptitle),
							$dbc->_db->quote($permalink), $dbc->_db->quote($this->text),
						 	$dbc->_db->quote(ORBX_CLIENT_IP), $dbc->_db->quote($_SESSION['user.r']['id']),
						 	$dbc->_db->quote($this->forum_grp));
        }
		else {

			if($this->forum_grp != 'default' && $orbx_mod->validate_module('inpulls.groups')) {
				require_once DOC_ROOT . '/orbicon/modules/inpulls.groups/inc.groups.php';
				modify_grp_activity(1, get_grp_id_from_permalink($this->forum_grp));
			}

			$q = sprintf('	INSERT INTO 		'.TABLE_FORUM.'
												(thread_id, title,
												content, time,
												ip, user,
												forum_group)
							VALUES 				(%s, %s,
												%s, UNIX_TIMESTAMP(),
												%s, %s,
												%s)',
					$dbc->_db->quote($thread_id), $dbc->_db->quote($this->ptitle),
					$dbc->_db->quote($this->text), $dbc->_db->quote(ORBX_CLIENT_IP),
					$dbc->_db->quote($_SESSION['user.r']['id']), $dbc->_db->quote($this->forum_grp));
        }
		$dbc->_db->query($q);

		$id = $dbc->_db->insert_id();

		if($thread_id == 0) {
            $q = sprintf('	UPDATE 		'.TABLE_FORUM.'
            				SET 		thread_id=%s
            				WHERE 		(id=%s)
            				LIMIT 		1', $dbc->_db->quote($id), $dbc->_db->quote($id));
        }
		else  {
            $q = sprintf('	UPDATE 		'.TABLE_FORUM.'
            				SET 		thread_time=UNIX_TIMESTAMP()
            				WHERE 		(id=%s)
            				LIMIT 		1', $dbc->_db->quote($thread_id));
        }

		$dbc->_db->query($q);
    }

    /**
     * Displays the main message of threads
     *
     * @author Pavle Gardijan <pavle.gardijan@gmail.com>
     * @return string
     */
    function print_forum_index()
	{
		global $dbc, $orbicon_x;

		if(!isset($_GET['p'])) {
			$unset_below = true;
		}

		$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 10;
		$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

		require_once DOC_ROOT . '/orbicon/class/class.pagination.php';
		$pagination = new Pagination('p', 'pp');

		$read = $dbc->_db->query(sprintf('	SELECT 		COUNT(id) AS numrows
											FROM 		'.TABLE_FORUM.'
											WHERE 		(thread_id=id) AND
														(forum_group = %s)',
											$dbc->_db->quote($this->forum_grp)));
		$row = $dbc->_db->fetch_assoc($read);

		$pagination->total = $row['numrows'];
		$pagination->split_pages();

        $q = sprintf('	SELECT 		*
		        		FROM 		'.TABLE_FORUM.'
		        		WHERE 		(thread_id = id) AND
		        					(forum_group = %s)
		        		ORDER BY 	thread_time DESC
		        		LIMIT		%s, %s', $dbc->_db->quote($this->forum_grp),
		        		$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']), $dbc->_db->quote($_GET['pp']));

        $r = $dbc->_db->query($q);
        $affected = $dbc->_db->affected_rows();

        if($affected > 0) {
            $forum = '<table class="forum_index" style="width:100%">
            <tr class="forum_header">
            	<td>'._L('forum_title').'</td>
            	<td>'._L('forum_replies').'</td>
            	<td>'._L('forum_author').'</td>
            	<td>'._L('forum_last_msg').'</td>
            </tr>';

			$a = $dbc->_db->fetch_assoc($r);

			$i = 0;

            while($a) {

            	$class = (($i % 2) == 0) ? '' : 'class="odd"';

            	$q_ = sprintf('	SELECT 		(COUNT(thread_id)-1) AS num
            					FROM 		'.TABLE_FORUM.'
            					WHERE 		(thread_id=%s) AND
            								(forum_group = %s)
            					GROUP BY 	thread_id', $dbc->_db->quote($a['id']), $dbc->_db->quote($this->forum_grp));
                $r_ = $dbc->_db->query($q_);
                $a_ = $dbc->_db->fetch_assoc($r_);

                //number of reactions
                $this->react = intval($a_['num']);

                if($_COOKIE['forum_topic_' . $a['id']] == ($this->react + 1)) {
                	$style = ' style="font-weight:normal !important;"';
                }
                else {
                	$style = '';
                }

                if(!$a['user']) {
                    $display_name = _L('forum_guest');
                }
                else {

                	$user = $this->_pr->get_profile($this->_pr->get_prid_from_rid($a['user']));
    	       		$username = $this->_pr->get_username($a['user']);
	           		$username = $username['username'];

	           		$display_username = (empty($user['contact_name'])) ? $username : $user['contact_name'].' '.$user['contact_surname'];

                	$display_name = '<a href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$username, ORBX_SITE_URL . '/~' . $username).'">'.$display_username.'</a>';
                }

                $this->ptitle = stripslashes($a['title']);
				$url = url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.forum/'.urlencode($a['permalink'].'.'.$a['id']) . '&amp;forum=' . $this->forum_grp, ORBX_SITE_URL . '/forum/' . $a['permalink'] . '.' . $a['id']);

                $forum .= '<tr '.$class.'>
                <td><a '.$style.' href="'.$url.'">'.$this->ptitle.'</a></td>
                <td>'.$this->react.'</td>
                <td>'.$display_name.'</td>
                <td>'.date($_SESSION['site_settings']['date_format'] . ' H:i', $a['thread_time']).'</td>
                </tr>';
				$a = $dbc->_db->fetch_assoc($r);
				$i ++;
            }
            unset($this->react);
            $forum .= '</table>';

            $forum .= $pagination->construct_page_nav(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr] . '&forum=' . $this->forum_grp);

            // this invalidates caching, clean up from memory
			if($unset_below) {
				unset($_GET['p'], $_GET['pp']);
			}
        }
		else {
            $forum .= _L('forum_no_threads');
        }
        $this->ptitle = ''; //the new thread's title is empty

		return $forum;
    }

    /**
     * Displays all messages of a thread
     *
     * @author Pavle Gardijan <pavle.gardijan@gmail.com>
     * @param unknown_type $thread_permalink
     * @param int $thread_id
     * @return unknown
     */
    function print_forum_threads($thread_permalink, $thread_id)
	{
		$thread_id = intval($thread_id);
		global $dbc, $orbicon_x;

		if(!isset($_GET['p'])) {
			$unset_below = true;
		}

		$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 10;
		$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

		require_once DOC_ROOT . '/orbicon/class/class.pagination.php';
		$pagination = new Pagination('p', 'pp');

		$read = $dbc->_db->query(sprintf('	SELECT 		COUNT(id) AS numrows
											FROM 		'.TABLE_FORUM.'
											WHERE 		(thread_id=%s) AND
														(forum_group = %s)',
											$dbc->_db->quote($thread_id), $dbc->_db->quote($this->forum_grp)));
		$row = $dbc->_db->fetch_assoc($read);

		$pagination->total = $row['numrows'];
		$pagination->split_pages();

		if(!setcookie('forum_topic_' . $thread_id, $row['numrows'], (time() + 99999999), '/', '.' . DOMAIN_NO_WWW)) {
			trigger_error('Could not set cookie "' . $thread_id . '"', E_USER_NOTICE);
		}

        $q = sprintf('	SELECT 		*
						FROM 		'.TABLE_FORUM.'
						WHERE 		(thread_id=%s) AND
									(forum_group = %s)
						ORDER BY 	time ASC
						LIMIT 		%s, %s', $dbc->_db->quote($thread_id),  $dbc->_db->quote($this->forum_grp),
						$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']), $dbc->_db->quote($_GET['pp']));

        $r = $dbc->_db->query($q);
        $affected = $dbc->_db->affected_rows();

        if($affected > 0) {
            $forum = '<table id="forum_messages" style="width:100%">';
			$a = $dbc->_db->fetch_assoc($r);

			$orbicon_x->set_page_title(_L('forum') . ' &raquo; ' . $a['title']);

            while($a) {
                $this->ptitle = stripslashes($a['title']);
                $this->text = nl2br(stripslashes($a['content']));
           		$user = $this->_pr->get_profile($this->_pr->get_prid_from_rid($a['user']));
           		$username = $this->_pr->get_username($a['user']);
           		$username = $username['username'];

           		$picture = $user['picture'];

           		if(is_file(DOC_ROOT . '/site/venus/thumbs/t-' . $picture)) {
					$picture = ORBX_SITE_URL.'/site/venus/thumbs/t-' . $picture;
				}
				elseif(is_file(DOC_ROOT . '/site/venus/' . $picture)) {
					$picture = ORBX_SITE_URL.'/site/venus/' . $picture;
				}
				else {
					$picture = ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/unknownUser.gif';
				}

                if(!$a['user']) {
                    $display_name = _L('forum_guest') . '<br /><img class="forum_avatar" src="' . $picture . '" alt="'.$username.'" title="'.$username.'" />';
                }
                else {


                	$user = $this->_pr->get_profile($this->_pr->get_prid_from_rid($a['user']));
    	       		$username = $this->_pr->get_username($a['user']);
	           		$username = $username['username'];

	           		$display_username = (empty($user['contact_name'])) ? $username : $user['contact_name'].' '.$user['contact_surname'];

                	$display_name = '<a href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$username, ORBX_SITE_URL . '/~' . $username).'">'.$display_username.'<br /><img class="forum_avatar" src="' . $picture . '" alt="'.$username.'" title="'.$username.'" /></a>';
                }

                $forum .= '
                	<tr class="message_title">
                		<td colspan="2"><a name="post'.$a['id'].'"></a><strong>'.$this->ptitle.'</strong></td>
                	</tr>
                	<tr class="forum_user">
	                	<td class="forum_name">'.$display_name.'</td>
    	            	<td class="forum_txt">'.$this->text.'</td>
    	            </tr>
    	            <tr class="forum_date">
	                	<td colspan="2">'._L('forum_msg_num').': '.$this->get_total_user_msgs($a['user']).'<br />'.date($_SESSION['site_settings']['date_format'] . ' H:i', $a['time']).'</td>
	                </tr>
	                <tr class="forum_msg_separator"><td colspan="2"><hr /></td></tr>';
				$a = $dbc->_db->fetch_assoc($r);
            }

            $forum .= '</table>';
        }
		else {
            $forum .= _L('forum_no_msgs');
        }

        $q = sprintf('	SELECT 	title
						FROM 	'.TABLE_FORUM.'
						WHERE 	(id=%s)
						LIMIT 	0, 1', $dbc->_db->quote($thread_id));
        $r = $dbc->_db->query($q);
        $a = $dbc->_db->fetch_assoc($r);
        $this->ptitle = stripslashes($a['title']);  //Re title for form

        $forum .= $pagination->construct_page_nav(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr] . '&forum=' . $this->forum_grp);

		// this invalidates caching, clean up from memory
		if($unset_below) {
			unset($_GET['p'], $_GET['pp']);
		}

		return $forum;
    }

    /**
     * Enter description here...
     *
     * @author Pavle Gardijan <pavle.gardijan@gmail.com>
     * @return unknown
     */
	function print_forum()
	{
		if(isset($_POST['submit'])) {
			$this->add_new_post($_POST['title'], $_POST['content'], $_POST['name'], $_POST['thread_id']);
		}

		global $orbicon_x, $orbx_mod;

		if($this->forum_grp != 'default' && $orbx_mod->validate_module('inpulls.groups')) {
			require_once DOC_ROOT . '/orbicon/modules/inpulls.groups/inc.groups.php';
			if(!get_grp_is_member($_SESSION['user.r']['id'], get_grp_id_from_permalink($this->forum_grp))) {
				return 'Nisi član ove grupe. <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.$this->forum_grp.'">Učlani se</a>';
			}

			$grp_id = get_grp_id_from_permalink($this->forum_grp);

			$member_status = (get_grp_member_status($_SESSION['user.r']['id'], $grp_id));

			if(!get_grp_is_owner($_SESSION['user.r']['id'], $grp_id)) {
				if($member_status == INPULLS_GRP_MEMBER_DISABLED) {
					return 'Odlukom vlasnika grupe oduzeto ti je članstvo';
				}
				elseif ($member_status == INPULLS_GRP_MEMBER_WAITING) {
					return 'Vlasnik grupe još nije obobrio tvoje članstvo. Pokušaj ponovno kasnije';
				}
			}
		}

		$thread = explode('/', $_GET[$orbicon_x->ptr]);
		$thread = explode('.', $thread[1]);

		if($thread[0] != '') {
			$forum = $this->print_forum_threads($thread[0], $thread[1]);
			$this->pansw = $thread[1];
			$forum .= '<h2 class="new_topic">Napiši odgovor</h2>';
		}
		else {
			$forum = $this->print_forum_index();
			$this->pansw = 0;
			$forum .= '<h2 class="new_topic">Pokreni novu temu</h2>';
		}

		$forum .= $this->print_forum_form($this->ptitle);
		return $forum;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $user_id
	 * @return unknown
	 */
	function get_total_user_msgs($user_id)
	{
		global $dbc;

		$r = $dbc->_db->query(sprintf('		SELECT 		COUNT(id) AS total
											FROM 	' . TABLE_FORUM . '
											WHERE 		(user = %s) AND
														(forum_group = %s)', $dbc->_db->quote($user_id), $dbc->_db->quote($this->forum_grp)));
		$a = $dbc->_db->fetch_assoc($r);
		return $a['total'];
	}

	function get_lastest_forum_summary()
	{
		global $dbc,$orbicon_x;

		$list = '';
        $q = sprintf('	SELECT 		id, title, permalink, thread_time, thread_id, user
		        		FROM 		'.TABLE_FORUM.'
		        		WHERE 		(forum_group = %s)
		        		ORDER BY 	time DESC
		        		LIMIT		5', $dbc->_db->quote($this->forum_grp));

        $r = $dbc->_db->query($q);
        $a = $dbc->_db->fetch_assoc($r);

        while ($a) {

        	if($a['permalink'] == '') {
        		$a['permalink'] = $this->get_permalink_from_id($a['thread_id']);
        	}

			if(!$a['user']) {
                $display_name = _L('forum_guest');
            }
            else {

            	$user = $this->_pr->get_profile($this->_pr->get_prid_from_rid($a['user']));
	       		$username = $this->_pr->get_username($a['user']);
           		$username = $username['username'];

           		$display_username = (empty($user['contact_name'])) ? $username : $user['contact_name'].' '.$user['contact_surname'];

            	$display_name = '<a href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$username, ORBX_SITE_URL . '/~' . $username).'">'.$display_username.'</a>';
            }

            $read = $dbc->_db->query(sprintf('	SELECT 		COUNT(id) AS numrows
												FROM 		'.TABLE_FORUM.'
												WHERE 		(thread_id=%s) AND
															(forum_group = %s)',
												$dbc->_db->quote($a['thread_id']), $dbc->_db->quote($this->forum_grp)));
			$row = $dbc->_db->fetch_assoc($read);

			$p = 0;
			while($row['numrows'] > 0) {
				$row['numrows'] -= 10;
				$p ++;
			}

            $this->ptitle = stripslashes($a['title']);
			$url = ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.forum/'.urlencode($a['permalink'].'.'.$a['thread_id']) . '&amp;forum=' . $this->forum_grp . '&amp;p='.$p.'&amp;pp=10#post' . $a['id'];

            $list .= '<li><a href="'.$url.'">'.$this->ptitle.'</a> by '.$display_name.'</li>';

	        $a = $dbc->_db->fetch_assoc($r);
        }

        return "<div id=\"forum_summary\"><h3>Zadnje sa foruma</h3><ol>$list</ol></div>";
	}


	function get_permalink_from_id($id)
	{
		global $dbc;

		$list = '';
        $q = sprintf('	SELECT 		permalink
		        		FROM 		'.TABLE_FORUM.'
		        		WHERE 		(forum_group = %s) AND
		        					(id = %s)
		        		LIMIT		1', $dbc->_db->quote($this->forum_grp), $dbc->_db->quote($id));

        $r = $dbc->_db->query($q);
        $a = $dbc->_db->fetch_assoc($r);

        return $a['permalink'];
	}
}

?>