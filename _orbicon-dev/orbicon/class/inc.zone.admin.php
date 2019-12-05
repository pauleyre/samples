<?php

/**
 * Zone admin class
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @subpackage Global
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-07-01
 */

	function build_zones($columns, $add_homepage = false, $default_selected = '')
	{
		global $dbc, $orbicon_x, $orbx_mod;

		// homepage
		if($add_homepage) {
			if(!in_array('orbicon_home', $columns)) {
				$opcije = '<option value="orbicon_home">'._L('www_homepage').'</option>';
			}
			else {
				$selected = '<option value="orbicon_home">'._L('www_homepage').'</option>';
			}
		}

		// special pages: sitemap
		if(!in_array('sitemap', $columns)) {
			$opcije .= '<option value="sitemap">'._L('sitemap').'</option>';
		}
		else {
			$selected .= '<option value="sitemap">'._L('sitemap').'</option>';
		}

		// special pages: attila
		if(!in_array('attila', $columns)) {
				$opcije .= '<option value="attila">'._L('search_page').'</option>';
		}
		else {
			$selected .= '<option value="attila">'._L('search_page').'</option>';
		}

		// columns
		$r = $dbc->_db->query(sprintf('
		SELECT 		*
		FROM 		'.TABLE_COLUMNS.'
		WHERE 		(parent IS NULL) AND
					(menu_name != \'hidden\') AND
					(menu_name != \'box\') AND
					(language = %s)
		ORDER BY 	permalink', $dbc->_db->quote($orbicon_x->ptr)));
		$a = $dbc->_db->fetch_assoc($r);
		$opcije .= '<optgroup label="'._L('column').'">';

		while($a) {
			if(!in_array($a['permalink'], $columns)) {
				$opcije .= sprintf('<option value="%s">%s</option>', $a['permalink'], $a['title']);
			}
			else {
				$selected .= sprintf('<option value="%s">%s</option>', $a['permalink'], $a['title']);
			}
			$a = $dbc->_db->fetch_assoc($r);
		}

		$opcije .= '</optgroup>';

		// subcolumns
		$r = $dbc->_db->query(sprintf('
			SELECT 		*
			FROM 		'.TABLE_COLUMNS.'
			WHERE 		(parent IS NOT NULL) AND
						(menu_name != \'hidden\') AND
						(menu_name != \'box\') AND
						(language = %s)
			ORDER BY 	parent ASC, permalink ASC', $dbc->_db->quote($orbicon_x->ptr)));
		$a = $dbc->_db->fetch_assoc($r);
		$opcije .= '<optgroup label="'._L('subcolumn').'">';

		while($a) {
			if(!in_array($a['permalink'], $columns)) {
				$opcije .= sprintf('<option value="%s">%s [%s]</option>', $a['permalink'], $a['title'], $a['parent']);
			}
			else {
				$selected .= sprintf('<option value="%s">%s [%s]</option>', $a['permalink'], $a['title'], $a['parent']);
			}
			$a = $dbc->_db->fetch_assoc($r);
		}

		$opcije .= '</optgroup>';

		// internal
		$r = $dbc->_db->query(sprintf('	SELECT 		*
										FROM 		'.TABLE_COLUMNS.'
										WHERE 		(menu_name = \'hidden\') AND
													(language = %s)
										ORDER BY 	permalink',
		$dbc->_db->quote($orbicon_x->ptr)));

		$a = $dbc->_db->fetch_assoc($r);
		$opcije .= '<optgroup label="'._L('internal').'">';

		while($a) {
			$parent = (!empty($a['parent'])) ? '['.$a['parent'].']' : '';

			if(!in_array($a['permalink'], $columns)) {
				$opcije .= sprintf('<option value="%s">%s %s</option>', $a['permalink'], $a['title'], $parent);
			}
			else {
				$selected .= sprintf('<option value="%s">%s %s</option>', $a['permalink'], $a['title'], $parent);
			}
			$a = $dbc->_db->fetch_assoc($r);
		}

		$opcije .= '</optgroup>';

		// news
		$r = $dbc->_db->query(sprintf('	SELECT 		*
										FROM 		'.TABLE_NEWS.'
										WHERE 		(live = 1) AND
													(language = %s)
										ORDER BY 	permalink',
		$dbc->_db->quote($orbicon_x->ptr)));

		$a = $dbc->_db->fetch_assoc($r);
		$opcije .= '<optgroup label="'._L('news').'">';

		while($a) {
			$parent = (!empty($a['parent'])) ? '['.$a['parent'].']' : '';
			if(!in_array($a['permalink'], $columns)) {
				$opcije .= sprintf('<option value="%s">%s %s</option>', $a['permalink'], $a['title'], $parent);
			}
			else {
				$selected .= sprintf('<option value="%s">%s %s</option>', $a['permalink'], $a['title'], $parent);
			}
			$a = $dbc->_db->fetch_assoc($r);
		}

		$opcije .= '</optgroup>';

		// modules
		$opcije .= '<optgroup label="'._L('modules').'">';
		foreach($orbx_mod->all_modules as $module) {

			if(substr($module, -1, 1) == '/') {
				$module = substr($module, -1, 1);
			}
			$module = explode('/', $module);
			$module = array_pop($module);

			$frontend = $props = $orbx_mod->load_info($module);

			// i am a full page module
			if($frontend['module']['runtime'] == 'page') {
				if(!in_array('mod.' . $module, $columns)) {
					$opcije .= '<option value="mod.' . $module . '">'._L($module).'</option>';
				}
				else {
					$selected .= '<option value="mod.' . $module . '">'._L($module).'</option>';
				}
			}
		}

		$opcije .= '</optgroup>';

		// replace default selected if available
		if($default_selected != '') {
			$opcije = preg_replace('/<option value="'.$default_selected.'">/', '<option value="'.$default_selected.'" selected="selected">', $opcije, 1);
			$selected = preg_replace('/<option value="'.$default_selected.'">/', '<option value="'.$default_selected.'" selected="selected">', $selected, 1);
		}

		return array($opcije, $selected);
	}

	function save_zone()
	{
		if(isset($_POST['save_zone'])) {
			global $dbc, $orbicon_x;
			// * first, delete all
			$permalink = $_GET['edit'];
			$title = trim($_POST['zone_title']);
			$locked = intval($_POST['locked']);
			$ssl = intval($_POST['under_ssl']);

			if($title == '') {
				trigger_error('save_zone() expects parameter 1 to be non-empty', E_USER_WARNING);
				return false;
			}

			$permalink = get_permalink($title);
			$title = utf8_html_entities($title);

			if(!empty($_POST['orbicon_list_selected'])) {
				$_POST['orbicon_list_selected'] = array_remove_empty($_POST['orbicon_list_selected']);
				$columns = implode('|', $_POST['orbicon_list_selected']);
			}

			$columns_ascii = $orbicon_x->urlnormalize($columns, true);
			$columns_ascii = urldecode($columns_ascii);
			
			if(!isset($_GET['edit'])) {
				$q = sprintf('	INSERT
								INTO 		'.TABLE_ZONES.' (
										title, permalink,
										column_list, language,
										locked, under_ssl,
										column_list_ascii) VALUES
										(%s, %s,
										%s, %s,
										%s, %s,
										%s)',
										$dbc->_db->quote($title), $dbc->_db->quote($permalink), 
										$dbc->_db->quote($columns), $dbc->_db->quote($orbicon_x->ptr), 
										$dbc->_db->quote($locked), $dbc->_db->quote($ssl), 
										$dbc->_db->quote($columns_ascii));
			}
			else {
				$q = sprintf('	UPDATE '.TABLE_ZONES.'
								SET		title = %s, permalink = %s,
										column_list = %s, under_ssl = %s,
										locked=%s, column_list_ascii=%s
								WHERE 	(permalink = %s) AND
										(language = %s)',
							$dbc->_db->quote($title), $dbc->_db->quote($permalink),
							$dbc->_db->quote($columns), $dbc->_db->quote($ssl),
							$dbc->_db->quote($locked), $dbc->_db->quote($columns_ascii),
							$dbc->_db->quote($_GET['edit']),
							$dbc->_db->quote($orbicon_x->ptr));
			}

			$dbc->_db->query($q);

			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/zones&edit='.urlencode($permalink));
		}
	}

	function load_zone()
	{
		if(isset($_GET['edit'])) {
			global $dbc, $orbicon_x;
			$q = sprintf('	SELECT 		*
							FROM 		'.TABLE_ZONES.'
							WHERE 		(permalink=%s) AND
										(language = %s)
							LIMIT 		1', $dbc->_db->quote($_GET['edit']), $dbc->_db->quote($orbicon_x->ptr));

			$a = $dbc->_db->get_cache($q);
			if($a !== null) {
				return $a;
			}

			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			$dbc->_db->put_cache($a, $q);
			return $a;
		}
		return null;
	}

	function delete_zone()
	{
		if(isset($_GET['delete_zone'])) {
			global $dbc, $orbicon_x;
			$dbc->_db->query(sprintf('	DELETE
										FROM 	'.TABLE_ZONES.'
										WHERE 	(permalink = %s) AND
												(language = %s)
										LIMIT 	1',
										$dbc->_db->quote($_GET['delete_zone']), $dbc->_db->quote($orbicon_x->ptr)));


			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/zones');
		}
	}

	function get_zones_array()
	{
		global $dbc, $orbicon_x;
		$q = sprintf('	SELECT 		*
						FROM 		'.TABLE_ZONES.'
						WHERE 		(language = %s)
						ORDER BY 	permalink', $dbc->_db->quote($orbicon_x->ptr));

		$a = $dbc->_db->get_cache($q);
		if($a !== null) {
			return $a;
		}

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$zones[] = $a;
			$a = $dbc->_db->fetch_assoc($r);
		}

		unset($a);
		$dbc->_db->put_cache($zones, $q);

		return $zones;
	}

?>