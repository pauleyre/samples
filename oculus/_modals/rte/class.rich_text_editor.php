<?php

define('RTE_ROOT', '_modals');
define('RTEC_COLOR_PALETTE', RTE_ROOT.'/rte/rte_components/color_palette.php');
define('RTEC_CHARACTER_MAP', RTE_ROOT.'/rte/rte_components/char_map.php');
define('RTEC_COLOR_PICKER', RTE_ROOT.'/rte/rte_components/color_picker.html');


class RichTextEditor
{
	var $nToolbarWidth;
	var $nToolbarHeight;
	var $sToolbarSource;

	function RichTextStartNewToolbar($nWidth = 100, $nHeight = 400)
	{
		$_SESSION['RTE_UserDocument'] = (isset($_SESSION['RTE_UserDocument'])) ? $_SESSION["RTE_UserDocument"] : NULL;
		$this -> nToolbarWidth = $nWidth;
		$this -> nToolbarHeight = $nHeight;

		$this -> sToolbarSource = 'http://'.$_SERVER['SERVER_NAME'].'/'.RTE_ROOT.'/rte/rte_components/frame.php?ftype='.$_GET['ftype'].'&amp;id='.$_GET['id'];
	}

	function _PaletteBuildRGB($nR, $nG, $nB)
	{
		(array) $aColors = array(0 => "FF", 1 => "CC", 2 => "99", 3 => "66", 4 => "33", 5 => "00");
		(string) $sColor = $aColors[$nR].$aColors[$nG].$aColors[$nB];
		return "<td style=\"background-color: #$sColor;\">
					<a href=\"javascript: void(0);\" onmouseover= \"javascript: RichTextChangeHexValue('$sColor');\" onclick=\"javascript: RichTextForeCol('RTE', '$sColor');\" title=\"$sColor\">
						<img src=\"../graphics/background/table_cube6x6.gif\" style=\"border: none; width: 6px; height: 6px;\" />
					</a>
				</td>\n";
	}

	function RichTextBuildPalette()
	{
		(string) $sSessPaletteVar = "PaletteRTE";
		(int) $nR = 0;
		(int) $nG = 0;
		(int) $nB = 0;
		(int) $nCount = 6;
		(string) $sPalette = "<table id=\"ColorPalette\" name=\"ColorPalette\"><tbody>\n";

		for($nG = 0; $nG < $nCount; $nG += 2)
		{
			for($nR = 0; $nR < $nCount; $nR ++)
			{
				$sPalette .= "<tr>\n";

				for($nB = 0; $nB < $nCount; $nB ++) {
					$sPalette .= $this -> _PaletteBuildRGB($nR, $nG, $nB);
				}

				for($nB = 0; $nB < $nCount; $nB ++) {
					$sPalette .= $this -> _PaletteBuildRGB($nR, ($nG + 1), $nB);
				}

				$sPalette .= "</tr>\n";
			}
		}

		$sPalette .= "</tbody></table>\n";
		return $sPalette;
	}

	function RichTextCapturePostData()
	{
		if($_POST["RTEData"] != "")
		{
			(string) $sUserDocument = "";
			$sUserDocument = $this -> _RichTextSaveAsHTML();

			if(file_exists($sUserDocument)) {
				return 1;
			}
			else {
				return 0;
			}
		}
		return 0;
	}

	function _RichTextSaveAsHTML()
	{
		(string) $sUserDir = "../";
		(string) $sUserDocument = $sUserDir.$_POST["RTEdocument"].".html";

		$rDocument = fopen($sUserDocument, "wb");
		fwrite($rDocument, $_POST["RTEData"]);
		fclose($rDocument);

		return $sUserDocument;
	}

	function RichTextGetResources()
	{
		$dir = RTE_ROOT;
		return <<<RTE_RES
<link href="{$dir}/rte/rich_text_editor.css" rel="stylesheet" type="text/css" media="all" />
<script src="{$dir}/rte/rich_text_editor.js"></script>
RTE_RES;
	}
}

?>