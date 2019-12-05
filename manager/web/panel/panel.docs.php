<?php

require 'logic/func.upload.php';
require_once 'logic/class.Client.php';
require_once 'logic/class.Employee.php';

if(isset($_GET['c'])) {

	$e = new Employee($_SESSION['employee']['id']);
	$e->getEmployee();

	if(($_GET['c'] == 'java')) {
		$e->flags = $e->flags | Employee::USE_JAVA_UPLOADER;
	}

	if ($_GET['c'] == 'simple') {
		$e->flags &= ~Employee::USE_JAVA_UPLOADER;
	}

	$e->setEmployee();
	$_SESSION['employee']['flags'] = $e->flags;

}

if(isset($_POST['delete'])) {

	foreach ($_POST['fdelete'] as $f_id_del) {
		$f_id_del = basename(urldecode($f_id_del));
		unlink("web/upload/u{$_SESSION['employee']['id']}/$f_id_del");
	}

	if(isset($_GET['id'])) {
		meta_redirect('./?action=dc');
		exit();
	}

}

if(isset($_POST['save_txt_file']) && isset($_GET['id'])) {
	file_put_contents("web/upload/u{$_SESSION['employee']['id']}/" . basename(urldecode($_GET['id'])), $_POST['mercury_txt']);
}

?>
<table height="100%" width="100%" border=1>

<tr>
<td width="30%">
<form action="" method=post>

<ol class=list>

<?php

	$files = glob("web/upload/u{$_SESSION['employee']['id']}/{*}", GLOB_BRACE);

	foreach($files as $file) {

		if(is_dir($file)) {
			continue;
		}

		$bfile = basename($file);
		$ext = get_document_icon(get_extension($bfile));
		$selected = ($_GET['id'] == $file) ? 'style="font-style:italic"' : '';
		$file_size = get_file_size($file);
		$file_date = strftime('%d.%m.%Y %H:%M', filemtime($file));

		$file = urlencode($file);

		echo "<li class=litem><a $selected href=\"./?action=dc&id=$file\"><input type=checkbox value=\"$file\" name=fdelete[]> $ext $bfile <span>$file_size - $file_date</span></a></li>";
	}

	echo '<li class=litem>SVEUKUPNO: ' . get_dir_size("web/upload/u{$_SESSION['employee']['id']}") . '</li>';

?>
</ol>
<input type=submit name=delete value=Ukloni> <input <?php echo (!isset($_GET['id'])) ? 'disabled' : ''; ?> type="button" onclick="redirect('./?action=ab')" value="Dodaj novo">
</form>
</td>
<td>

<?php

	if($_SESSION['employee']['flags'] & Employee::USE_JAVA_UPLOADER) {
		echo '<a href="./?action=dc&c=simple">Prebaci na jednostavno dodavanje</a>';
		echo generate_upload_applet();
	}
	else {
		echo '<a href="./?action=dc&c=java">Prebaci na JAVA dodavanje</a>';

		echo '<form action="logic/render.upload.php" method="post" enctype="multipart/form-data">
		<input name=simple type=hidden value=1>
		<input name=credentials type=hidden value="'.get_ajax_id().'">
		<input type=file name=userfile[]><br>
		<input type=file name=userfile[]><br>
		<input type=file name=userfile[]><br>
		<input type=file name=userfile[]><br>
		<input type=file name=userfile[]><br>

		<input type=submit value=DODAJ name=submit>

		</form>';
	}

	$file = basename(urldecode($_GET['id']));
	$f_path = "web/upload/u{$_SESSION['employee']['id']}/$file";

	if(!empty($file)) {
		$ext = get_extension($file);
		include_once 'logic/func.mmedia.php';

		switch($ext) {
			case 'mp3': case 'wav': case 'ogg': case 'wma':
				$file_preview = get_mp3_player($file);
			break;
			case 'mpg': case 'mpeg': case 'wmv': case 'avi':
				$file_preview = get_video_player($file);
			break;
			case 'mov': case '3gp':
				$file_preview = get_apple_player($file);
			break;
			case 'flv':
				$file_preview = get_flv_player($file);
			break;
			case 'swf':
			$size = getimagesize($f_path);
$file_preview = '<object data="'.$f_path.'" type="application/x-shockwave-flash" width="'.$size[0].'" height="'.$size[1].'">
	<param name="movie" value="'.$f_path.'">
	<param name="quality" value="high">
	<param name="menu" value="0">
</object>';
			break;
			// txt
			case 'txt': case 'log': case 'bat':
				$mercury_txt = htmlspecialchars(file_get_contents($f_path));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br><input name=save_txt_file type=submit id=save_txt_file value=SPREMI>';
			break;
			// html
			case 'html': case 'htm': case 'shtml':
				$mercury_txt = htmlspecialchars(file_get_contents($f_path));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br><input name=save_txt_file type=submit id=save_txt_file value=SPREMI>';
				$file_preview .= '<script src=./web/js/edit_area/edit_area_full.js></script>
<script>
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "html",
	start_highlight: true
});
</script>';
			break;
			// php
			case 'php': case 'php3': case 'phps':
				$mercury_txt = htmlspecialchars(file_get_contents($f_path));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br><input name=save_txt_file type=submit id=save_txt_file value=SPREMI>';
				$file_preview .= '<script src=./web/js/edit_area/edit_area_full.js></script>
