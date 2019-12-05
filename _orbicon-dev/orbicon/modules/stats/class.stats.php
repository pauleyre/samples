<?php

/**
 * Statistics class
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @subpackage Statistics
 * @version 1.4
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-11-12
 */

class Statistics
{
	var $_current_month;
	var $_current_time;

	function statistics()
	{
		$this->__construct();
	}

	function __construct()
	{
		$this->_current_month = $this->_get_current_month();
		$this->_current_time = time();
	}

	// * daily session counter
	function orbicon_session_counter()
	{
		// if we're coming from a local network into production, ignore it

		if(!valid_public_ip(ORBX_CLIENT_IP) && ($_SESSION['site_settings']['syncm_type'] == SYNC_MANAGER_TYPE_RECEIVER)) {
			return;
		}

		// ignore search bots
		if(!get_is_search_engine_bot() && !get_is_w3c_validator()) {
			// ignore some stats if already recorded
			if(!$_SESSION['orbicon_session_stat']) {
				$current_day = date('d', $this->_current_time);
				$numberDays = date('t', $this->_current_month);
				$days = array();

				global $dbc;
				if($_SESSION['site_settings']['stats_sess']) {
					// check if we entered a new month
					$q = sprintf('	SELECT 	*
									FROM 	'.TABLE_STATISTICS.'
									WHERE 	(date = %s) AND
											(type=\'visits\')', $dbc->_db->quote($this->_current_month));
					$r = $dbc->_db->query($q);
					$a = $dbc->_db->fetch_assoc($r);

					// we entered a new month, fill in the details
					if(empty($a['id'])) {
						$i = 1;
						while($i <= $numberDays) {
							$days[] = ($current_day == $i) ? "$i:1" : "$i:0";
							$i ++;
						}

						$days = implode('|', $days);

						$q = sprintf('	INSERT INTO 	'.TABLE_STATISTICS.'
														(entry, date, type)
										VALUES 			(%s, %s, \'visits\')',
						$dbc->_db->quote($days), $dbc->_db->quote($this->_current_month));

						$dbc->_db->query($q);
					}
					// increase stats for current day
					else {
						$days = explode('|', $a['entry']);
						$new_stats = array();

						foreach($days as $value) {
							$day = explode(':', $value);
							$new_stats[] = ($current_day == $day[0]) ? $day[0].':'.($day[1]+1) : "$day[0]:$day[1]";
						}

						$new_stats = implode('|', $new_stats);
						$q = sprintf('	UPDATE 	'.TABLE_STATISTICS.'
										SET 	entry = %s
										WHERE 	(date = %s) AND
												(type=\'visits\')', $dbc->_db->quote($new_stats), $dbc->_db->quote($this->_current_month));
						$dbc->_db->query($q);
					}
				}

				if($_SESSION['site_settings']['stats_hourly']) {
					$this->log_hourly_visits();
				}

				if($_SESSION['site_settings']['stats_ip']) {
					$this->log_unique_ip();
				}

				if($_SESSION['site_settings']['stats_country']) {
					$this->log_country();
				}

				$_SESSION['orbicon_session_stat'] = true;
			}

			$this->log_refer();

			if($_SESSION['site_settings']['stats_content']) {
				$this->log_content();
			}
		}
	}

	function log_unique_ip()
	{
		$current_day = date('d', $this->_current_time);
   		$numberDays = date('t', $this->_current_month);
		$ip = ORBX_CLIENT_IP;

		global $dbc;
		// check if we entered a new month
		$q = sprintf('	SELECT 	*
						FROM 	'.TABLE_STATISTICS.'
						WHERE 	(date = %s) AND
								(type=\'unique_ip\')',
								$dbc->_db->quote($this->_current_month));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		if(empty($a['id']) || !$r) {
			$i = 1;
			while($i <= $numberDays) {
				$days[] = ($current_day == $i) ? "$i:$ip" : "$i:";
				$i ++;
			}

			$days = implode('|', $days);

			$q = sprintf('	INSERT INTO 	'.TABLE_STATISTICS.'
											(entry, date,
											type)
							VALUES 			(%s, %s,
											\'unique_ip\')',
			$dbc->_db->quote($days), $dbc->_db->quote($this->_current_month));
			$dbc->_db->query($q);
		}
		else {
			$days = explode('|', $a['entry']);

			foreach($days as $value) {
				$day = explode(':', $value);
				$ips = explode(',', $day[1]);

				if(!in_array($ip, $ips) && $current_day == $day[0]) {
					$ips[] = $ip;
				}

				$ips = implode(',', $ips);

				$new_stats[] = "$day[0]:$ips";
			}

			$new_stats = implode('|', $new_stats);
			$q = sprintf('	UPDATE 		'.TABLE_STATISTICS.'
							SET 		entry = %s
							WHERE 		(date = %s) AND
										(type=\'unique_ip\')',
			$dbc->_db->quote($new_stats), $dbc->_db->quote($this->_current_month));
			$dbc->_db->query($q);
		}
	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function log_refer()
	{
		$refer = trim($_SERVER['HTTP_REFERER']);
		$refer_parsed = parse_url($refer);

		// these are all invalid
		if(($refer_parsed['host'] == '') ||
		!$refer_parsed ||
		($refer_parsed['host'] == DOMAIN) ||
		($refer_parsed['host'] == $_SERVER['SERVER_NAME']) ||
		($refer_parsed['host'] == DOMAIN_NO_WWW)) {
			return false;
		}

		if($_SESSION['site_settings']['stats_refer']) {
			global $dbc;
			$q = sprintf('	INSERT INTO 	'.TABLE_STATISTICS.'
											(entry, date,
											type)
							VALUES 			(%s, %s,
											\'refer\')',
			$dbc->_db->quote(urlencode($refer)), $dbc->_db->quote($this->_current_month));
			$dbc->_db->query($q);
		}

		if($_SESSION['site_settings']['stats_keyword']) {
			$this->log_search_keywords($refer_parsed['query']);
		}
		return true;
	}

	function log_content()
	{
		// 2.2.2007, Pavle Gardijan
		// Fixed bug with deep paths
		$content = $_SERVER['REQUEST_URL'];
		$content = trim(str_replace(ORBX_SITE_URL, '', $content));

		if($content == '') {
			trigger_error('log_content() expects parameter 1 to be non-empty', E_USER_WARNING);
			return false;
		}

		return sql_insert('	INSERT INTO 	'.TABLE_STATISTICS.'
											(entry, date,
											type)
							VALUES 			(%s, %s,
											\'content\')',
		array($content, $this->_current_month));
	}

	function log_search_keywords($search_query)
	{
		$search_query = trim($search_query);

		if($search_query == '') {
			trigger_error('log_search_keywords() expects parameter 1 to be non-empty', E_USER_WARNING);
			return false;
		}

		// various query vars used by search engines
		$queries = array('q', 'p', 'sp-q', 'terms', 'query', 'key', 'qkw', 'kw', 'qs', 'encquery', 'qt', 'wd', 'w', 'text', 's', 'rdata');
		$query_found = false;

		// create a refer $_GET for comparing
		$search_query = explode('&', $search_query);

		foreach($search_query as $value) {
			$param = explode('=', $value);
			$refer_get[$param[0]] = $param[1];
		}

		// search for a match
		foreach($queries as $value) {
			if(!$query_found) {
				if(strlen($refer_get[$value]) > 0) {
					$query_found = true;
					$new_query = trim($refer_get[$value]);
					break;
				}
			}
		}

		if(!empty($new_query)) {
			global $dbc;

			$q = sprintf('	INSERT INTO 	'.TABLE_STATISTICS.'
											(entry, date,
											type)
							VALUES 			(%s, %s,
											\'keyword\')',
			$dbc->_db->quote($new_query), $dbc->_db->quote($this->_current_month));

			$dbc->_db->query($q);
		}
		return true;
	}

	function get_daily_visits_stats()
	{
     	$numberDays = date('t', $this->_current_month);
		$i = 1;
		$xml = "<graph xAxisName='"._L('day')."' yAxisName='"._L('graph_visits')."' caption='"._L('monthly_visits')."' subcaption='".sprintf(_L('for_month'), date('m', $this->_current_time)).'., '.date('Y', $this->_current_time)."' numdivlines='9' lineThickness='4' showValues='0' numVDivLines='22' formatNumberScale='0' rotateNames='0' decimalPrecision='0' anchorRadius='6' anchorBgAlpha='50' showAlternateVGridColor='1' anchorAlpha='100' animation='0' limitsDecimalPrecision='0' divLineDecimalPrecision='0'>
<categories >";

		while($i <= $numberDays) {
			$xml .= "<c n='$i' />";
			$i ++;
		}

		$xml .= "</categories><dataset seriesName='"._L('uniq_sess')."' color='808080' anchorBorderColor='808080'>";

		// get stats
		global $dbc;
		$q = sprintf('	SELECT 	entry
						FROM 	'.TABLE_STATISTICS.'
						WHERE 	(date = %s) AND
								(type=\'visits\')',
								$dbc->_db->quote($this->_current_month));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		$days = explode('|', $a['entry']);

		foreach($days as $value) {
			$day = explode(':', $value);
			$xml .= "<s v='$day[1]' />";
		}

		$xml .= "</dataset><dataset seriesName='"._L('uniq_ip')."' color='B9EF5C' anchorBorderColor='B9EF5C'>";

		// unique ips
		$q = sprintf('	SELECT 		entry
						FROM 		'.TABLE_STATISTICS.'
						WHERE 		(date = %s) AND
									(type=\'unique_ip\')',
									$dbc->_db->quote($this->_current_month));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		$days = explode('|', $a['entry']);

		foreach($days as $value) {
			// give us ips
			$day = explode(':', $value);
			// get the number of ips
			$ips = substr_count($day[1], ',');
			// add one if not empty to get the accurate number
			$ips = (($ips == 0) && empty($day[1])) ? 0 : ($ips + 1);
			$xml .= "<s v='$ips' />";
		}

		$xml .= '</dataset></graph>';

		unset($days, $day, $ips);
		return $xml;
	}

	function get_top_refers($return_unformatted = false)
	{
		$refers = ($return_unformatted) ? -1 : $this->_get_summary_stats('summary_refers');

		if(!is_array($refers)) {
			$refers = array();
			global $dbc;
			$r = $dbc->_db->query(sprintf('	SELECT 		entry
											FROM 		'.TABLE_STATISTICS.'
											WHERE 		(type = \'refer\') AND
														(date = %s)',
														$dbc->_db->quote($this->_current_month)));
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {
				$refers[$a['entry']] += 1;
				$a = $dbc->_db->fetch_assoc($r);
			}

			$dbc->_db->free_result($r);

			if(!is_array($refers)) {
				return null;
			}

			natsort($refers);
			$refers = array_reverse($refers);

			if($return_unformatted) {
				return $refers;
			}
		}

		$refers = (!isset($_GET['expand_refers'])) ? array_slice($refers, 0, 10) : $refers;
		$i = 1;

		foreach($refers as $key => $value) {
			$class = (($i % 2) == 0) ? ' style="background:#ffffff;"' : '';
			// Pavle Gardijan 31/1/2007
			// double decode for utf-8 content
			$key = urldecode(urldecode($key));

			$table .= "<tr$class>
				<td>$i.</td>
				<td><div style=\"overflow:hidden;\"><a target=\"_blank\" href=\"$key\">$key</a></div></td>
				<td>$value</td>
			</tr>";
			$i ++;
		}

		return $table;
	}

	function get_top_content($return_unformatted = false)
	{
		$content = ($return_unformatted) ? -1 : $this->_get_summary_stats('summary_content');

		if(!is_array($content)) {
			$content = array();
			global $dbc;
			$r = $dbc->_db->query(sprintf('	SELECT 	entry
											FROM 	'.TABLE_STATISTICS.'
											WHERE 	(type = \'content\') AND
													(date = %s)', $dbc->_db->quote($this->_current_month)));
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {
				$content[$a['entry']] += 1;
				$a = $dbc->_db->fetch_assoc($r);
			}

			$dbc->_db->free_result($r);

			natsort($content);
			$content = array_reverse($content);

			if($return_unformatted) {
				return $content;
			}
		}

		$content = (!isset($_GET['expand_content'])) ? array_slice($content, 0, 10) : $content;
		$i = 1;

		foreach($content as $key => $value) {
			$class = (($i % 2) == 0) ? ' style="background:#ffffff;"' : '';
			// Pavle Gardijan 31/1/2007
			// double decode for utf-8 content
			$key = urldecode(urldecode($key));
			$a_style = (strpos(strtolower($key), '=orbicon') === false) ? 'color:#000000;font-weight:bold;' : 'color:#666666;font-weight:normal;';
			$table .= "<tr$class>
				<td>$i.</td>
				<td><div style=\"overflow:hidden;\"><a style=\"$a_style\" href=\"".ORBX_SITE_URL.$key."\">$key</a></div></td>
				<td>$value</td>
			</tr>";
			$i ++;
		}

		return $table;
	}

	function get_top_keywords($return_unformatted = false)
	{
		$keyword = ($return_unformatted) ? -1 : $this->_get_summary_stats('summary_keywords');

		if(!is_array($keyword)) {
			$keyword = array();
			global $dbc;
			$r = $dbc->_db->query(sprintf('	SELECT 		entry
											FROM 		'.TABLE_STATISTICS.'
											WHERE 		(type = \'keyword\') AND
														(date = %s)',
														$dbc->_db->quote($this->_current_month)));
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {
				// Pavle Gardijan 31/1/2007
				// decode here to counter double entries
				$keyword[urldecode($a['entry'])] += 1;
				$a = $dbc->_db->fetch_assoc($r);
			}

			$dbc->_db->free_result($r);

			if(!is_array($keyword)) {
				return null;
			}

			natsort($keyword);
			$keyword = array_reverse($keyword);

			if($return_unformatted) {
				return $keyword;
			}
		}

		$keyword = (!isset($_GET['expand_keywords'])) ? array_slice($keyword, 0, 10) : $keyword;
		$i = 1;

		foreach($keyword as $key => $value) {
			$class = (($i % 2) == 0) ? ' style="background:#ffffff;"' : '';
			// Pavle Gardijan 31/1/2007
			// moved decode up to counter double entries
			$table .= "<tr$class>
				<td>$i.</td>
				<td>$key</td>
				<td>$value</td>
			</tr>";
			$i ++;
		}

		return $table;
	}

	function log_login_history($user, $login = true)
	{
		if(empty($user) || !is_array($user)) {
			return false;
		}

		$log_action = ($login) ? 'in' : 'out';

		$log = sprintf('%s : User %s [#:%s, n:%s %s, s:%s] logged '.$log_action."\n", date('r', $this->_current_time), ORBX_CLIENT_IP, $user['id'], $user['first_name'], $user['last_name'], $user['status']);
		$log = utf8_html_entities($log);

		global $dbc;

		$q = sprintf('INSERT INTO '.TABLE_STATISTICS.'
						(entry, date, type)
						VALUES (%s, %s, \'login_history\')', $dbc->_db->quote($log), $dbc->_db->quote($this->_current_month));
		$dbc->_db->query($q);

		return true;
	}

	function get_login_history()
	{
		global $dbc;

		$q = sprintf('	SELECT 		entry
						FROM 		'.TABLE_STATISTICS.'
						WHERE 		(date = %s) AND
									(type = \'login_history\')
						ORDER BY 	id DESC', $dbc->_db->quote($this->_current_month));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		$stats = '';

		while($a) {
			$stats .= str_replace('&amp;', '&', htmlspecialchars($a['entry']));
			$a = $dbc->_db->fetch_assoc($r);
		}

		return $stats;
	}

	function get_stat_months()
	{
		$months = array();
		// get stats
		global $dbc;
		$q = sprintf('	SELECT 		date
						FROM 		'.TABLE_STATISTICS.'
						WHERE 		(date != %s)
						GROUP BY 	date', $dbc->_db->quote($this->_current_month));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$months[] = $a['date'];
			$a = $dbc->_db->fetch_assoc($r);
		}
		return $months;
	}

	function _get_current_month($get_true_month = false)
	{
		$date = getdate();
		$date = mktime(0, 0, 0, $date['mon'], 1, $date['year']);

		if($get_true_month) {
			return $date;
		}

		if(isset($_GET['range'])) {
			return $_GET['range'];
		}

		$this->_current_month = $date;
		return $this->_current_month;
	}

	function _get_summary_stats($type)
	{
		global $dbc;
		$true_month = $this->_get_current_month(true);

		if($true_month === $this->_current_month) {
			return -1;
		}

		$q = sprintf('	SELECT 		entry
						FROM 		'.TABLE_STATISTICS.'
						WHERE 		(date = %s) AND
									(type = %s)
						LIMIT		1',
									$dbc->_db->quote($this->_current_month), $dbc->_db->quote($type));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);
		unset($q);
		return unserialize($a['entry']);
	}

	function _compress_stats()
	{
		global $dbc;
		$proper_month = $this->_current_month;
		$former_months = $this->get_stat_months();

		if(empty($former_months)) {
			return true;
		}

		foreach($former_months as $month) {
			// check for existing summary
			$q = sprintf('	SELECT 		id
							FROM 		'.TABLE_STATISTICS.'
							WHERE 		(date = %s) AND
										(type LIKE %s)',
										$dbc->_db->quote($month), $dbc->_db->quote('%summary_%'));
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			if(empty($a['id'])) {
				$this->_current_month = $month;
				$keywords = serialize($this->get_top_keywords(true));
				$content = serialize($this->get_top_content(true));
				$refers = serialize($this->get_top_refers(true));
				$countries = serialize($this->get_top_countries(true));
				$attila_keywords = serialize($this->get_top_attila_keywords(true));

				// insert summaries
				sql_insert('	INSERT INTO 	'.TABLE_STATISTICS.'
												(entry, date,
												type)
								VALUES 			(%s, %s,
												\'summary_keywords\')',
												array($keywords, $this->_current_month));

				sql_insert('	INSERT INTO 	'.TABLE_STATISTICS.'
												(entry, date,
												type)
								VALUES 			(%s, %s,
												\'summary_content\')',
												array($content, $this->_current_month));

				sql_insert('	INSERT INTO 	'.TABLE_STATISTICS.'
												(entry, date,
												type)
								VALUES 			(%s, %s,
												\'summary_refers\')',
												array($refers, $this->_current_month));

				sql_insert('INSERT INTO 	'.TABLE_STATISTICS.'
											(entry, date,
											type)
							VALUES 			(%s, %s,
											\'summary_country\')',
											array($countries, $this->_current_month));

				// insert summaries
				sql_insert('	INSERT INTO 	'.TABLE_STATISTICS.'
												(entry, date,
												type)
								VALUES 			(%s, %s,
												\'summary_attila_keywords\')',
										array($attila_keywords, $this->_current_month));

			} // if end
		} // foreach end

		$this->_current_month = $proper_month;

		/* OR (type = \'attila\')*/


		$q_delete = sprintf('	DELETE
								FROM 		'.TABLE_STATISTICS.'
								WHERE 		(date != %s) AND
											(
											(type = \'content\') OR
											(type = \'refer\') OR
											(type = \'keyword\') OR
											(type = \'country\')
								)',
								$dbc->_db->quote($this->_current_month));
		$dbc->_db->query($q_delete);
	}

	function get_country_name($country_code)
	{
		$country_code = strtoupper($country_code);
		include DOC_ROOT . '/orbicon/3rdParty/iplist/countries.php';
		$country_name = $countries[$country_code];

		if($country_name === null){
			$country_name = 'Unknown';
		}

		return $country_name;
	}

	function get_country($ip = ORBX_CLIENT_IP)
	{
		$numbers = explode('.', $ip);
		include DOC_ROOT . '/orbicon/3rdParty/iplist/' . $numbers[0] . '.php';

		$code = ($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);

		foreach($ranges as $key => $value) {
			if($key <= $code) {
				if($ranges[$key][0] >= $code) {
					$country = $ranges[$key][1];
					break;
				}
			}
		}

		if((string) $country === '') {
			return 'A1';
		}

		return $country;
	}

	function log_country()
	{
		$country = $this->get_country();

		if(empty($country)) {
			return false;
		}

		global $dbc;

		$q = sprintf('	INSERT INTO 	'.TABLE_STATISTICS.'
										(entry, date,
										type)
						VALUES 			(%s, %s,
										\'country\')',
		$dbc->_db->quote($country), $dbc->_db->quote($this->_current_month));

		$dbc->_db->query($q);

		return true;
	}

	function get_top_countries($return_unformatted = false)
	{
		$content = ($return_unformatted) ? -1 : $this->_get_summary_stats('summary_country');

		if(!is_array($content)) {
			$content = array();
			global $dbc;
			$r = $dbc->_db->query(sprintf('	SELECT 	entry
											FROM 	'.TABLE_STATISTICS.'
											WHERE 	(type = \'country\') AND
													(date = %s)', $dbc->_db->quote($this->_current_month)));
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {
				$content[$a['entry']] += 1;
				$a = $dbc->_db->fetch_assoc($r);
			}

			$dbc->_db->free_result($r);

			natsort($content);
			$content = array_reverse($content);

			if($return_unformatted) {
				return $content;
			}
		}

		$content = (!isset($_GET['expand_countries'])) ? array_slice($content, 0, 10) : $content;
		$i = 1;

		foreach($content as $key => $value) {
			$class = (($i % 2) == 0) ? ' style="background:#ffffff;"' : '';
			$country_name = $this->get_country_name($key);
			$table .= "<tr$class>
				<td>$i.</td>
				<td><img src=".ORBX_SITE_URL."/orbicon/gfx/flag_icons/$key.gif alt=\"$country_name\" title=\"$country_name\" /> $country_name</td>
				<td>$value</td>
			</tr>";
			$i ++;
		}

		return $table;
	}

	function get_os()
	{
		$os = get_browser(ORBX_USER_AGENT, true);
		return $os['platform'];
	}

	function get_browser_name()
	{
		$os = get_browser(ORBX_USER_AGENT, true);
		return $os['browser'];
	}

	function log_hourly_visits()
	{
		$current_hour = date('G', $this->_current_time);

		global $dbc;
		// check if we entered a new month
		$q = sprintf('	SELECT 	*
						FROM 	'.TABLE_STATISTICS.'
						WHERE 	(date = %s) AND
								(type=\'hourly\')', $dbc->_db->quote($this->_current_month));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);
		$hours = array();

		// we entered a new month, fill in the details
		if(empty($a['id'])) {
			$i = 0;
			while($i <= 23) {
				$hours[] = ($current_hour == $i) ? "$i:1" : "$i:0";
				$i ++;
			}

			$hours = implode('|', $hours);

			$q = sprintf('	INSERT INTO 	'.TABLE_STATISTICS.'
											(entry, date, type)
							VALUES 			(%s, %s, \'hourly\')',
			$dbc->_db->quote($hours), $dbc->_db->quote($this->_current_month));

			$dbc->_db->query($q);
		}
		// increase stats for current day
		else {
			$new_stats = array();
			$hours = explode('|', $a['entry']);

			foreach($hours as $value) {
				$hour = explode(':', $value);
				$new_stats[] = ($current_hour == $hour[0]) ? $hour[0].':'.($hour[1]+1) : "$hour[0]:$hour[1]";
			}

			$new_stats = implode('|', $new_stats);
			$q = sprintf('	UPDATE 	'.TABLE_STATISTICS.'
							SET 	entry = %s
							WHERE 	(date = %s) AND
									(type=\'hourly\')', $dbc->_db->quote($new_stats), $dbc->_db->quote($this->_current_month));
			$dbc->_db->query($q);
		}
	}

	function get_hourly_visits_stats()
	{
		$i = 0;
		$xml = "<graph xAxisName='"._L('hour')."' yAxisName='"._L('graph_visits')."' caption='"._L('hourly_visits')."' subcaption='".sprintf(_L('for_month'), date('m', $this->_current_time)).'., '.date('Y', $this->_current_time)."' numdivlines='9' lineThickness='4' showValues='0' numVDivLines='22' formatNumberScale='0' rotateNames='0' decimalPrecision='0' anchorRadius='6' anchorBgAlpha='50' showAlternateVGridColor='1' anchorAlpha='100' animation='0' limitsDecimalPrecision='0' divLineDecimalPrecision='0'>
<categories >";

		while($i <= 23) {
			$xml .= "<c n='$i' />";
			$i ++;
		}

		$xml .= "</categories><dataset seriesName='"._L('uniq_sess_hour')."' color='808080' anchorBorderColor='808080'>";

		// get stats
		global $dbc;
		$q = sprintf('	SELECT 	entry
						FROM 	'.TABLE_STATISTICS.'
						WHERE 	(date = %s) AND
								(type=\'hourly\')',
								$dbc->_db->quote($this->_current_month));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		$hours = explode('|', $a['entry']);

		foreach($hours as $value) {
			$hour = explode(':', $value);
			$xml .= "<s v='$hour[1]' />";
		}

		$xml .= '</dataset></graph>';

		unset($hours, $hour);
		return $xml;
	}

	function save_stats_settings()
	{
		if(isset($_POST['save_stats_props'])) {
			global $dbc, $orbicon_x;

			$q = '	SELECT 		*
					FROM 		'.TABLE_SETTINGS.'
					WHERE 		(setting=\'stats_sess\')';
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			$settings = array(
				'stats_sess' => intval($_POST['stats_sess']),
				'stats_ip' => intval($_POST['stats_ip']),
				'stats_content' => intval($_POST['stats_content']),
				'stats_refer' => intval($_POST['stats_refer']),
				'stats_country' => intval($_POST['stats_country']),
				'stats_keyword' => intval($_POST['stats_keyword']),
				'stats_hourly' => intval($_POST['stats_hourly']),
				'stats_attila' => intval($_POST['stats_attila'])
			);

			if(empty($a) || !$r) {
				foreach($settings as $key => $value) {
					$q = sprintf('	INSERT
									INTO 	'.TABLE_SETTINGS.'
											(setting, value)
									VALUES 	(%s, %s)',
									$dbc->_db->quote($key), $dbc->_db->quote($value));
					$dbc->_db->query($q);
				}
			}
			else {
				foreach($settings as $key => $value) {
					$q = sprintf('	UPDATE 	'.TABLE_SETTINGS.'
									SET 	value = %s
									WHERE 	(setting = %s)',
												$dbc->_db->quote($value), $dbc->_db->quote($key));
					$dbc->_db->query($q);
				}
			}

			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/stats');
		}
	}

	function get_top_attila_keywords($return_unformatted = false, $preserve_keys = false, $natsort = true, $sql = '')
	{
		$keyword = ($return_unformatted) ? -1 : $this->_get_summary_stats('summary_attila_keywords');

		if(!is_array($keyword)) {
			$keyword = array();
			global $dbc;
			$r = $dbc->_db->query(sprintf('	SELECT 		entry
											FROM 		'.TABLE_STATISTICS.'
											WHERE 		(type = \'attila\') AND
														(date = %s)' . $sql,
														$dbc->_db->quote($this->_current_month)));
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {
				$a['entry'] = is_numeric($a['entry']) ? ' ' . $a['entry'] : $a['entry'];
				// Pavle Gardijan 31/1/2007
				// decode here to counter double entries
				$keyword[urldecode($a['entry'])] += 1;
				$a = $dbc->_db->fetch_assoc($r);
			}

			$dbc->_db->free_result($r);

			if(!is_array($keyword)) {
				return null;
			}

			if($natsort) {
				natsort($keyword);
			}

			$keyword = array_reverse($keyword, $preserve_keys);

			if($return_unformatted) {
				return $keyword;
			}
		}

		$keyword = (!isset($_GET['expand_attila_keywords'])) ? array_slice($keyword, 0, 10) : $keyword;
		$i = 1;

		foreach($keyword as $key => $value) {
			$class = (($i % 2) == 0) ? ' style="background:#ffffff;"' : '';
			// Pavle Gardijan 31/1/2007
			// moved decode up to counter double entries
			$table .= "<tr$class>
				<td>$i.</td>
				<td>$key</td>
				<td>$value</td>
			</tr>";
			$i ++;
		}

		return $table;
	}

	function log_attila_search_keywords($search_query)
	{
		$search_query = trim($search_query);

		if($search_query == '') {
			trigger_error('log_attila_search_keywords() expects parameter 1 to be non-empty', E_USER_WARNING);
			return false;
		}


		sql_insert('	INSERT INTO 	'.TABLE_STATISTICS.'
										(entry, date,
										type)
						VALUES 			(%s, %s,
										\'attila\')', array($search_query, $this->_current_month));

		return true;
	}

	/**
	 * Personal search
	 *
	 * @param string $query
	 * @return int
	 */
	function log_personal_search($query)
	{
		return sql_insert('INSERT INTO user_last_search (user_id, query, time) VALUES (%s, %s, UNIX_TIMESTAMP())', array($_SESSION['user.r']['id'], $query));
	}

	/**
	 * Personal visits
	 *
	 * @param string $query
	 * @return int
	 */
	function log_personal_visits()
	{
		if($_SESSION['page_title']) {
			//sql_res('DELETE FROM user_last_visit WHERE `time` NOT IN ( SELECT `time` FROM `user_last_visit` WHERE user_id = %s ORDER BY `time` DESC LIMIT 5  )');
			return sql_insert('INSERT INTO user_last_visit (user_id, url, title, time) VALUES (%s, %s, %s, UNIX_TIMESTAMP())', array($_SESSION['user.r']['id'], $_SERVER['REQUEST_URI'], $_SESSION['page_title']));
		}
	}
}

?>