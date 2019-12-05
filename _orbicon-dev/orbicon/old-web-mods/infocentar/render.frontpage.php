<?php

if(!isset($_GET['p'])) {
	$unset_below = true;
}

// TODO : check this with Pavle
// get user's data
$visitor = $_SESSION['user.a'];
$visitor['user_type'] = 'a';

// not admin
if(!get_is_admin()) {
	$visitor = $_SESSION['user.r'];
	$visitor['first_name'] = $_SESSION['user.r']['contact_name'];
	$visitor['last_name'] = $_SESSION['user.r']['contact_surname'];
	$visitor['email'] = $_SESSION['user.r']['contact_email'];
	$visitor['user_type'] = 'r';

}

// * requirements
$url = ORBX_SITE_URL;
$lang = $orbicon_x->ptr;
$orbx_build = ORBX_BUILD;
global $dbc;

// if user change number of displayed questions, remember that
if(isset($_POST['ic_show_per_page'])){
	setcookie('ic_show_per_page', $_POST['ic_show_per_page'], time() - 3600);
	setcookie('ic_show_per_page', $_POST['ic_show_per_page'], 0);
}

// * requirements
include_once DOC_ROOT . '/orbicon/modules/infocentar/class/icsettings.class.php';

// * load infocentar settings
$icsettings = new ICSettings;
$icsetting = $icsettings->_get_settings();

$rows_per_page = isset($_POST['ic_show_per_page']) ? intval($_POST['ic_show_per_page']) : $_COOKIE['ic_show_per_page'];
$rows_per_page = (empty($rows_per_page)) ? $icsetting['public_per_page'] : $rows_per_page;
$rows_per_page = ($rows_per_page < 1) ? 20 : $rows_per_page;
$rows_per_page = ($rows_per_page > 50) ? 50 : $rows_per_page;

// * import class files
require_once DOC_ROOT.'/orbicon/modules/infocentar/class/question.class.php';
require_once DOC_ROOT.'/orbicon/modules/infocentar/class/category.class.php';
require_once DOC_ROOT.'/orbicon/modules/infocentar/class/tag.class.php';


if($icsetting['send_friend'] == 1){
	$sedntofriend = include_once DOC_ROOT.'/orbicon/modules/send2friend/render.send2friend.php';
} else {
	$sedntofriend = '';
}

// include rating class
require_once DOC_ROOT.'/orbicon/modules/infocentar/class/rating.class.php';

$menu = '
	<ul id="front_page_menu">
		<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.infocentar">'._L('home').'</a></li>
		<li><a href="javascript: void(null);" id="search_view">'._L('ic-search-questions').'</a></li>
		<li><a href="javascript: void(null);" id="ask_view">'._L('ic-ask').'</a></li>
	</ul>';

// * get info on active questions & answers
$qs = new Question();

// * initialize object for categories
$cl = new Category();

require_once DOC_ROOT.'/orbicon/modules/infocentar/front.summary.php';

// save answer if we have one
if(($icsetting['answer_privileges'] == 'ar') || ($icsetting['answer_privileges'] == 'arp')) {
	if(isset($_POST['post_answer'])) {
		/**
		 * @todo this sucks but until we improve the Question object...
		 */
		$_POST['content'] = $_POST['answer'];

		if(strip_tags($_POST['content']) != '') {
			$qnew = new Question($_POST);
			$qnew->set_new_answer($_POST['qid']);
			unset($qnew);
		}
	}
}

switch(strtolower($_GET['sp'])){
	case 'search':	include DOC_ROOT.'/orbicon/modules/infocentar/front.search.php';
					break;
	case 'tag':		include DOC_ROOT.'/orbicon/modules/infocentar/front.tag.php';
					break;
	case 'q':		include DOC_ROOT.'/orbicon/modules/infocentar/front.question.php';
					break;
	default:		include DOC_ROOT.'/orbicon/modules/infocentar/front.home.php';
					break;
}

