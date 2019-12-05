<?php

$broj_stan = mysql_query("SELECT DISTINCT  COUNT(id) as brojac  FROM `orbx_mod_estate` WHERE `type` = 'stan' ");
$broj_s_array = mysql_fetch_object($broj_stan);
$broj_zemlj = mysql_query("SELECT DISTINCT  COUNT(id) as brojac  FROM `orbx_mod_estate` WHERE `type` = 'zemljiste' ");
$broj_z_array = mysql_fetch_object($broj_zemlj);

$event=$_GET["event"];
$sql_query = "SELECT * FROM orbx_mod_estate";
$result = mysql_query($sql_query) or die ("Error in query: $sql_query. " . mysql_error());

/////////////////////////////
//DETALJI                 ///
/////////////////////////////

if ($event=="detalj") {

if (!isset($_GET['id']) || trim($_GET['id']) == '')
{

	die("NEMA unosa sa tim ID!");
}

$id = $_GET['id'];
$query =mysql_query("SELECT * FROM orbx_mod_estate WHERE id = '$id' ");

$rezultat = mysql_fetch_object($query);


//za ostale slike
$ostale_male .= $rezultat->other_img;
$ostale_male_array = explode(',',$ostale_male);
$ostale_velike .= $rezultat->other_img_big;
$ostale_velike_array = explode(',', $ostale_velike);

$show_details .="<h6>".ucfirst($rezultat->title)."</h6>
<div id='show_list2'>";
/*    <a href='".ORBX_SITE_URL."/site/venus/". $rezultat->img_naziv."' rel='lightbox[roadtrip]' title ='".$rezultat->tag_title."'>
    <img src='".ORBX_SITE_URL."/site/venus/". $rezultat->img_naziv."' alt ='".$rezultat->img_alt."' title = '".$rezultat->img_title."' id='".$rezultat->img_title."' /></a>*/


					$details .= (is_file(DOC_ROOT . '/site/venus/thumbs/t-'.$rezultat->img_naziv)) ? '<img class="thumb_image" src="'.ORBX_SITE_URL.'/site/venus/thumbs/t-'.$rezultat->img_naziv.'" alt="'.$rezultat->img_alt.'" title="'.$rezultat->img_title.'" style="padding:5px;" />' : '<img src="'.ORBX_SITE_URL.'/site/venus/'.$rezultat->img_naziv.'" class="thumb_image" alt="'.$rezultat->img_alt.'" title="'.$rezultat->img_title.'" style="padding:5px;" />';

				$show_details .= '<a href="' . ORBX_SITE_URL . '/site/venus/' . $rezultat->img_naziv.'" rel=\'lightbox[roadtrip]\'>' . $details . '</a>
				';

					$data .="<br />
    <br />

    <span>Opis nekretnine</span>
  <p class='opis'>$rezultat->text<br /></p>
    <br />
    <span>Adresa : ".ucwords($rezultat->address)."</span>
    <br />
    <span>Lokacija : ". ucfirst($rezultat->location)."</span>
    <br />
    <span>Tip nekretnine :".ucfirst($rezultat->type)."</span>
    <br/>
    <span>Cijena : ".$rezultat->price."  &euro;/m<sup>2</sup></span>
    <br/>
    <span>Kvadratura : ".$rezultat->msquare." m<sup>2</sup></span>
    <br />
    <br />
    <a id='back' href='javascript:history.go(-1)'>NATRAG</a><br /><br />";



/*
	require_once DOC_ROOT.'/orbicon/modules/ponuda/gallery_images.php';
	print_image_gallery($category);
	echo $images;
	var_dump($images);
	var_dump($br);
*/
	function print_image_gallery($category)
	{
		global $dbc, $orbicon_x;



		$max_images_box = 6;
		$max_image_box_previews = 5;
		$css_width = intval(60 / $max_image_box_previews);
		$i = 0;

		$r = $dbc->_db->query(sprintf('		SELECT 		*
											FROM 		'.VENUS_IMAGES.'
											WHERE 		(category = %s)
											ORDER BY 	last_modified
											', $dbc->_db->quote($category)));

		$a = $dbc->_db->fetch_array($r);

		while($i < $max_images_box) {
			$images .= '<div class="news_cat_box">';


			while($a) {

				$thumb_img = (is_file(DOC_ROOT . '/site/venus/thumbs/t-'.$a['permalink'])) ? '<img class="thumb_image" src="'.ORBX_SITE_URL.'/site/venus/thumbs/t-'.$a['permalink'].'" alt="'.$a['permalink'].'" title="'.$a['permalink'].'" style="padding:5px;" />' : '<img src="'.ORBX_SITE_URL.'/site/venus/'.$a['permalink'].'" class="thumb_image" alt="'.$a['permalink'].'" title="'.$a['permalink'].'" style="padding:5px;" />';

				$images .= '<div class="news_cat_box_preview" style="margin: 0 1px;"><a href="' . ORBX_SITE_URL . '/site/venus/' . $a['permalink'].'" rel=\'lightbox[roadtrip]\'>' . $thumb_img . '</a><br />
				</div>';
				$a = $dbc->_db->fetch_array($r);
			}

			$images .= '</div>';
			$i++;

		}

						#me:
				$read = $dbc->_db->query(sprintf('SELECT COUNT(id) AS numrows FROM '.VENUS_IMAGES.' WHERE category=%s', $dbc->_db->quote($category) ));
				$row = $dbc->_db->fetch_array($read);
				$numrows = $row['numrows'];
				#var_dump($numrows);

				// how many pages we have when using paging?
				$maxPage = ceil($numrows/$rowsPerPage);

				// print the link to access each page
				#$self = $_SERVER['PHP_SELF'];
				$nav = '';
				for($page = 1; $page <= $maxPage; $page++)
				{
					if ($page == $pageNum)
					{
						$nav .= "$page";   // no need to create a link to current page
					}
					else
					{
						$show=$_GET['show_only'];
						if (isset($show)) {
							$nav .= " <a class=\"page\" href=\"?{$orbicon_x->ptr}=orbicon/venus&amp;show_only=".$_GET['show_only']."&amp;page=$page\">$page</a> ";
						} else {
							#$nav .= " <a href=\"$self?page=$page\">$page</a> ";
							$nav .= ' <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr].'&amp;page='.$page.'">'.$page.'</a> ';
						}

					}
				}
				// creating previous and next link
				// plus the link to go straight to
				// the first and last page
				if ($pageNum > 1)
				{
					$page = $pageNum - 1;
				/*    $prev = " <a href=\"$self?page=$page\">[Prethodna]</a> ";

					$first = " <a href=\"$self?page=1\">[Prva]</a> ";
				*/
				}
				else
				{
					$prev  = '&nbsp;'; // we're on page one, don't print previous link
					$first = '&nbsp;'; // nor the first page link
				}
				if ($pageNum < $maxPage)
				{
					$page = $pageNum + 1;
				/*    $next = " <a href=\"$self?page=$page\">[Sljedeca]</a> ";

					$last = " <a href=\"$self?page=$maxPage\">[Posljednja]</a> ";
				*/
				}
				else
				{
					$next = '&nbsp;'; // we're on the last page, don't print next link
					$last = '&nbsp;'; // nor the last page link
				}
				// print the navigation link
				echo '<style> a.page,a.page:hover,a.page:visited{ color:#003399;} </style>';
				$images .='<p><center>'.$nav.'</center></p><br />';
				#mysql_free_result($read);

		return $images;

	}

	$slike = print_image_gallery($rezultat->gallery);


	if (empty($ostale_male) and empty($ostale_velike)) {
/*
$if_cond .="<a href='".ORBX_SITE_URL."/site/gfx/logo.jpg' rel='lightbox' title ='Dom Mreža d.o.o.'>
    <img src='".ORBX_SITE_URL."/site/gfx/logo.jpg' alt ='image alt' title = 'Dom Mreža d.o.o.' id='image id' width='311' height='200'  /></a>";
*/

$if_cond .="<a href='".ORBX_SITE_URL."/site/gfx/logo.jpg' rel='lightbox' title ='Dom Mreža d.o.o.'>
    <img src='".ORBX_SITE_URL."/site/gfx/logo.jpg' alt ='image alt' title = 'Dom Mreža d.o.o.' id='image id' width='' height='' style='margin-right:150px;'  /></a>";

	}else {
		for ($x=0;$x<sizeof($ostale_male_array) and $x<sizeof($ostale_velike_array);$x++)
{
		$slike .="<a href='".ORBX_SITE_URL."/site/venus/".$ostale_velike_array[$x]."' rel='lightbox[roadtrip]' title ='".$rezultat->tag_title."'>
    			<img src='".ORBX_SITE_URL."/site/venus/".$ostale_male_array[$x]."' alt ='".$rezultat->img_alt."' width='72' height='53'  class='image' id='".$rezultat->img_title."' title = '".$rezultat->img_title."'  /></a>
	";
}
	}
	$nastavak .="


</div>
";

return $header.$data.$show_details.$if_cond.$slike.$nastavak;

}

// if records present make a list of all items in database
		if (mysql_num_rows($result) > 0)
		{

			$header ="<ul style='margin: 0;
margin-bottom: 1em;
padding-left: 0;
float: left;
font-weight: bold;
width: 100%;
border: 1px solid #DFDFDF;
border-width: 1px 0;
margin: 0;
padding: 0;'>

<li style='display: inline;'><a style='float: left;
color: gray;
font-weight: bold;
padding: 2px 6px 4px 6px;
text-decoration: none;
background: white url(http://www.dynamicdrive.com/cssexamples/media/menudivide.gif) top right repeat-y;' href='?hr=mod.ponuda&event=stanovi'>Stanovi($broj_s_array->brojac)</a></li>

<li style='display: inline;'><a style='float: left;
color: gray;
font-weight: bold;
padding: 2px 6px 4px 6px;
text-decoration: none;
background: white url(http://www.dynamicdrive.com/cssexamples/media/menudivide.gif) top right repeat-y;' href='?hr=mod.ponuda&event=zemljista'>Zemljište($broj_z_array->brojac)</a></li>
<li style='display: inline;'><a style='float: left;
color: gray;
font-weight: bold;
padding: 2px 6px 4px 6px;
text-decoration: none;
background: white url(http://www.dynamicdrive.com/cssexamples/media/menudivide.gif) top right repeat-y;'
href='?hr=mod.ponuda&event=kuce'>Kuće</a></li>
					</ul>";
			$div_start ="<div style='width=750px;float=right;'>";
			$div_end ="</div>";

			/* cCde for pagination. It is continued in file "detail_listing.php" */
			require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

	$_GET['p'] = isset($_GET['p']) ? $_GET['p'] : 1;
	$_GET['pp'] = isset($_GET['pp']) ? $_GET['pp'] : 12;
	$rowsPerPage = $_GET['pp'];
	$offset = ($_GET['p'] -1) * $rowsPerPage;

$nav .="<style>
		.orbicon_pagination strong, .orbicon_pagination a, .orbicon_pagination a:hover, .orbicon_pagination a:visited
		{
				font-size:10px !important;
		}</style>";

	$pagination = new Pagination('p', 'pp');

	if ($event=="stanovi")
	{
		$prikaz = mysql_query("SELECT * FROM `orbx_mod_estate` WHERE `type` = 'stan' LIMIT $offset, $rowsPerPage");

	$total = mysql_query("SELECT COUNT(id) FROM `orbx_mod_estate` WHERE `type` = 'stan'");
	$total = mysql_fetch_array($total);
	$total = $total[0];

		$pagination->total = $total;

		$pagination->split_pages();

		$nav .="<p class=pagination>". $pagination->construct_page_nav('http://www.dommreza.hr/?hr=mod.ponuda&event='.$_GET['event'])."</p>";

		require 'detail_listing.php';
		return $zemljista . $nav;

	}

	if ($event=="kuce")
	{
		$prikaz = mysql_query("SELECT * FROM `orbx_mod_estate` WHERE `type` = 'kuca' LIMIT $offset, $rowsPerPage");

	$total = mysql_query("SELECT COUNT(id) FROM `orbx_mod_estate` WHERE `type` = 'kuca'");
	$total = mysql_fetch_array($total);
	$total = $total[0];
		$pagination->total = $total;

		$pagination->split_pages();

		$nav .= $pagination->construct_page_nav('http://www.dommreza.hr/?hr=mod.ponuda&event='.$_GET['event']);

		require 'detail_listing.php';
		return $zemljista . $nav;

	}

		if ($event=="apartmani")
	{
		$prikaz = mysql_query("SELECT * FROM `orbx_mod_estate` WHERE `type` = 'apartman' LIMIT $offset, $rowsPerPage");

	$total = mysql_query("SELECT COUNT(id) FROM `orbx_mod_estate` WHERE `type` = 'apartman'");
	$total = mysql_fetch_array($total);
	$total = $total[0];
		$pagination->total = $total;

		$pagination->split_pages();

		$nav .= $pagination->construct_page_nav('http://www.dommreza.hr/?hr=mod.ponuda&event='.$_GET['event']);

		require 'detail_listing.php';
		return $zemljista . $nav;

	}

	if ($event=="zemljista")
	{
		$prikaz = mysql_query("SELECT * FROM `orbx_mod_estate` WHERE `type` = 'zemljiste' LIMIT $offset, $rowsPerPage");

	$total = mysql_query("SELECT COUNT(id) FROM `orbx_mod_estate` WHERE `type` = 'zemljiste'");
	$total = mysql_fetch_array($total);
	$total = $total[0];
		$pagination->total = $total;

		$pagination->split_pages();

		$nav .= $pagination->construct_page_nav('http://www.dommreza.hr/?hr=mod.ponuda&event='.$_GET['event']);

		require 'detail_listing.php';
		return $zemljista . $nav;

	} else {
		$prikaz = mysql_query("SELECT * FROM orbx_mod_estate LIMIT $offset, $rowsPerPage");

	$total = mysql_query("SELECT COUNT(id) FROM `orbx_mod_estate`");

	$total = mysql_fetch_array($total);
	$total = $total[0];

		$pagination->total = $total;

		$pagination->split_pages();

		$nav .= $pagination->construct_page_nav('http://www.dommreza.hr/?hr=mod.ponuda&event='.$_GET['event']);


		require 'detail_listing.php';
		return $zemljista . $nav;
	}

}
?>