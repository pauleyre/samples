<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:
	Modified.......:
*-----------------------------------------------------------------------*/

	function get_page_formats()
	{
		global $orbicon_x;

		// all switched off
		if(
			(Settings::get_news_property_set($_SESSION['site_settings']['news_properties'], ORBX_CONTENT_PROP_ALT_PDF) === false) &&
			(Settings::get_news_property_set($_SESSION['site_settings']['news_properties'], ORBX_CONTENT_PROP_ALT_TXT) === false) &&
			(Settings::get_news_property_set($_SESSION['site_settings']['news_properties'], ORBX_CONTENT_PROP_ALT_HTML) === false)) {
			return '';
		}

		if($_GET[$orbicon_x->ptr] == 'attila') {
			return '';
		}

		if(scan_templates('<!>ALT_CONTENT_FORMATS') < 1) {
			return false;
		}

		$a = array(
				_L('pdf') => array('ext' => 'pdf', 'ico' => 'pdf-file.png'),
				_L('text_only') => array('ext' => 'txt', 'ico' => 'page_white_text.png'),
				_L('handheld') => array('ext' => 'html', 'ico' => 'ipod.png')
				);

		foreach($a as $key => $value) {

			switch($value['ext']) {
				case 'pdf': 	$bit = ORBX_CONTENT_PROP_ALT_PDF; 		break;
				case 'txt': 	$bit = ORBX_CONTENT_PROP_ALT_PDF;  		break;
				case 'html':	$bit = ORBX_CONTENT_PROP_ALT_PDF; 		break;
			}

			if(Settings::get_news_property_set($_SESSION['site_settings']['news_properties'], $bit) === true) {
				$url = url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr].'/'.$value['ext'], ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.$_GET[$orbicon_x->ptr].'/'.$value['ext']);
				$menu .= "<li><a href=\"$url\"><img src=\"".ORBX_SITE_URL."/orbicon/gfx/file_icons/{$value['ico']}\" alt=\"$key\" title=\"$key\" /> $key</a></li>";
			}
		}

		unset($a, $key, $value);
		// you can add '._L('alt_content').'
		return '
		<div id="page_versions"><br />
			<ul>'.$menu.'</ul>
		</div>';
	}

	function output_page_format($format)
	{
		global $orbicon_x;
		require_once DOC_ROOT . '/orbicon/class/inc.column.php';

		$content = load_column(true);

		switch($format) {
			case 'txt':
				$replace_me = array('<br>', '<br />', '<br/>', '<li>', '</li>', '&nbsp;');
				$replace_with = array("\n", "\n", "\n", '* ', "\n", ' ');
				header('Content-Type: text/plain; charset=UTF-8', true);
				$content['magister_content'] = str_replace('</p>', "\n", $content['magister_content']);
				$content['magister_content'] = strip_tags($content['magister_content'], '<br><li>');
				$content['magister_content'] = utf8_html_entities($content['magister_content'], true);
				$content['magister_content'] = str_replace($replace_me, $replace_with, $content['magister_content']);
				$content['magister_content'] = strip_tags($content['magister_content']);
				$page .= str_replace('&nbsp;', ' ', utf8_html_entities($content['title'], true))."\r\n\r\n";
				$page .= $content['magister_content'];
				unset($content, $replace_me, $replace_with);
			break;
			case 'html':
				$format = explode('/', $_GET[$orbicon_x->ptr]);
				array_pop($format);
				$format = implode('/', $format);

				$content['magister_content'] = strip_selected_tags_by_id_or_class(array('innerRightCol', 'tools', 'printSend', 'ibankBox', 'related', 'serFAQ', 'popularno', 'clickMe', 'related_gray'), $content['magister_content']);
				$content['magister_content'] = str_replace('COLUMN_TITLE', $content['title'], $content['magister_content']);

				$org_url = ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$format;
				header('Content-Type: text/html; charset=UTF-8', true);
				$css = '<style type="text/css">/*<![CDATA[*/
@import url("'.ORBX_SITE_URL.'/site/gfx/print.css");
/*]]>*/</style>';
				$page .= '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
				$page .= '<html><head><title>'.$content['title'].'</title>'.$css.'</head><body>
				<div class="print-logo"><img src="./site/gfx/print-logo.gif" /></div>';
				$page .= $content['magister_content'];
				$page .= '
				<script type="text/javascript" src="./orbicon/controler/gzip.server.php?file=/orbicon/javascript/orbiconx.final.js&amp;'.ORBX_BUILD.'"></script>
				<script type="text/javascript"><!-- // --><![CDATA[
if(sIFR) {
	var interstate = {
      src: \'./site/gfx/flash/interstate3.swf\'
    };

    sIFR.activate(interstate);

    sIFR.replace(interstate, {
      selector: \'h2.buster\'
      ,css: {
        \'.sIFR-root\': { \'color\': \'#cc0000\' }
      },wmode: \'transparent\'
    });
}
//]]></script>';
				$page .= '<p>Org. link: <a href="'.$org_url.'">'.$org_url.'</a></p></body></html>';
				unset($content, $format, $org_url);
			break;
			case 'pdf':
				$content['title'] = str_replace('&nbsp;', ' ', utf8_html_entities($content['title'], true));
				$doc_title = $content['title'];
				$doc_subject = $content['title'];
				$doc_keywords = $content['title'];
				$htmlcontent = strip_tags($content['magister_content'],
				'<td><tr><th><table><sup><sub><small><b><strong><i><em><u><ul><ol><li><p><hr><a><font><blockquote><br><h1><h2><h3><h4><h5><h6>');
				$doc_date = (empty($content['date'])) ? $content['lastmod'] : $content['date'];
				$doc_date = date($_SESSION['site_settings']['date_format'], $doc_date);

				require_once DOC_ROOT.'/orbicon/3rdParty/tcpdf/config/lang/eng.php';
				require_once DOC_ROOT.'/orbicon/3rdParty/tcpdf/tcpdf.php';

				//create new PDF document (document units are set by default to millimeters)
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

				// set document information
				$pdf->SetCreator(PDF_CREATOR);
				$pdf->SetAuthor(PDF_AUTHOR);
				$pdf->SetTitle($doc_title);
				$pdf->SetSubject($doc_subject);
				$pdf->SetKeywords($doc_keywords);

				$pdf->SetHeaderData(NULL, NULL, $doc_title, $doc_date);

				//set margins
				$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
				//set auto page breaks
				$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
				$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

				$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
				$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

				$pdf->setLanguageArray($l); //set language items

				//initialize document
				$pdf->AliasNbPages();

				$pdf->AddPage();

				// output some HTML code
				$pdf->WriteHTML($htmlcontent, true, 0);

				//Close and output PDF document
				$pdf->Output();
				unset($content, $pdf, $doc_title, $doc_subject, $doc_keywords, $doc_date, $htmlcontent);
			break;
		}
		return $page;
	}

?>