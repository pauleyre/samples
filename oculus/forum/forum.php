<?php
require_once('class.forum.php');

$forum->Show_SFname();

$forum->Main_page();
print("<br><br>\n");

if (isset($_POST['submit'])) {
    $for_mail = $_POST['frm_mail'];
    if (!empty($_POST['frm_mail'])) {
	    $valmail = $forum->ProvjeriEmailFormat($_POST['frm_mail']);
        if (!$valmail) {
            echo "Your mail was invalid so was droped!<br><br>\n";
            $for_mail = "";      // if mail invalid then dropped
        }
    }

    $forum->Add_new_post($_POST['frm_ptitle'],$_POST['frm_text'],$for_mail,$_POST['frm_ip'],$_POST['frm_name'],$_POST['frm_wid']);
}

if (isset($_GET['wid'])) {
    #phpinfo();
    $forum->Show_SForum_Threads($_GET['wid']);
    $forum->pansw = $_GET['wid'];
} else {
    $forum->Show_SForum();
    $forum->pansw = 0;
}

$forum->Show_frm($forum->ptitle);

print("<br><br>\n");
$forum->Main_page();
?>