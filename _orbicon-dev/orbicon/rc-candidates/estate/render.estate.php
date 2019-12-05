<?php

$prikaz =mysql_query("SELECT * FROM orbx_mod_estate ORDER BY `orbx_mod_estate`.`id` DESC LIMIT 8");

#mysql_query("SET NAMES 'utf8'");
$divovi .='<table border="0"><tr>';
$i=0;
while ($fetch = mysql_fetch_assoc($prikaz)) 
{	

 $divovi .= "<td valign='top'><div class='showcase_item'>
			<h4>" . $fetch['title'] . "</h4>
				<div>";
					/*
					<img  height='93' width='124' class='" . $fetch['img_css_id'] . "' src='".ORBX_SITE_URL."/site/venus/".$fetch['img_naziv']."' alt='" . $fetch['img_alt']  . "' title='" . $fetch['img_title'] . "' />
					*/
					
					$thumb_img = (is_file(DOC_ROOT . '/site/venus/thumbs/t-'.$fetch['img_naziv'])) ? '<img class="thumb_image" src="'.ORBX_SITE_URL.'/site/venus/thumbs/t-'.$fetch['img_naziv'].'" alt="'.$fetch['img_alt'].'" title="'.$fetch['img_title'].'" />' : '<img src="'.ORBX_SITE_URL.'/site/venus/'.$fetch['img_naziv'].'" class="'.$fetch['img_css_id'].'" alt="'.$fetch['img_alt'].'" title="'.$fetch['img_title'].'" />';
			
/*
$divovi .= '<div class="news_cat_box_preview" style="margin: 0 1px;"><a href="' . ORBX_SITE_URL . '/site/venus/' .$fetch['img_naziv'].'" rel=\'lightbox[roadtrip]\'></a><br /></div>';
*/
					
					$divovi .="<a href='?hr=mod.ponuda&event=detalj&id=".$fetch['id']."'>" . $thumb_img . "</a>
							<p>Cijena: <span>" . $fetch['price'] . " </span>&euro;/m<sup>2</sup></p>
							<p>Kvadratura: <span>" . $fetch['msquare'] . " </span>m<sup>2</sup></p>
				
					</div>
				</div></td>";
			$i++; 
			if ($i % 4 == 0) { 
				$divovi .= "</tr><tr>"; 
			}


			
}


$divovi .= "</tr></table>";


return  $divovi;
?>