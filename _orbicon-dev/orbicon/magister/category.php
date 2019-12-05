<?php
/**
 * Text DB categories
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @subpackage Magister
 * @version 1.30
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */
	$aQuery = explode('/', $_GET['read']);

	$r = $dbc->_db->query(sprintf('	SELECT 	*
									FROM 	'.MAGISTER_CATEGORIES.'
									WHERE 	(permalink = %s) AND
											(language = %s)',
									$dbc->_db->quote($aQuery[1]), $dbc->_db->quote($orbicon_x->ptr)));
	$a = $dbc->_db->fetch_assoc($r);

	$page_title = (!empty($aQuery[2]) && is_numeric($aQuery[2])) ? $a['name'].' // '.$aQuery[2].' '._L('from').' '.($aQuery[2] + 50) : $a['name'];

	if(empty($a['id'])) {
		header('HTTP/1.1 404 Not Found', true);
		$_SESSION['cache_status'] = 404;
		$page_title = '404 Not Found';
	}

	$orbicon_x->set_page_title($page_title);
?>
<table style="width:100%;">
<tr>
	<td style="background:#ffffff; border:1px solid #cccccc; padding:0.5em; vertical-align:top;">
				<h2><?php echo $a['name']; ?></h2>
				<h3><?php echo $a['description']; ?></h3>
				<h4><?php echo _L('you_are_in_category'); ?> &quot;<?php echo htmlspecialchars($aQuery[1]); ?>&quot;</h4>

	<?php

			$rResultNavigation = $dbc->_db->query(sprintf('	SELECT 		COUNT(id)
															FROM 		'.MAGISTER_TITLES.'
															WHERE 		(live = 1) AND
																		(hidden = 0) AND
																		(category = %s) AND
																		(language = %s)',
			$dbc->_db->quote($aQuery[1]), $dbc->_db->quote($orbicon_x->ptr)));
			$aCountNav = $dbc->_db->fetch_array($rResultNavigation);
			$my_count = $aCountNav;

			if($aCountNav[0] > 50) {
				echo '<h3>' . _L('navigation') . ': ';
				$i = 0;
				while($aCountNav[0] > 0) {
					$link = ($i == 0) ? '' : ($i * 50);
					$title = ($i + 1);
					echo '<a style="padding-left: 0.5em;padding-right:0.5em;" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&amp;read=kategorija/'.$aQuery[1].'/'.$link.'">'.$title.'</a><span="style:font-size:150%;">|</span>';
					if($title == 10) {echo '<br />';}
					$aCountNav[0] = $aCountNav[0] - 50;
					$i ++;
				}
				echo '</h3>';
			}

			$start = (empty($aQuery[2])) ? 0 : $aQuery[2];

			if($aQuery[1] == '_orbx_unsorted') {
				$aQuery[1] = '';
			}

			$r = $dbc->_db->query(sprintf('		SELECT 		*
												FROM 		'.MAGISTER_TITLES.'
												WHERE 		(live = 1) AND
															(hidden = 0) AND
															(category = %s) AND
															(language = %s)
												ORDER BY 	live_time DESC, title
												LIMIT 		%s, 50', $dbc->_db->quote($aQuery[1]), $dbc->_db->quote($orbicon_x->ptr), $dbc->_db->quote($start)));
			$a = $dbc->_db->fetch_assoc($r);

			if(!empty($a['id'])) {

				/**/
				echo '<table class="magister_category_table">
						<tr style="border-bottom: 1px solid #cccccc;">
							<th>'._L('title').'</th>
							<th style="width:10px;"></th>
							<th>'._L('publish_date').'</th>
							<th style="width:10px;"></th>
							<th>'._L('last_mod').'</th>
							<th style="width:10px;"></th>
							<th>'._L('category').'</th>
							<!-- <th style="width:10px;"></th>
							<th>'._L('published').'</th> -->
						</tr>';

				$i = 0;

				/**/

				while($a) {
					$r_c = $dbc->_db->query(sprintf('	SELECT 		COUNT(id)
														FROM 		'.MAGISTER_CONTENTS.'
														WHERE 		(live = 1) AND
																	(hidden = 0) AND
																	(question_permalink = %s) AND
																	(language = %s)', $dbc->_db->quote($a['permalink']), $dbc->_db->quote($orbicon_x->ptr)));
					$a_c = $dbc->_db->fetch_array($r_c);

					//$status_img = ($a['live'] == 1) ? 'accept.png' : 'cancel.png';
					$style = (($i % 2) == 0) ? '#eeeeee' : '#ffffff';
					/**/
					echo'
						<tr style="background:'.$style.';">
							<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&amp;read=clanak/'.$a['permalink'].'/">'.$a['title'].' ( '. $a_c[0] .' )</a></td>
						<td></td>
						<td align="center">'.date($_SESSION['site_settings']['date_format'] . ' H:m:i', $a['live_time']).'</td>
						<td></td>
						<td align="center">'.date($_SESSION['site_settings']['date_format'] . ' H:m:i', $a['last_modified']).'</td>
						<td></td>
						<td align="center">'.$a['category'].'</td>
						<!-- <td></td>
						<td align="center"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/'.$status_img.'" alt="'.$status_img.'" title="'.$status_img.'" /></td> -->
					</tr>';
					/**/

					$a = $dbc->_db->fetch_assoc($r);
					$i ++;
				}

				/**/
				echo '</table>';
				/**/

			}
			else {
				echo '<ol><li><h2>'.sprintf(_L('no_txt_cat_data'), '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister">', '</a>').'</h2></li>';
			}

	echo '</ol>';

	if($my_count > 0) {
		echo '<p><input type="button" value="'._L('add_new').'" onclick="javascript: redirect(\''.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/magister\');"  /></p>';
	}


?>
			</td>
	<td></td>
</tr>
</table>