<?php
/**
 * Attila search engine
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemFE
 * @version 1.1
 * @link http://
 * @license http://
 * @since 2006-10-16
 */

define('ATTILA_DISPLAY_SPONSORED', 	false);
define('ATTILA_HILITE_RESULTS', 	false);
define('ATTILA_ALGORITHM_PHP', 		1);			// better support for international languages, slower
define('ATTILA_ALGORITHM_SQL', 		2);			// bad support for international languages, faster

class Attila
{
	var $algorithm;
	var $sourceid;
	var $query;
	var $use_calc;
	var $clean_query;
	var $query_permalink;
	var $search_results;
	var $result_feedback;
	var $sponsored_link;
	var $titles;
	var $process_time;
	var $max_search_results;
	var $atl_start_display;
	var $category;

	function Attila()
	{
		$this->__construct();
	}

	function __construct()
	{
		$this->algorithm = ATTILA_ALGORITHM_PHP;
	}

	function atl_calc()
	{
		if(!isset($starttime)) {
			$starttime = explode(' ', microtime());
			$starttime = $starttime[1] + $starttime[0];
		}
		// * calc start
		$this->use_calc = false;
		$calc_operators = array('+', '-', '*', '/', '%', 'pi');

		foreach($calc_operators as $value) {
			if($this->use_calc == 0) {
				$this->use_calc = (strpos(strtolower($_GET['q']), $value) !== false) ? 1 : 0;
				$math_operation = trim(strip_tags(strtolower($_GET['q'])));
			}
		}

		// let's now see if stripped of operators and other chars matches an int or float
		$math_operation_sanity_check = str_replace($calc_operators, '', $math_operation);
		$math_operation_sanity_check = str_replace(array('.', ',', '(', ')', ' '), '', $math_operation_sanity_check);

		if(is_numeric($math_operation_sanity_check)) {
			$this->use_calc ++;
		}
		//echo "<!-- $math_operation_sanity_check|$this->use_calc -->";
		if($this->use_calc == 2) {
			$math_subs = array(
				'pi'			=> M_PI
				);

			$math_subs_removal = array_keys($math_subs);
			$math_subs_replacement = array_values($math_subs);

			$math_operation = str_replace($math_subs_removal, $math_subs_replacement, $math_operation);

			$this->search_results = eval("return ($math_operation);");

			// finish the process time
			$mtime = explode(' ', microtime());
			$totaltime = $mtime[0] + $mtime[1] - $starttime;
			$this->process_time = $totaltime;
			// fill out vars
			$this->clean_query = $math_operation;
			$this->result_feedback = _L('math_results')." : <strong>$this->clean_query</strong>";
		}
		// * calc end
	}