// append lead text above IC
if($icsetting['intro'] == 1) {
	$lead_txt = '';
	$r_ = $dbc->_db->query(sprintf('SELECT 		content
									FROM 		'.MAGISTER_CONTENTS.'
									WHERE 		(live = 1) AND
												(hidden = 0) AND
												(question_permalink = %s) AND
												(language = %s)
									ORDER BY 	uploader_time', $dbc->_db->quote($icsetting['intro_text']), $dbc->_db->quote($orbicon_x->ptr)));
	$a_ = $dbc->_db->fetch_assoc($r_);

	while($a_) {
		$lead_txt .= $a_['content'];
		$a_ = $dbc->_db->fetch_assoc($r_);
	}
	$dbc->_db->free_result($r_);

	$intro_text = '<div id="intro_text">' . $lead_txt . '</div>';
}
else {
	$intro_text = '';
}

// build tag cloud
if($icsetting['tag_cloud'] == 1) {
	$tagCloudObj = new Tag;
	$tag_cloud = $tagCloudObj->build_tag_cloud();
	$tag_cloud = '<div id="tag_cloud">' . $tag_cloud . '</div>';
}
else {
	$tag_cloud = '';
}



$cats = '<ul id="front_page_cats">';

		// * get full list of categories, that are active
		$categories = $cl->get_all_categories(1);
		$cat_item = $dbc->_db->fetch_assoc($categories);

		while($cat_item){

			// * retrieve number of questions inside current category
			$quest_num = $cl->get_items_num($cat_item['id'], '1');

			// * active category
			$active_cat = ($_GET['category'] == $cat_item['id']) ? ' class="active_cat_item"' : '';

			$cats .= '
			<li>
				<a title="'.$cat_item['description'].'" href="'.ORBX_SITE_URL.'/?'.$lang.'=mod.infocentar&amp;category='.$cat_item['id'].'"'.$active_cat.'>'.$cat_item['title'].' ('.$quest_num['total'].')</a>
			</li>';

			$cat_item = $dbc->_db->fetch_assoc($categories);
		}

$cats .= '</ul>';

if($icsetting['append_polls'] == 1) {
	$cats .= include_once DOC_ROOT . '/orbicon/modules/polls/render.polls.php';
}

$cat_title = '<h1 id="cat_title">' . _L('ic-category-title') . '</h1>';
$cat_title = ($icsetting['apply_title_info'] == 0 || $icsetting['apply_title_info'] == 3) ? $cat_title : '';

$quest_title = '<h1 id="quest_title">'. _L('ic-quest-title') . '</h1>';
$quest_title = ($icsetting['apply_title_info'] == 0 || $icsetting['apply_title_info'] == 2) ? $quest_title : '';

// * include search form
$searching_form = require_once DOC_ROOT.'/orbicon/modules/infocentar/form/form.search.php';
$ask_form = require_once DOC_ROOT.'/orbicon/modules/infocentar/form/form.ask.php';

$hide_search = (isset($_POST['submit_new_question'])) ? ' class="hidenitem"' : '';
$hide_ask = (isset($_POST['submit_new_question'])) ? '' : ' class="hidenitem"';

// unset some vars
unset($_POST['submit_new_question']);

// this screws up caching, clean up from memory
if($unset_below) {
	unset($_GET['p'], $_GET['pp']);
}

// this is for confirmation
$confirm_domain_sig = DOMAIN_NAME;
$confirm_msg_sig = _L('ic-msg-thanx');

return <<<INFOCENTAR_FRONT
<script type="text/javascript" src="{$url}/orbicon/modules/infocentar/library/functions.js"></script>
<script type="text/javascript" src="{$url}/orbicon/3rdParty/yui/build/utilities/utilities.js"></script>
<script type="text/javascript" src="{$url}/orbicon/3rdParty/yui/build/button/button-beta.js"></script>
<script type="text/javascript" src="{$url}/orbicon/3rdParty/yui/build/container/container.js"></script>

<link type="text/css" rel="stylesheet" href="{$url}/orbicon/3rdParty/yui/build/container/assets/container.css" />
<link rel="stylesheet" type="text/css" href="{$url}/orbicon/modules/infocentar/gfx/backend.css" />
<link rel="stylesheet" type="text/css" href="{$url}/orbicon/modules/infocentar/setup/frontend.css" />

<script type="text/javascript">
	var ok = "{$isItOkToDisplay}";

	function init() {

		var handleCancel = function() {
			this.cancel();
		};

		// Instantiate the Dialog
		var myDialog = new YAHOO.widget.SimpleDialog("show_notif_popup",
													{ 	width : "300px",
														fixedcenter : true,
														draggable: false,
														visible : false,
														constraintoviewport : true,
														buttons : [{text:"Ok", handler:handleCancel}]
													} );

		// Render the Dialog
		myDialog.render();

		if(ok == "true"){
			YAHOO.util.Event.addListener(window, "load", myDialog.show, myDialog, true);
		}

	}

	YAHOO.util.Event.onDOMReady(init);
</script>

	{$intro_text}
	{$tag_cloud}

	<div id="show_notif_popup" style="visibility: hidden;">
		<div class="hd">{$confirm_domain_sig}</div>
		<div class="bd">{$confirm_msg_sig}</div>
	</div>

		<div class="cleaner"></div>
	<div id="infocentar_frontpage_menu">{$menu}</div>
	<div class="cleaner"></div>
	<div id="form_holder">
		<div id="search_holder"{$hide_search}>{$searching_form}</div>
		<div id="ask_holder"{$hide_ask}>{$ask_form}</div>
	</div>
	<div id="ic_content">
		{$quest_title}
		{$cat_title}
		<div class="cleaner"></div>

		<div id="ic_body">
			<div id="short_summary">{$summary}</div>
			<div id="front_page_question_list">
				<div class="art_decor_top"></div>
					{$show_on_screen}
				<div class="cleaner"></div>
			</div>
			<div id="front_page_category_list">
				<div class="holder">{$cats}</div>
				<div id="promo">
					<a href="http://www.hpb.hr/?hr=hpb-kontakt-centar" title="HPB kontakt centar"><img src="{$url}/site/gfx/banner.jpg" title="Kontakt centar" alt="Kontakt centar" /></a>
				</div>
			</div>
			<div class="cleaner"></div>
		</div>
	</div>
	<div class="cleaner"></div>
INFOCENTAR_FRONT;

?>