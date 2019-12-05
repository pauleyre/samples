<?php

class UserStats
{
	var $user_id;

	function __construct($user_id)
	{
		$this->user_id = intval($user_id);
	}

	function log_info($url, $title)
	{
		$stat = array();
		$stat['source'] = $url;
		$stat['type'] = 'URL';
		$stat['group'] = 'info';
		$stat['title'] = $title;

		$this->perc_inc('info');

		return $this->log_db(serialize($stat));
	}

	function log_selling($url, $title)
	{
		$stat = array();
		$stat['source'] = $url;
		$stat['type'] = 'URL';
		$stat['group'] = 'selling';
		$stat['title'] = $title;

		$this->perc_inc('selling');

		return $this->log_db(serialize($stat));
	}

	function log_banner($banner)
	{
		$stat = array();
		$stat['source'] = $banner;
		$stat['type'] = 'BANNER';
		$stat['group'] = 'selling';

		$this->perc_inc('selling');

		return $this->log_db(serialize($stat));
	}

	function log_finale($url, $title)
	{
		$stat = array();
		$stat['source'] = $url;
		$stat['type'] = 'FINALE';
		$stat['group'] = 'finale';
		$stat['title'] = $title;

		return $this->log_db(serialize($stat));
	}

	function log_misc($url, $title)
	{
		$stat = array();
		$stat['source'] = $url;
		$stat['type'] = 'URL';
		$stat['group'] = 'misc';
		$stat['title'] = $title;

		$this->perc_inc('misc');

		return $this->log_db(serialize($stat));
	}

	function log_db($stat)
	{
		$stat = "$stat|||";

		$a = sql_assoc('SELECT date FROM user_stats WHERE date = CURDATE() AND user_id = %s', $this->user_id);

		if(!$a['date']) {
			return sql_insert('INSERT INTO user_stats VALUES(%s, CURDATE(), %s)', array($this->user_id, $stat));
		}
		else {
			return sql_insert('UPDATE user_stats SET stats=CONCAT(stats, %s) WHERE (user_id = %s) AND (date = CURDATE())', array($stat, $this->user_id));
		}
	}

	function perc_inc($group)
	{
		$a = sql_assoc('SELECT date FROM user_stats_perc WHERE (date = CURDATE()) AND (user_id = %s)', $this->user_id);
		$arr = array('selling' => 0, 'info' => 0, 'misc' => 0);

		switch ($group) {
			case 'info': $arr['info'] = 1; break;
			case 'selling': $arr['selling'] = 1; break;
			case 'misc': $arr['misc'] = 1; break;
		}

		$this->pers_inc($group);

		if(!$a['date']) {
			return sql_insert('INSERT INTO user_stats_perc VALUES (%s, '.$arr['selling'].', '.$arr['info'].', '.$arr['misc'].', CURDATE())', array($this->user_id));
		}
		else {
			return sql_insert('UPDATE user_stats_perc SET '.$group.'='.$group.' + 1 WHERE (user_id = %s) AND (date = CURDATE())', array($this->user_id));
		}
	}

	function generate_stats($timestamp)
	{
		global $dbc;

		$s = '';
		$r = sql_res('SELECT stats, date FROM user_stats WHERE date >= DATE_SUB(date, INTERVAL %s DAY) AND (user_id = %s)', array($timestamp, $this->user_id));
		$a = $dbc->_db->fetch_assoc($r);

		while ($a) {

			$stats = explode('|||', $a['stats']);

			$s .= '<p><b>' . $a['date'] . '</b></p>';

			foreach ($stats as $stat) {

				$stat = unserialize($stat);

				if($stat['type'] == 'URL') {

					$s .= '<a href="http://' . ORBX_SITE_URL . $stat['source'].'"><img title="'.$stat['title'].'" alt="'.$stat['title'].'" onmouseover="$(\'pr_info\').innerHTML = this.title" src="./orbicon/modules/userstats/gfx/'.$stat['group'].'.gif" /></a>';
				}
				else if($stat['type'] == 'BANNER') {
					$s .= '<img src="./orbicon/modules/userstats/gfx/selling.gif" title="'.$stat['source'].'" alt="'.$stat['source'].'" />';
				}
				else if($stat['type'] == 'FINALE') {
					$s .= '<a href="http://' . ORBX_SITE_URL . $stat['source'].'"><img title="'.$stat['title'].'" alt="'.$stat['title'].'" onmouseover="$(\'pr_info\').innerHTML = this.title" src="./orbicon/modules/userstats/gfx/finale.gif" /></a><br/>';
				}
			}

			$a = $dbc->_db->fetch_assoc($r);
		}

		return $s;
	}

	function pers_inc($group)
	{
		$a = sql_assoc('SELECT user_id FROM user_stats_pers WHERE (user_id = %s)', $this->user_id);
		$arr = array('selling' => 0, 'info' => 0, 'misc' => 0);

		switch ($group) {
			case 'info': $arr['info'] = 1; break;
			case 'selling': $arr['selling'] = 1; break;
			case 'misc': $arr['misc'] = 1; break;
		}

		if(!$a['user_id']) {
			return sql_insert('INSERT INTO user_stats_pers VALUES (%s, '.$arr['selling'].', '.$arr['info'].', '.$arr['misc'].')', $this->user_id);
		}
		else {
			return sql_insert('UPDATE user_stats_pers SET '.$group.'='.$group.' + 1 WHERE (user_id = %s)', $this->user_id);
		}
	}

}

?>