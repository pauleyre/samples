<?php
	function print_image_gallery($category)
	{
		global $dbc, $orbicon_x;
		
			if($_GET['show_only'] == '_orbx_unsorted') {
				$show_only = 'AND ((category = \'\') OR (category IS NULL))';
			}
			else {
				$show_only = (empty($_GET['show_only'])) ? '' : 'AND (category = '.$dbc->_db->quote($_GET['show_only']).')';
			}
		
			$rowsPerPage = 8; 
			// by default we show first page 
			$pageNum = 1; 
			// if $_GET['page'] defined, use it as page number 
			if(isset($_GET['page'])) 
			{ 
				(int) $pageNum = $_GET['page']; 
			} 
			// counting the offset 
			$offset = ($pageNum - 1) * $rowsPerPage; 

		$max_images_box = 4;
		$max_image_box_previews = 3;
		$css_width = intval(60 / $max_image_box_previews);
		$i = 0;

		$r = $dbc->_db->query(sprintf('		SELECT 		* 
											FROM 		'.VENUS_IMAGES.' 
											WHERE 		(category = %s) 
											ORDER BY 	last_modified
											LIMIT 		'.$dbc->_db->quote($offset).','.$dbc->_db->quote($rowsPerPage).'', $dbc->_db->quote($category)));

		$a = $dbc->_db->fetch_array($r);

		while($i < $max_images_box) {
			$images .= '<div class="news_cat_box">';

			while($a) {
			/*			
			$thumb=ORBX_SITE_URL.'/site/venus/thumbs/'.$a['permalink'];
			if (isset($thumb)) {
				$images .= '<div class="news_cat_box_preview" style="margin: 0 1px; width:'.$css_width.'%;"><a href="' . $thumb.'"><div style="background: #cccccc;">'.substr($a['title'], 0, 20).'...</div><img style="overflow:auto; padding: 3px; border: 1px solid black;" src="' . $thumb.'"/></a><br />
				'.get_file_size(DOC_ROOT.'/site/venus/'.$a['permalink']).'<br />'.date('d.m.Y', $a['uploader_time']).'</div>';
				$a = $dbc->_db->fetch_array($r);
				unset($thumb);
			} else {
			*/
			
				$thumb_img = (is_file(DOC_ROOT . '/site/venus/thumbs/t-'.$a['permalink'])) ? '<img style="width:33%;" class="thumb_image" src="'.ORBX_SITE_URL.'/site/venus/thumbs/t-'.$a['permalink'].'" alt="'.$a['permalink'].'" title="'.$a['permalink'].'" />' : '<img src="'.ORBX_SITE_URL.'/site/venus/'.$a['permalink'].'" class="thumb_image" alt="'.$a['permalink'].'" title="'.$a['permalink'].'" style="width:33%;" />';
			
				$images .= '<div class="news_cat_box_preview" style="margin: 0 1px; width:'.$css_width.'%;"><a href="' . ORBX_SITE_URL . '/site/venus/' . $a['permalink'].'"><div style="background: #cccccc;">'.substr($a['title'], 0, 20).'...</div>' . $thumb_img . '</a><br />
				'.get_file_size(DOC_ROOT.'/site/venus/'.$a['permalink']).'<br />'.date('d.m.Y', $a['uploader_time']).'</div>';
				$a = $dbc->_db->fetch_array($r);
			/*
			}
			*/
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
		$br="<h1>ksdkkdfjkdfjkf</h1>";
		return $br;
		
	}
?>