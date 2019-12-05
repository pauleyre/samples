<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:	class.banners.php
	Version........:	1.0a (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:	Banner manager. Implements multiple banners, ip tracking, "smart loading", randomness
	Modified.......:	* 1.0a - Pavle Gardijan / 22-10-2006 / fixed bug #1
*-----------------------------------------------------------------------*/

/**
 * Enter description here...
 *
 */
define('BANNER_MAX_LIFETIME', 5);
/**
 * Enter description here...
 *
 */
define('BANNER_CANDIDATES_MAX_PERCENT', 50);

define('BANNER_TYPE_468_X_60', 1);
define('BANNER_TYPE_187_X_86', 2);
define('BANNER_TYPE_244_X_86', 4);

class Banners
{
	var $banner_candidates;
	var $banner_candidate;
	var $banner_user_ip;
	var $types;

	/**
	 * PHP 4 compatibility
	 *
	 */
	function banners()
	{
		$this->__construct();
	}

	/**
	 * assign values to variables
	 *
	 */
	function __construct()
	{
		$this->banner_candidates = array();
		$this->banner_candidate = null;
		$this->banner_user_ip = ORBX_CLIENT_IP;
		$this->types = array(
			BANNER_TYPE_468_X_60 => '468x60',
			BANNER_TYPE_187_X_86 => '187x86',
			BANNER_TYPE_244_X_86 => '244x86'
		);
	}

	/**
	 * Determine user's banner
	 *
	 * @return string
	 */
	function banner_ring($type = BANNER_TYPE_468_X_60)
	{
		ignore_user_abort(true);
		global $dbc, $orbicon_x;
		$banner_determined = false;

		$current_zones = $_SESSION['current_zone'];

		if(!empty($current_zones)) {
			foreach($current_zones as $value) {
				$q = sprintf('	SELECT 		*
								FROM 		'.TABLE_BANNERS.'
								WHERE 		(language = %s) AND
											((zone = %s) OR (zone = \'\') OR (zone = \'all\')) AND
											(banner_type = %s) AND
											(displays != \'0\')',
								$dbc->_db->quote($orbicon_x->ptr), $dbc->_db->quote($value['permalink']), $dbc->_db->quote($type));
				$r = $dbc->_db->query($q);
				$a = $dbc->_db->fetch_assoc($r);

				if(empty($a['id'])) {
					ignore_user_abort(false);
					return false;
				}

				// * This loop will prepare $banner_candidates array for later usage
				while($a) {
					if(!empty($a['ips'])) {
						$b = 0;
						$a['ips'] = $this -> __banner_clean_up_ips($a['ips']);
						$banner_ips = explode('|', $a['ips']);
						foreach($banner_ips as $value) {
							if(empty($value)) {
								$b ++;
							}
						}
					}
					$this -> banner_candidates[$a['permalink']] = (count($banner_ips) - $b);
					$a = $dbc->_db->fetch_assoc($r);
				}

				mysql_free_result($r);

				natsort($this -> banner_candidates);

				//print_r($this -> banner_candidates);

				$total_candidates = count($this -> banner_candidates);

				if(($total_candidates > 1) && (BANNER_CANDIDATES_MAX_PERCENT > 0) && (BANNER_CANDIDATES_MAX_PERCENT < 100)) {
					$total = intval($total_candidates / (100 / BANNER_CANDIDATES_MAX_PERCENT));
					$this -> banner_candidates = array_splice($this -> banner_candidates, 0, $total);
				}

				$this -> banner_candidates = array_keys($this -> banner_candidates);
				$this -> banner_candidate = (count($this -> banner_candidates) > 1) ? array_rand($this -> banner_candidates, 1) : $this -> banner_candidates[0];

				//echo ($this -> banner_candidate);

				$r = $dbc->_db->query($q);
				$a = $dbc->_db->fetch_assoc($r);

				while($a) {
					$a['ips'] = $this -> __banner_clean_up_ips($a['ips']);

					if(!$this -> get_is_banner_displayed($a['ips']) && !$banner_determined) {
						if($this->get_banner_has_displays($a['displays']) && $this -> banner_is_ready_for_display($a['permalink'])) {
							$filename = (is_file(DOC_ROOT.'/site/mercury/'.$a['title'])) ? '/site/mercury/'.$a['title'] : '/site/venus/'.$a['title'];
							$ext = get_extension($a['title']);
							$info = getimagesize(DOC_ROOT.$filename);

							if($type == BANNER_TYPE_468_X_60) {
								$info[0] = (!empty($info[0])) ? $info[0] : 468;
								$info[1] = (!empty($info[1])) ? $info[1] : 60;
							}
							else if($type == BANNER_TYPE_187_X_86) {
								$info[0] = (!empty($info[0])) ? $info[0] : 187;
								$info[1] = (!empty($info[1])) ? $info[1] : 86;
							}
							else if($type == BANNER_TYPE_244_X_86) {
								$info[0] = (!empty($info[0])) ? $info[0] : 244;
								$info[1] = (!empty($info[1])) ? $info[1] : 86;
							}

							switch($ext) {
								case 'swf':
									$preview = "<script type=\"text/javascript\"><!-- // --><![CDATA[
									AC_FL_RunContent(
										'codebase', 'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab',
										'width','".$info[0]."',
										'height','".$info[1]."',
										'src','".substr(ORBX_SITE_URL.$filename, 0, -4)."',
										'quality','high',
										'pluginspage','http://www.adobe.com/go/getflashplayer',
										'movie','".substr(ORBX_SITE_URL.$filename, 0, -4)."',
										'wmode','transparent',
										'menu','0',
										'flashvars','url=".ORBX_SITE_URL."/orbicon/modules/banners/banner.click.php?banner=".$a['permalink'].'|'.base64_encode($a['img_url'])."'
									);
								// ]]></script>";
									/**
									 * @todo this bit of code destroys the layout
									 */
								/*$preview = '
								<object	id="master_banner"
										type="application/x-shockwave-flash"
										data="'.ORBX_SITE_URL.$filename.'"
										height="'.$info[1].'" width="'.$info[0].'">
											<param name="movie" value="'.ORBX_SITE_URL.$filename.'" />
											<param name="quality" value="high" />
											<param name="menu" value="0" />
											<param name="flashvars" value="url='.ORBX_SITE_URL.'/orbicon/controler/banner.click.php?banner='.$a['permalink'].'|'.base64_encode($a['img_url']).'" />

									</object>
								';*/
								break;
								default:
									$preview = '<a onclick="javascript:__bnclick(\''.$a['permalink'].'\');" href="'.$a['img_url'].'" style="border:0;background: 0 none !important;" rel="nofollow" target="_blank"><img style="height:'.$info[1].'px;width:'.$info[0].'px;border:0;" src="'.ORBX_SITE_URL.$filename.'" alt="'.$a['img_url'].'" title="'.$a['img_url'].'" /></a>';
								break;
							}

							if(is_numeric($a['displays'])) {
								$q_ = sprintf('	UPDATE 	'.TABLE_BANNERS.'
												SET 	ips = %s, displays = %s
												WHERE 	(permalink = %s) AND
														(language = %s)',
										$dbc->_db->quote($this -> add_ip_to_banner($a['ips'])), $dbc->_db->quote(intval($a['displays'] - 1)), $dbc->_db->quote($a['permalink']), $dbc->_db->quote($orbicon_x->ptr));
							}
							else {
								$q_ = sprintf('	UPDATE 	'.TABLE_BANNERS.'
												SET 	ips = %s
												WHERE 	(permalink = %s) AND
														(language = %s)',
										$dbc->_db->quote($this -> add_ip_to_banner($a['ips'])), $dbc->_db->quote($a['permalink']), $dbc->_db->quote($orbicon_x->ptr));
							}
							$dbc->_db->query($q_);
							$banner_determined = true;
						}
					}
					else {
						$q_ = sprintf('		UPDATE 	'.TABLE_BANNERS.'
											SET 	ips = %s
											WHERE 	(permalink = %s) AND
													(language = %s)',
									$dbc->_db->quote($a['ips']), $dbc->_db->quote($a['permalink']), $dbc->_db->quote($orbicon_x->ptr));
						$dbc->_db->query($q_);
					}
					$a = $dbc->_db->fetch_assoc($r);
				}
			}

		}

		ignore_user_abort(false);
		return $preview;
	}

	/**
	 * return true if we can view banner
	 *
	 * @param mixed $displays
	 * @return bool
	 */
	function get_banner_has_displays($displays)
	{
		$displays = strtolower($displays);

		// infinite is ok
		if($displays == 'perma') {
			return true;
		}
		// time, check for date
		else if($displays[0] == 't') {
			$displays = str_replace('t', '', $displays);
			$date = strtotime($displays);
			$b = (!empty($displays) && $date > time()) ? true : false;
			return $b;
		}
		// numeric, check for remaining displays
		else if(is_numeric($displays)) {
			$displays = intval($displays);
			$b = ($displays > 0) ? true : false;
			return $b;
		}
		// everything else fails
		return false;
	}

	/**
	 * returns true if user's already listed under banner viewers
	 *
	 * @param string $ips
	 * @return bool
	 */
	function get_is_banner_displayed($ips)
	{
		$ips = explode('|', $ips);

		foreach($ips as $value) {
			$ip_and_timestamp = explode('+', $$value);
			$new[] = $ip_and_timestamp[0];
		}

		// * clean up memory
		unset($ips);

		return in_array($this->banner_user_ip, $new);
	}

	/**
	 * adds IP to a list of banner's IPS
	 *
	 * @param string $ips
	 * @return string
	 */
	function add_ip_to_banner($ips)
	{
		return $ips .= '|' . $this->banner_user_ip.'+'.time();
	}

	function __banner_clean_up_ips($ips)
	{
		$ips = explode('|', $ips);

		foreach($ips as $value) {
			$ip_and_timestamp = explode('+', $value);

			if(($ip_and_timestamp[1] + BANNER_MAX_LIFETIME) > time()
			&& !empty($ip_and_timestamp[1]) && $ip_and_timestamp !== false) {
				$new[] = implode('+', $ip_and_timestamp);
			}
		}

		$new = (is_array($new)) ? (implode('|', $new)) : $new;

		return $new;
	}

	/**
	 * return true if $banner_name equals banner candidate
	 *
	 * @param string $banner_name
	 * @return bool
	 */
	function banner_is_ready_for_display($banner_name) {
		return ($banner_name == $this->banner_candidate);
	}

	function update_banner()
	{
		ignore_user_abort(true);
		$permalink = $_REQUEST['permalink'];
		$displays = $_REQUEST['displays'];
		$client = $_REQUEST['client'];
		$zone = $_REQUEST['zone'];
		$img_url = $_REQUEST['img_url'];
		$type = $_REQUEST['type'];

		global $dbc, $orbicon_x;
		$q = sprintf('	UPDATE 	'.TABLE_BANNERS.'
						SET 	displays=%s, client=%s,
								zone=%s, img_url=%s,
								banner_type=%s
						WHERE 	(permalink=%s) AND
								(language=%s)',
						$dbc->_db->quote($displays), $dbc->_db->quote($client),
						$dbc->_db->quote($zone), $dbc->_db->quote($img_url),
						$dbc->_db->quote($type),
						$dbc->_db->quote($permalink),
						$dbc->_db->quote($orbicon_x->ptr));
		$dbc->_db->query($q);

		ignore_user_abort(false);
		return 1;
	}

	function update_banner_clicks()
	{
		ignore_user_abort(true);
		$permalink = $_REQUEST['banner'];

		// flash banner
		if(strpos($permalink, '|') !== false) {
			// split permalink and target URL
			$permalink = explode('|', $permalink);
			// set URL
			$target = base64_decode($permalink[1]);
			// reset permalink for SQL queries
			$permalink = $permalink[0];
		}

		global $dbc, $orbicon_x, $orbx_log;

		$q = sprintf('	UPDATE 	'.TABLE_BANNERS.'
						SET 	clicks = clicks + 1
						WHERE 	(permalink = %s) AND
								(language = %s)',
							$dbc->_db->quote($permalink),
							$dbc->_db->quote($orbicon_x->ptr));
		$dbc->_db->query($q);

		ignore_user_abort(false);

		if(!empty($target)) {
			redirect($target);
		}

		return 1;
	}

	function get_banner_zones($selected_zone)
	{
		$zones = get_zones_array();

		$menu = '<optgroup label="'._L('select_zone').'">';
		$menu .= '<option value="">'._L('all').'</option>';

		if(!empty($zones)) {
			foreach($zones as $value) {
				$selected = ($value['permalink'] == $selected_zone) ? 'selected="selected"' : '';
				$menu .= '<option value="'.$value['permalink'].'" '.$selected.'>'.$value['title'].'</option>';
			}
		}

		$menu .= '</optgroup>';
		return $menu;
	}

	function get_banner_clients($selected_client)
	{
		global $dbc;
		$menu = '<optgroup label="'._L('select_client').'">';
		$menu .= '<option value="">&mdash;</option>';

		/**
		 * @todo not used currently, disabled for perfomance
		 */
		/*$q = '	SELECT 	username
				FROM 	'.TABLE_REG_USERS.'
				WHERE 	(username != \'\') AND
						(banned = 0)';

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$menu .= '<option value="'.$a['username'].'">(RID '.$a['id'].')'.$a['username'].'</option>';
			$a = $dbc->_db->fetch_assoc($r);
		}*/

		$menu .= '</optgroup>';
		return $menu;
	}

	/**
	 * Render banner
	 *
	 * @param string $title
	 * @param string $permalink
	 * @param string $url
	 * @return string
	 */
	function banner_renderer($title, $permalink, $url, $type)
	{
		$filename = (is_file(DOC_ROOT.'/site/mercury/'.$title)) ? '/site/mercury/'.$title : '/site/venus/'.$title;
		$ext = get_extension($title);
		$info = getimagesize(DOC_ROOT.$filename);

		if($type == BANNER_TYPE_468_X_60) {
			$info[0] = (!empty($info[0])) ? $info[0] : 468;
			$info[1] = (!empty($info[1])) ? $info[1] : 60;
		}
		else if($type == BANNER_TYPE_187_X_86) {
			$info[0] = (!empty($info[0])) ? $info[0] : 187;
			$info[1] = (!empty($info[1])) ? $info[1] : 86;
		}
		else if($type == BANNER_TYPE_244_X_86) {
			$info[0] = (!empty($info[0])) ? $info[0] : 244;
			$info[1] = (!empty($info[1])) ? $info[1] : 86;
		}

		switch($ext) {
			case 'swf':
				$preview = "<script type=\"text/javascript\"><!-- // --><![CDATA[
				AC_FL_RunContent(
					'codebase', 'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab',
					'width','".$info[0]."',
					'height','".$info[1]."',
					'src','".substr(ORBX_SITE_URL . $filename, 0, -4)."',
					'quality','high',
					'pluginspage','http://www.adobe.com/go/getflashplayer',
					'movie','".substr(ORBX_SITE_URL . $filename, 0, -4)."',
					'wmode','transparent',
					'menu','0',
					'flashvars','url=" . ORBX_SITE_URL . "/orbicon/modules/banners/banner.click.php?banner=".$permalink.'|'.base64_encode($url)."'
				);
			// ]]></script>";
			break;
			default:
				$preview = '<a onclick="javascript:__bnclick(\''.$permalink.'\');" href="'.$url.'" style="border:0;background: 0 none !important;" rel="nofollow" target="_blank"><img style="height:'.$info[1].'px;width:'.$info[0].'px;border:0;" src="'.ORBX_SITE_URL.$filename.'" alt="'.$url.'" title="'.$url.'" /></a>';
			break;
		}

		return $preview;
	}
}

?>