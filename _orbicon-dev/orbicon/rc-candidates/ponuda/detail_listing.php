<?php

$zemljista .='<style>
					.list_link {
					color: gray;
					font-weight: bold;
					padding: 2px 6px 4px 6px;
					text-decoration: none;
				}
			</style>
			<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr].'&amp;event=stanovi" class="list_link">Stanovi</a> |
			<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr].'&amp;event=kuce" class="list_link">Kuće</a> |
			<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr].'&amp;event=apartmani" class="list_link">Apartmani</a> |
			<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr].'&amp;event=zemljista" class="list_link">Zemljišta</a>
			<table border="0"><tr>';
$i=0;

		while ($fetch = mysql_fetch_assoc($prikaz))
		{

		 $zemljista .= "<td valign='top'><div id='ponuda_lista'>
					<h6>" . $fetch['title'] . "</h6>
						<div>";

							$thumb_img = (is_file(DOC_ROOT . '/site/venus/thumbs/t-'.$fetch['img_naziv'])) ? '<img class="thumb_image" src="'.ORBX_SITE_URL.'/site/venus/thumbs/t-'.$fetch['img_naziv'].'" alt="'.$fetch['img_alt'].'" title="'.$fetch['img_title'].'" />' : '<img src="'.ORBX_SITE_URL.'/site/venus/'.$fetch['img_naziv'].'" class="'.$fetch['img_css_id'].'" alt="'.$fetch['img_alt'].'" title="'.$fetch['img_title'].'" />';

				#<span>Tip : ".$fetch['type']." </span>
				#<br/>
							$zemljista .='<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr].'&amp;event=detalj&amp;id='.$fetch['id'].'">' . $thumb_img . '</a>
									  	<span>Adresa : '.$fetch['address'].' </span>
										<br/>
										<span>Lokacija : '.$fetch['location'].' </span>
										<br/>
										<span>Cijena : '.$fetch['price'].' &euro;/m<sup>2</sup></span>
										<br/>
										<span>Kvadratura : '.$fetch['msquare'].' m<sup>2</sup></span> <br />

							</div>
						</div></td>';
					$i++;
					if ($i % 4 == 0) {
						$zemljista .= "</tr><tr>";
					}



		}


		$zemljista .= "</tr></table>";

?>