<script>
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "php",
	start_highlight: true
});
</script>';
			break;
			// xml
			case 'rdf': case 'xml': case 'rss':
				$mercury_txt = htmlspecialchars(file_get_contents($mercury_dir.$a['content']));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br><input name=save_txt_file type=submit id=save_txt_file value=SPREMI>';
				$file_preview .= '<script src=./web/js/edit_area/edit_area_full.js></script>
<script>
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "xml",
	start_highlight: true
});
</script>';
			break;
			// js
			case 'js':
				$mercury_txt = htmlspecialchars(file_get_contents($f_path));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br><input name=save_txt_file type=submit id=save_txt_file value=SPREMI>';
				$file_preview .= $file_preview .= '<script src=./web/js/edit_area/edit_area_full.js></script>
<script>
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "js",
	start_highlight: true
});
</script>';
			break;
			// css
			case 'css':
				$mercury_txt = htmlspecialchars(file_get_contents($f_path));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br><input name=save_txt_file type=submit id=save_txt_file value=SPREMI>';
				$file_preview .= '<script src=./web/js/edit_area/edit_area_full.js></script>
<script>
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "css",
	start_highlight: true
});
</script>';
			break;
			// vbs
			case 'vbs':
				$mercury_txt = htmlspecialchars(file_get_contents($f_path));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br><input name=save_txt_file type=submit id=save_txt_file value=SPREMI>';
				$file_preview .= '<script src=./web/js/edit_area/edit_area_full.js></script>
<script>
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "vb",
	start_highlight: true
});
</script>';
			break;
			// python
			case 'py':
				$mercury_txt = htmlspecialchars(file_get_contents($f_path));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br><input name=save_txt_file type=submit id=save_txt_file value=SPREMI>';
				$file_preview .= '<script src=./web/js/edit_area/edit_area_full.js></script>
<script>
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "python",
	start_highlight: true
});
</script>';
			break;
			case 'pdf':
				$file_preview = '<iframe src="'.$f_path.'?'. uniqid(md5(rand()), true).'" width="100%" height="500px"></iframe>';
			break;
			case 'jpg':
			case 'jpeg':
			case 'tif':
			case 'tiff':
			case 'bmp':
			case 'gif':
			case 'png':
			case 'ico':
			case 'psd':
			case 'dib':
			case 'jpe':
			case 'jfif':
				$file_preview = '<img src="'.$f_path.'?'. uniqid(md5(rand()), true).'">';
			break;
			default: $file_preview = '<a href="'.$f_path.'">'.$file.'</a>';
			break;
		}
	}

?>
<form action="" method="POST">
<?php echo $file_preview ?>
</form>
<?php
if(isset($_GET['id'])) {
?>
<div>
<input type="button" value="PREUZMI LOKALNO">
<input type="button" value="PROSLIJEDI"> <select></select>
</div>
<?php
}
?>
</td>
</tr>

</table>