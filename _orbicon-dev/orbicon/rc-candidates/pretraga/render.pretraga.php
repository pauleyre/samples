<?php
$type = $_POST['type'];
$location = $_POST['location'];

$from_price=$_POST['from_price'];
$till_price=$_POST['till_price'];
$from_size = $_POST['from_size'];
$till_size = $_POST['till_size'];

$from_price=trim($from_price);
$till_price=trim($till_price);

$from_size=trim($from_size);
$till_size=trim($till_size);

/*if( empty($till_price)) {
header('location: http://www.dommreza.hr');
}*/


	$sql = "SELECT * FROM orbx_mod_estate WHERE type='".$type."' AND price BETWEEN $from_price AND $till_price AND location='".$location."' AND msquare BETWEEN $from_size AND $till_size";
	#var_dump($sql);
	$query = mysql_query($sql);

	$foot .='<div>Pronađeni rezultati po vrsti nekretnine <b>"'.$type.'"</b>, lokaciji <b>"'.$location.'"</b> u rasponu cijene od <b>'.$from_price.'</b> do <b>'.$till_price.'</b> Eura po m<sup>2</sup> te po kvadraturi od <b>'.$from_size.'</b> do <b>'.$till_size.'</b> po m<sup>2</sup>:</div>';
	$i=0;
	$foot .='<table border="0"><tr>';
	while($row = mysql_fetch_assoc($query)) {


		$thumb_img = (is_file(DOC_ROOT . '/site/venus/thumbs/t-'.$row['img_naziv'])) ? '<img class="thumb_image" src="'.ORBX_SITE_URL.'/site/venus/thumbs/t-'.$row['img_naziv'].'" alt="'.$row['img_alt'].'" title="'.$row['img_title'].'" />' : '<img src="'.ORBX_SITE_URL.'/site/venus/'.$row['img_naziv'].'" class="'.$row['img_css_id'].'" alt="'.$row['img_alt'].'" title="'.$row['img_title'].'" />';




	$foot .= '<td valign="top"><div id="ss"><span><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr].'&amp;event=detalj&amp;id='.$row['id'].'">'.$row['title'].'</a></span><br />';
		$foot .= '<a href="?'.$orbicon_x->ptr.'=mod.ponuda&amp;event=detalj&amp;id='.$row['id'].'">' . $thumb_img . '</a><br />
		Adresa :  '.$row['address'].'<br />
		Lokacija : '.$row['location'].'<br/>
		Tip nekretnine :  '.$row['type'].'<br />
		Cijena nekretnine : '.$row['price'].' (&euro;/m<sup>2</sup>)<br>
		Kvadratura : '.$row['msquare'].' (m<sup>2</sup>)<br/>
		</div></td>
		';

			$i++;
			if ($i % 4 == 0) {
				$foot .= '</tr><tr>';
			}

	}
	$foot .='</tr></table>';
	return $foot;
?>