	function atl_run($user_query = '', $source = '', $category = '')
	{
		global $dbc, $orbicon_x, $orbx_mod;

		$_POST['sourceid'] = ($source == '') ? $_POST['sourceid'] : $source;
		$this->sourceid = $_POST['sourceid'];

		$_POST['category'] = ($category == '') ? $_POST['category'] : $category;
		$this->category = $_POST['category'];

		$starttime = explode(' ', microtime());
		$starttime = $starttime[1] + $starttime[0];

		$_GET['q'] = ($user_query == '') ? $_GET['q'] : $user_query;
		$this->atl_start_display = (isset($_GET['pp']) && isset($_GET['p'])) ? (intval($_GET['pp'] * $_GET['p']) - $_GET['pp']) : 0;

		$this -> atl_calc();

		if($this->use_calc != 2) {
			$this->query = trim(strip_tags($_GET['q']));

			$this->clean_query = $this->query;
			$this->query = utf8_html_entities(str_replace(array('?', '!'), '', $this->query));
			$this->query_permalink = get_permalink($this->clean_query);

			$this->query = explode(' ', $this->query);

			foreach($this->query as $value)	{
				if(ATTILA_DISPLAY_SPONSORED) {
					$sponsored = strtolower($value);
					/*if($sponsored == 'web' || $sponsored == 'design'
					|| $sponsored == 'css' || $sponsored == 'dizajn'
					|| $sponsored == 'html')
					{
						$this->sponsored_link[] = array(
									'href' => 'tutor.blog.hr',
									'title' => 'Tutor.Blog',
									'desc' => 'HTML i CSS tutoriali na hrvatskom jeziku - naučite kako dizajnirati svoje web stranice. Prilagođeno za potpune početnike.');
					}*/
				}

				$temp[] = $value;
			}

			if(ATTILA_DISPLAY_SPONSORED) {
				$this->sponsored_link = (!empty($this->sponsored_link)) ? array_unique($this->sponsored_link) : null;
			}

			$this->query = (count($temp) > 1) ? implode(' ', $temp) : $temp[0];
			$this->query = str_replace(array('?', ',', ':', '!', '.', '-', '"', '\''), ' ', $this->query);

			$this->titles = '';
			$this->search_results = '';

			if(!empty($this->query)) {

				$cache_results = $dbc->_db->get_cache('SELECT attila_attila_attila ' . $this->query . $this->atl_start_display);
				if($cache_results !== null) {
					return $cache_results;
				}

				// permalink match
				if($orbx_mod->validate_module('news')) {
					$sQuery = sprintf('	SELECT 		*
										FROM 		'.TABLE_NEWS.'
										WHERE 		(permalink LIKE %s) AND
													(live = 1) AND
													(language = %s)
										LIMIT 		1',
										$dbc->_db->quote('%'.$this->query_permalink.'%'), $dbc->_db->quote($orbicon_x->ptr));

					$rResult = $dbc->_db->query($sQuery);
					$aArticle = $dbc->_db->fetch_assoc($rResult);

					if(!empty($aArticle['permalink'])) {
						$title_excerpt = $aArticle['title'];

						$url = (empty($aArticle['redirect'])) ? url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$aArticle['permalink'], ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.$aArticle['permalink']) : $aArticle['redirect'];

						$this->search_results[$aArticle['permalink']] = array(
													'permalink' => $aArticle['permalink'],
													'title' => $title_excerpt,
													'live_time' => $aArticle['date'],
													'type' => 'permalink-match-news',
													'href' => $url,
													'freq' => 999999999.0);
					}
				}
				// try columns

				// permalink match
				$sQuery = sprintf('	SELECT 		*
									FROM 		'.TABLE_COLUMNS.'
									WHERE 		(permalink LIKE %s) AND
												(menu_name != \'box\') AND
												(language = %s)
									LIMIT 		1',
									$dbc->_db->quote('%'.$this->query_permalink.'%'), $dbc->_db->quote($orbicon_x->ptr));
				$rResult = $dbc->_db->query($sQuery);
				$aArticle = $dbc->_db->fetch_assoc($rResult);

				if(!empty($aArticle['permalink'])) {
					$title_excerpt = $aArticle['title'];
					$url = (empty($aArticle['redirect'])) ? url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$aArticle['permalink'], ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.$aArticle['permalink']) : $aArticle['redirect'];

					$this->search_results[$aArticle['permalink']] = array(
												'permalink' => $aArticle['permalink'],
												'title' => $title_excerpt,
												'live_time' => $aArticle['lastmod'],
												'type' => 'permalink-match-columns',
												'href' => $url,
												'excerpt' => null,
												'freq' => 999999999.0);
				}


				// documents (doc, pdf)
				$sQuery = sprintf('	SELECT 		*,
									MATCH 		(search_index)
									AGAINST 	(%s)
									AS 			freq
									FROM 		'.MERCURY_FILES.'
									WHERE 		(live = 1) AND
												MATCH(search_index)
									AGAINST 	(%s IN BOOLEAN MODE) AND
												(search_index != \'\')
									ORDER BY 	freq DESC',
									$dbc->_db->quote($this->query_permalink),
									$dbc->_db->quote($this->query_permalink));

				$rResult = $dbc->_db->query($sQuery);
				$aArticle = $dbc->_db->fetch_assoc($rResult);

				while($aArticle) {
					$title = ($aArticle['title'] == '') ? $aArticle['content'] : $aArticle['title'];
					$url = ORBX_SITE_URL . '/site/mercury/' . $aArticle['content'];

					$this->search_results[$aArticle['permalink']] = array(
												'permalink' => $aArticle['permalink'],
												'title' => $title,
												'live_time' => $aArticle['live_time'],
												'type' => 'permalink-match-documents',
												'href' => $url,
												'excerpt' => null,
												'freq' => floatval($aArticle['freq']));
					$aArticle = $dbc->_db->fetch_assoc($rResult);
				}
				// answers
				$this->_get_db_results();

				// titles
				if($orbx_mod->validate_module('news')) {
					$sQuery = sprintf('	SELECT 	title
										FROM 	'.TABLE_NEWS.'
										WHERE 	(live = 1) AND
												(language = %s)', $dbc->_db->quote($orbicon_x->ptr));

					$rResult = $dbc->_db->query($sQuery);
					$aArticle = $dbc->_db->fetch_assoc($rResult);

					while($aArticle) {
						$this->titles .= $aArticle['title'].' ';
						$aArticle = $dbc->_db->fetch_assoc($rResult);
					}

					$dbc->_db->free_result($rResult);
				}

				$sQuery = sprintf('SELECT 	title
									FROM 	'.TABLE_COLUMNS.'
									WHERE 	(redirect = \'\') AND
											(menu_name != \'box\') AND
											(language = %s)',
				$dbc->_db->quote($orbicon_x->ptr));

				$rResult = $dbc->_db->query($sQuery);
				$aArticle = $dbc->_db->fetch_assoc($rResult);

				while($aArticle) {
					$this->titles .= $aArticle['title'].' ';
					$aArticle = $dbc->_db->fetch_assoc($rResult);
				}
				$dbc->_db->free_result($rResult);
			}

			$this->max_search_results = count($this->search_results);
			$this->atl_find_similar();

			$mtime = explode(' ', microtime());
			$totaltime = $mtime[0] + $mtime[1] - $starttime;
			$this->process_time = $totaltime;

			if(ATTILA_HILITE_RESULTS) {
				$this->atl_hilite_results();
			}
			$this->__atl_sort_results();
		}

		$related = $this->atl_return_related();
		if($related != -1) {
			return $related;
		}

		$results = $this->__atl_display_search_results();

		// attila gets higher frequency caching, so we'll "fake" the query for it
		$dbc->_db->put_cache($results, 'SELECT attila_attila_attila ' . $this->query);

		return $results;
	}

	function atl_find_similar()
	{
		global $orbicon_x;
		$min_results_text = ($this->max_search_results > 10) ? ($this->atl_start_display + 1) : 1;
		$max_results_text = ($this->max_search_results > 10) ? ($this->atl_start_display + 10) : $this->max_search_results;
		$max_results_text = ($max_results_text > $this->max_search_results) ? $this->max_search_results : $max_results_text;

		if(empty($this->search_results)) {
			$this->titles = str_replace(array('?', ',', ':', '!', '.', '-','"','\'','/','$'), ' ', $this->titles);

			// array of words to check against
			$words = explode(' ', $this->titles);

			// no shortest distance found, yet
			$shortest = -1;
			// loop through words to find the closest
			foreach($words as $word) {
				$word = trim($word);

				if(!empty($word)) {
					// calculate the distance between the input word,
					// and the current word
					$lev = levenshtein($this->query, $word);

					// check for an exact match
					if($lev == 0) {
						// closest word is this one (exact match)
						$closest = $word;
						$shortest = 0;

						// break out of the loop; we've found an exact match
						break;
					}

					// if this distance is less than the next found shortest
					// distance, OR if a next shortest word has not yet been found
					if($lev <= $shortest || $shortest < 0) {
						// set the closest match, and shortest distance
						$closest = $word;
						$shortest = $lev;
					}
				}
			}

			if($shortest == 0) {
				$this->result_feedback = _L('search_results').": $min_results_text - $max_results_text "._L('from')." $this->max_search_results "._L('for').' '._L('query')."<strong>$this->clean_query</strong>";
			}
			else {
				if(!empty($closest))
					$url = ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=attila&amp;q='.$closest;
					$this->result_feedback = _L('did_you_mean')." <a href=\"$url\"><strong>$closest</strong></a>?";
			}
		}
		else {
			$this->result_feedback = _L('search_results').": $min_results_text - $max_results_text "._L('from')." $this->max_search_results "._L('for').' '._L('query')." <strong>$this->clean_query</strong>";
		}
	}

	function atl_hilite_results()
	{
		if(!empty($this->search_results)) {
			// * hilite results
			$q_search = explode(' ', $this->query);

			foreach($q_search as $q_value) {
				$q_value = trim($q_value);

				if(!empty($q_value) && (strlen($q_value) > 1)) {
					foreach($this->search_results as $key => $value) {
						// * prepare all combinations of $q_value
						$q_value_variations = array($q_value, strtolower($q_value), strtoupper($q_value), ucfirst(strtolower($q_value)));

						$this->search_results[$key]['title'] = $value['title'];
						$this->search_results[$key]['excerpt'] = $value['excerpt'];

						foreach($q_value_variations as $q_value_variation) {
							$this->search_results[$key]['title'] = str_replace($q_value_variation, "<span style=\"background:#ffff88;\">$q_value_variation</span>", $this->search_results[$key]['title']);

							if(!empty($this->search_results[$key]['excerpt'])) {
								$this->search_results[$key]['excerpt'] = str_replace($q_value_variation, "<span style=\"background:#ffff88;\">$q_value_variation</span>", $this->search_results[$key]['excerpt']);
							}
						}
					}
				}
			}
		}
	}

	function __atl_sort_results()
	{
		if($this->use_calc != 2 && count($this->search_results) > 1) {
			// sort them by frequency
			foreach($this->search_results as $key => $value) {
				$sort_by[$key] = floatval($value['freq']);
			}
			array_multisort($sort_by, SORT_DESC, $this->search_results);
		}

		if(is_array($this->search_results)) {
			$this->search_results = array_slice($this->search_results, $this->atl_start_display, 10);
		}
	}

	function atl_return_related()
	{
		// return related search results and exit
		if($this->sourceid == 'related') {
			if(is_array($this->search_results[$this->query_permalink])) {
				unset($this->search_results[$this->query_permalink]);
			}
			$related = '<ol>';
			if(empty($this->search_results)) {
				return false;
				//$related .= '<li>'._L('no_related').'.</li>';
			}
			else {
				foreach($this->search_results as $value) {
					if(!empty($value['permalink'])) {
						$related .= "<li><a href=\"{$value['href']}\">{$value['title']}</a></li>";
					}
				}
			}
			$related .= '</ol>';
			return $related;
		}
		return -1;
	}

	// autocomplete attila box
	function get_suggestions_box()
	{
		// * suggest
		(string) $current_query = $_REQUEST['query'];
		$current_query = trim(get_permalink($current_query));
		$suggestions = array();

		if($current_query != '') {
			global $dbc, $orbicon_x, $orbx_mod;

			if($orbx_mod->validate_module('stats')) {
				$r = $dbc->_db->query(sprintf('	SELECT 		entry
												FROM 		'.TABLE_STATISTICS.'
												WHERE 		(type = \'attila\') AND
															(entry LIKE %s)
												LIMIT		10',
														$dbc->_db->quote("%$current_query%")));
				$a = $dbc->_db->fetch_assoc($r);

				while($a) {
					$suggestions[] = trim(utf8_html_entities(urldecode($a['entry']), true));
					$a = $dbc->_db->fetch_assoc($r);
				}
			}
			else {
				// columns
				$q = sprintf('	SELECT 	title
								FROM 	'.TABLE_COLUMNS.'
								WHERE 	(menu_name != \'box\') AND
										(permalink LIKE %s) AND
										(language = %s)
								LIMIT 	10',
								$dbc->_db->quote("%$current_query%"), $dbc->_db->quote($orbicon_x->ptr));

				$r = $dbc->_db->query($q);
				$a = $dbc->_db->fetch_assoc($r);

				while($a) {
					$suggestions[] = trim(utf8_html_entities($a['title'], true));
					$a = $dbc->_db->fetch_assoc($r);
				}

				// news
				if($orbx_mod->validate_module('news')) {
					$q = sprintf('	SELECT 	title
									FROM 	'.TABLE_NEWS.'
									WHERE 	(live = 1) AND
											(permalink LIKE %s) AND
											(language = %s)
									LIMIT 	10',
									$dbc->_db->quote("%$current_query%"), $dbc->_db->quote($orbicon_x->ptr));

					$r = $dbc->_db->query($q);
					$a = $dbc->_db->fetch_assoc($r);

					while($a) {
						$suggestions[] = trim(utf8_html_entities($a['title'], true));
						$a = $dbc->_db->fetch_assoc($r);
					}
				}
			}

			// make it unique
			if(count($suggestions) > 1) {
				$suggestions = array_unique($suggestions);
			}
		}

		if(empty($suggestions)) {
			return '';
		}

		$suggestions = implode("\n", $suggestions);
		return $suggestions;
	}

	function __atl_display_search_results()
	{
		global $orbicon_x, $orbx_mod;

		if(ATTILA_DISPLAY_SPONSORED) {
			if(!empty($this->sponsored_link)) {
				$results_html .= '<div style="padding-right: 1em;padding-left: 1em;border-right: 1px solid #dee4e5; border-left: 1px solid #dee4e5;"><h4>'._L('sponsored_links').'</h4>';
				foreach($this->sponsored_link as $value) {
					if(!empty($value['href'])) {
						$results_html .= "<blockquote><a title=\"{$value['desc']}\" href=\"http://{$value['href']}/\">{$value['title']}</a><br />";
						// display description
						$results_html .= "<q style=\"font-size: 80%;\">{$value['desc']}</q></blockquote>";
					}
				}
				$results_html .= '</div>';
			}
		}

		$results_html .= "<span style=\"float:right;font-size:90%\">$this->result_feedback</span>"/*.' - <abbr title="'.$this->process_time.'s">'.rounddown($this->process_time, 2).'s</abbr>'*/;
		$results_html .= '<ol>';

		if(empty($this->search_results)) {
			$results_html .= '<li>'._L('no_search_results').' - <span style="font-size: 110%;">'.$this->clean_query.'</span></li>
			<li><a href="http://www.google.com/search?q='.$this->clean_query.'+site%3A'.DOMAIN.'&amp;hl='.$orbicon_x->ptr.'">'._L('try_with_google').'</a></li>';
		}
		// 2 means it's a valid calc query
		else if($this->use_calc == 2) {
			$results_html .= "<h1 id=\"math_result\">$this->search_results</h1>";

			if(is_float($this->search_results)) {
				$results_html .= '<h4><label for="attila_float">'._L('round_down_to').'</label> <select id="attila_float" onchange="javascript: $(\'math_result\').innerHTML = (parseFloat('.$this->search_results.').toFixed(this.options[this.selectedIndex].value));">';
				$float = explode('.', $this->search_results);
				$dec_pts = strlen($float[1]);
				$i = 1;

				while($i <= $dec_pts) {
					$selected = ($i == $dec_pts) ? 'selected="selected"' : '';
					$results_html .= "<option $selected value=\"$i\">$i</option>";
					$i ++;
				}

				$results_html .= '</select> <label for="attila_float">'._L('decimal_place').'.</label></h4>';
			}
		}
		else {


			// log statistics if found
			if($orbx_mod->validate_module('stats') && $_SESSION['site_settings']['stats_attila']) {
				include_once DOC_ROOT . '/orbicon/modules/stats/class.stats.php';
				$stats = new Statistics();
				$stats->log_attila_search_keywords($this->query);
				if($_SESSION['user.r']['id'] && $this->query) {
					$stats->log_personal_search($this->query);
				}
				$stats = null;
			}


			foreach($this->search_results as $value) {
				if(!empty($value['permalink'])) {
					//$style = ($this->query_permalink == $value['permalink']) ? 'style="font-size:120%;font-weight:bold;"' : '';
					$style = 'style="font-size:120%;font-weight:bold;"';
					$results_html .= "<li><p class=\"attila_p1\"><a $style href=\"{$value['href']}\">{$value['title']}</a></p>";
					// display excerpt
					if(!empty($value['excerpt']) && $value['type'] == 'content-match') {
						$results_html .= "<p class=\"attila_p2\">...{$value['excerpt']}...</p>";
					}

					while (stripos($value['href'], '%') !== false) {
						$value['href'] = urldecode($value['href']);
					}

					$results_html .= '<p class="attila_p3">'.$value['href'].'</p>';
					$results_html .= '</li>';
				}
			}
		}
		$results_html .= '</ol>';

		// footer for more search results

		require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

		if(!isset($_GET['p'])) {
			$unset_below = true;
		}

		$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 10;
		$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

		$pagination = new Pagination('p', 'pp');
		$pagination->total = $this->max_search_results;

		$pagination->split_pages();

		$results_html .= $pagination->construct_page_nav(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=attila&amp;q=' . $this->clean_query);

		// this invalidates caching, clean up from memory
		if($unset_below) {
			unset($_GET['p'], $_GET['pp']);
		}

		/*if($this->max_search_results > 10) {
			$results_html .= '<p class="orbicon_pagination">';
			$i = 0;
			$max = $this->max_search_results;

			while($max > 0) {
				$results_html .= ($this->atl_start_display == ($i * 10)) ? ($i+1).' | ' : '<a class="bit" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=attila&amp;q='.$this->clean_query.'&amp;start='.($i * 10).'">'.($i+1).'</a> | ';
				$max -= 10;
				$i ++;
			}

			$results_html .= ($this->max_search_results > ($this->atl_start_display + 10)) ? '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=attila&amp;q='.$this->clean_query.'&amp;start='.($this->atl_start_display + 10).'" class="next">'._L('next').' &gt;&gt;</a></p>' : _L('next').' &gt;&gt;</p>';
		}*/

		// powered by mark
		//$results_html .= '<p style="text-align:center;font-size:90%;"><a title="Attila search power" href="mailto:pavle.gardijan@gmail.com">Powered by Attila</a></p>';

		return "<div id=\"searchResults\">$results_html</div>";
	}

	function get_related($input = '')
	{
		if(scan_templates('<!>RELATED_CONTENT') < 1) {
			return false;
		}

		$var['q'] = (empty($input)) ? $_GET['rel'] : $input;
		$var['sourceid'] = 'related';

		$results = strip_tags($this->atl_run($var['q'], $var['sourceid']), '<ul><ol><li><a>');

		if(!empty($results)) {// you can add <strong>'._L('similar_content').'</strong>
			return '<div class="orbx_attila_rel">'.$results.'</div>';
		}
		return false;
	}

	function str_occurrences($haystack, $needle)
	{
		$haystack = explode($needle, $haystack);
		return count($haystack);
	}

	function _get_db_results()
	{
		if($this->algorithm == ATTILA_ALGORITHM_PHP) {
			$this->_scan_db_php();
		}
		else if($this->algorithm == ATTILA_ALGORITHM_SQL) {
			$this->_scan_db_sql();
		}
	}

	function _scan_db_php()
	{
		global $dbc, $orbicon_x, $orbx_mod;
		// answers
		if($_SESSION['site_settings']['inword_search']) {
			$sQuery = sprintf('	SELECT 		*
								FROM 		'.MAGISTER_CONTENTS.'
								WHERE 		(UCASE(content) LIKE UCASE(%s)) AND
											(live = 1) AND
											(hidden = 0)',
			$dbc->_db->quote('%' . $this->query . '%'));
		}
		else {
			$sQuery = sprintf('	SELECT 		*
								FROM 		'.MAGISTER_CONTENTS.'
								WHERE 		(
											(UCASE(content) LIKE UCASE(%s)) OR
											(UCASE(content) LIKE UCASE(%s)) OR
											(UCASE(content) LIKE UCASE(%s)) OR
											(UCASE(content) LIKE UCASE(%s)) OR
											(UCASE(content) LIKE UCASE(%s)) OR
											(UCASE(content) LIKE UCASE(%s)) OR
											(UCASE(content) LIKE UCASE(%s)) OR
											(UCASE(content) LIKE UCASE(%s)) OR
											(UCASE(content) LIKE UCASE(%s)) OR
											(UCASE(content) LIKE UCASE(%s)) OR
											(UCASE(content) LIKE UCASE(%s)) OR
											(UCASE(content) LIKE UCASE(%s)) OR
											(UCASE(content) LIKE UCASE(%s))

											) AND
											(live = 1) AND
											(hidden = 0)',
			$dbc->_db->quote('% ' . $this->query . ' %'), 	// space
			$dbc->_db->quote('%,' . $this->query . ' %'), 	// comma start
			$dbc->_db->quote('% ' . $this->query . ',%'), 	// comma end
			$dbc->_db->quote('%.' . $this->query . ' %'), 	// dot start
			$dbc->_db->quote('% ' . $this->query . '.%'), 	// dot end
			$dbc->_db->quote('%;' . $this->query . ' %'), 	// slash dot
			$dbc->_db->quote('% ' . $this->query . ';%'), 	// slash dot
			$dbc->_db->quote('%?' . $this->query . ' %'), 	// question mark
			$dbc->_db->quote('% ' . $this->query . '?%'), 	// question mark
			$dbc->_db->quote('%!' . $this->query . ' %'), 	// exclamation mark
			$dbc->_db->quote('% ' . $this->query . '!%'), 	// exclamation mark
			$dbc->_db->quote('%:' . $this->query . ' %'), 	// double start
			$dbc->_db->quote('% ' . $this->query . ':%') 	// double end

			);
		}

		$rResult = $dbc->_db->query($sQuery);
		$aArticle = $dbc->_db->fetch_assoc($rResult);

		while($aArticle) {
			$aArticle['freq'] = floatval($this->str_occurrences($aArticle['freq'], $this->query));
			$results[] = $aArticle;
			$aArticle = $dbc->_db->fetch_assoc($rResult);
		}

		$dbc->_db->free_result($rResult);

		if(!is_array($results)) {
			return false;
		}

		// Obtain a list of columns
		foreach($results as $key => $row) {
		   $freq[$key] = floatval($row['freq']);
		}

		// Sort the data with volume descending, edition ascending
		// Add $data as the last parameter, to sort by the common key
		array_multisort($freq, SORT_DESC, $results);

		foreach($results as $aArticle) {
			$rResult2 = $dbc->_db->query(sprintf('
											SELECT 		title, live_time,
														permalink
											FROM 		'.MAGISTER_TITLES.'
											WHERE 		(permalink = %s)
											LIMIT 		1',
			$dbc->_db->quote($aArticle['question_permalink'])));

			$aArticle2 = $dbc->_db->fetch_assoc($rResult2);

			$temp_excerpt = null;

			$query_terms = explode(' ', $this->query);

			$excerpt = strip_tags($aArticle['content'], '<br>');
			$excerpt = str_replace(array('<br />', '<br>', '<br/>'), ' ', $excerpt);

			foreach($query_terms as $value) {
				if(strlen($value) > 2) {
					$str = truncate_text(strstr($excerpt, $value), 70, '');
					if(!empty($str)) {
						$temp_excerpt[] = $str;
						break;
					}
				}
			}

			$excerpt = (count($temp_excerpt) > 1) ? implode('<br />', $temp_excerpt) : $temp_excerpt[0];

			if($orbx_mod->validate_module('news')) {
				$sQuery_o = sprintf('	SELECT 		title, permalink, redirect
										FROM 		'.TABLE_NEWS.'
										WHERE 		(UCASE(content) = UCASE(%s)) AND
													(language = %s)
										LIMIT 		1', $dbc->_db->quote($aArticle['question_permalink']), $dbc->_db->quote($orbicon_x->ptr));
				$r_o = $dbc->_db->query($sQuery_o);
				$a_o = $dbc->_db->fetch_assoc($r_o);
			}

			if(empty($a_o['permalink'])) {
				$sQuery_o = sprintf('	SELECT 		title, permalink,
													menu_name, redirect
										FROM 		'.TABLE_COLUMNS.'
										WHERE 		(menu_name != \'box\') AND
													(UCASE(content) = UCASE(%s)) AND
													(language = %s)
										LIMIT 1', $dbc->_db->quote($aArticle['question_permalink']), $dbc->_db->quote($orbicon_x->ptr));
				$r_o = $dbc->_db->query($sQuery_o);
				$a_o = $dbc->_db->fetch_assoc($r_o);
			}

			if(!empty($a_o['permalink'])) {

				$url = (empty($a_o['redirect'])) ? url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$a_o['permalink'], ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.$a_o['permalink']) : $a_o['redirect'];

				$this->search_results[$a_o['permalink']] = array(
						'permalink' => $a_o['permalink'],
						'title' => $a_o['title'],
						'live_time' => $aArticle2['live_time'],
						'type' => 'content-match',
						'href' => $url,
						'excerpt' => $excerpt,
						'freq' => floatval($aArticle['freq']));
			}
		}
	}

	function _scan_db_sql()
	{
		global $dbc, $orbicon_x, $orbx_mod;

		// answers
		$sQuery = sprintf('	SELECT 		*,
							MATCH 		(content)
							AGAINST 	(%s)
							AS 			freq
							FROM 		'.MAGISTER_CONTENTS.'
							WHERE 		MATCH(content)
							AGAINST 	(%s IN BOOLEAN MODE) AND
										(live = 1) AND
										(hidden = 0)
							ORDER BY 	freq DESC', $dbc->_db->quote($this->query), $dbc->_db->quote($this->query));

		$rResult = $dbc->_db->query($sQuery);
		$aArticle = $dbc->_db->fetch_array($rResult);

		while($aArticle) {
			$rResult2 = $dbc->_db->query(sprintf('
											SELECT 		title, live_time, permalink
											FROM 		'.MAGISTER_TITLES.'
											WHERE 		(permalink = %s)
											LIMIT 		1', $dbc->_db->quote($aArticle['question_permalink'])));
			$aArticle2 = $dbc->_db->fetch_assoc($rResult2);

			$temp_excerpt = null;

			$query_terms = explode(' ', $this->query);

			$excerpt = strip_tags($aArticle['content'], '<br>');
			$excerpt = str_replace(array('<br />', '<br>', '<br/>'), ' ', $excerpt);

			foreach($query_terms as $value) {
				if(strlen($value) > 2) {
					$str = truncate_text(strstr($excerpt, $value), 70, '');
					if(!empty($str)) {
						$temp_excerpt[] = $str;
						break;
					}
				}
			}

			$excerpt = (count($temp_excerpt) > 1) ? implode('<br />', $temp_excerpt) : $temp_excerpt[0];

			if($orbx_mod->validate_module('news')) {
				$sQuery_o = sprintf('	SELECT 		title, permalink, redirect
										FROM 		'.TABLE_NEWS.'
										WHERE 		(content = %s) AND
													(language = %s)
										LIMIT 		1', $dbc->_db->quote($aArticle['question_permalink']), $dbc->_db->quote($orbicon_x->ptr));
				$r_o = $dbc->_db->query($sQuery_o);
				$a_o = $dbc->_db->fetch_assoc($r_o);
			}

			if(empty($a_o['permalink'])) {
				$sQuery_o = sprintf('	SELECT 	title, permalink, menu_name, redirect
										FROM 	'.TABLE_COLUMNS.'
										WHERE 	(menu_name != \'box\') AND
												(content = %s) AND
												(language = %s)
										LIMIT 	1', $dbc->_db->quote($aArticle['question_permalink']), $dbc->_db->quote($orbicon_x->ptr));
				$r_o = $dbc->_db->query($sQuery_o);
				$a_o = $dbc->_db->fetch_assoc($r_o);
			}

			if(!empty($a_o['permalink'])) {

				$url = (empty($a_o['redirect'])) ? url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$a_o['permalink'], ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.$a_o['permalink']) : $a_o['redirect'];

				$this->search_results[$a_o['permalink']] = array(
						'permalink' => $a_o['permalink'],
						'title' => $a_o['title'],
						'live_time' => $aArticle2['live_time'],
						'type' => 'content-match',
						'href' => $url,
						'excerpt' => $excerpt,
						'freq' => floatval($aArticle['freq']));
			}

			$aArticle = $dbc->_db->fetch_assoc($rResult);
		}

		$dbc->_db->free_result($rResult);
	}
}

?>