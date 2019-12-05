<ul>
<?php
/*ini_set("display_errors", 1);
error_reporting(E_ALL);*/

	function GetExtension($sFilename) {
		return (strtolower(substr(strrchr(basename($sFilename), "."), 1)));
	}

	if(isset($_GET["del"])) {
		unlink("radni_nalozi/{$_GET['dir']}{$_GET['del']}");
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php?page=pdf_docs&dir={$_GET['dir']}\">";
	}
	

	$_GET["dir"] = (isset($_GET["dir"])) ? $_GET["dir"] : "";
	$_GET["dir"] = ($_GET["dir"] == "..") ? "" : $_GET["dir"];

	$d =  dir("radni_nalozi/{$_GET['dir']}");
	$entry = $d->read();

	$aLinks = explode("/", $_GET['dir']);

	array_pop($aLinks);
	array_pop($aLinks);

	$sShortened = implode("/", $aLinks);

	while($entry !== FALSE)
	{
		$is_dir = is_dir("radni_nalozi/{$_GET['dir']}$entry");
		if($is_dir && $entry != ".")
		{
			$link = ($entry == "..") ? "$sShortened/" : "{$_GET['dir']}$entry/";
			echo "<li style=\"font-size: 30px; list-style-image:url('gfx/folder.gif');\"><a href=\"index.php?page=pdf_docs&amp;dir=$link\">$entry</a></li>\n";
		}
		else if(!$is_dir && GetExtension($entry) == "pdf")
		{
			echo "<li style=\"font-size: 30px; list-style-image:url('gfx/pdf.gif');\">&nbsp;<input onclick=\"javascript: return false;\" onmousedown=\"javascript: if(window.confirm('Ukloni dokument &quot; $entry &quot;?')) {location.href='index.php?page=pdf_docs&dir={$_GET['dir']}&del=$entry';}\" type=\"button\" value=\"X\" style=\"font-size: 100%;\" />&nbsp;<a href=\"radni_nalozi/{$_GET['dir']}$entry\">$entry</a></li>\n";
		}
		$entry = $d->read();
	}
	$d->close();
?>
</ul>