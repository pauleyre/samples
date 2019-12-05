<?php
/**
 * Module handler class
 * The class handles module methods and dynamic loading
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @version 1.3
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-02-14
 */

/**
 * location of all modules
 *
 */
define('ORBX_MOD_ROOT', DOC_ROOT . '/orbicon/modules');
/**
 * Module INI filename
 *
 */
define('ORBX_MOD_INI', 'mod.ini');
/**
 * Big icon ini variable name for desktop
 *
 */
define('ORBX_MOD_ICON_BIG', 'big_icon');
/**
 * Medium icon ini variable name for module backend page
 *
 */
define('ORBX_MOD_ICON_MEDIUM', 'medium_icon');
/**
 * Small icon ini variable name for backend menu
 *
 */
define('ORBX_MOD_ICON_SMALL', 'small_icon');

class Module
{
	/**
	 * a list of all modules and their paths
	 *
	 * @var array
	 */
	var $all_modules;

	/**
	 * a list of installed modules
	 *
	 * @var array
	 */
	var $installed_modules;

	/**
	 * build a list of all modules
	 *
	 */
	function __construct()
	{
		$this->all_modules = glob(ORBX_MOD_ROOT . '/*');
	}

	/**
	 * PHP 4 compatibility
	 *
	 */
	function Module()
	{
		$this->__construct();
	}

	/**
	 * What does this do??
	 *
	 * @deprecated
	 */
	function print_modules()
	{

	}

	/**
	 * return true if $module is valid
	 *
	 * @param string $module
	 * @return bool
	 */
	function validate_module($module)
	{
		$module = $this->_trim_mod_name($module);

		if(!is_dir(ORBX_MOD_ROOT . '/' . $module)) {
			return false;
		}

		if(!is_file(ORBX_MOD_ROOT . '/' . $module . '/' . ORBX_MOD_INI)) {
			return false;
		}

		return true;
	}

	/**
	 * sanitize module name from path
	 *
	 * @param string $module
	 * @return string
	 */
	function _trim_mod_name($module)
	{
		if(strpos($module, '/') !== false) {
			if(substr($module, -1, 1) == '/') {
				$module = substr($module, -1, 1);
			}
			$module = explode('/', $module);
			$module = array_pop($module);
		}
		return $module;
	}

	/**
	 * get mod.ini parameters into an array. returns false on error
	 *
	 * @param string $module
	 * @return array
	 */
	function load_info($module)
	{
		$module = $this->_trim_mod_name($module);

		if(is_file(ORBX_MOD_ROOT . "/$module/" . ORBX_MOD_INI)) {
			return parse_ini_file(ORBX_MOD_ROOT . "/$module/" . ORBX_MOD_INI, true);
		}

		trigger_error('mod.ini for module '.$module.' ('.ORBX_MOD_ROOT . "/$module/" . ORBX_MOD_INI.') is not a file', E_USER_WARNING);
		return false;
	}

	/**
	 * get translations from all modules
	 *
	 * @return array
	 */
	function get_translations()
	{
		global $orbicon_x, $mod, $orbx_log;
		$mod_lngs = array();

		foreach($this->all_modules as $module) {
			// i have the translation file
			if(is_file($module . '/languages/' . $orbicon_x->ptr . '.php')) {
				include $module . '/languages/' . $orbicon_x->ptr . '.php';
			}
			// falling back for default language
			else {
				$orbx_log->dwrite('loading default English language translation in ' . $module, __LINE__, __FUNCTION__);

				if(is_file($module . '/languages/en.php')) {
					include $module . '/languages/en.php';
				}
				else {
					$orbx_log->ewrite('could not load default English language translation in ' . $module, __LINE__, __FUNCTION__);
				}
			}

			$mod_lngs = array_merge($mod_lngs, $mod);
		}

		return $mod_lngs;
	}

	/**
	 * return an array of icon information for $module for icon of $type ORBX_MOD_ICON_*
	 *
	 * @param string $module
	 * @param int $type
	 * @return array
	 */
	function get_module_icon($module, $type)
	{
		global $orbicon_x;
		$icon = array();

		$props = $this->load_info($module);

		$icon[$module] = ORBX_SITE_URL . '/orbicon/modules/'.$module.'/gfx/' . $props['module'][$type];

		return $icon;
	}

	/**
	 * print list items with links to modules for $tab
	 *
	 * @param string $tab
	 * @return string
	 */
	function print_menu_icons($tab)
	{
		global $orbicon_x;
		$menu = '';
		foreach($this->all_modules as $module) {
			$module = $this->_trim_mod_name($module);
			$ico = $this->get_module_icon($module, ORBX_MOD_ICON_SMALL);
			$be_admin = $this->load_info($module);

			if(!empty($be_admin['module']['backend']) && ($tab == $be_admin['module']['tab'])) {

				// there is no file specified in the path, fallback to default icon
				if(substr($ico[$module], -1, 1) == '/') {
					$ico[$module] = ORBX_SITE_URL . '/orbicon/gfx/gui_icons/application.png';
				}

				$menu .= '<li><a href="' . ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/'.$module.'"><img src="'.$ico[$module].'" /> '._L($module).'</a></li>';
			}
		}

		return $menu;
	}

	/**
	 * get and parse all valid box modules for use in template engine
	 *
	 * @return array
	 */
	function get_box_modules()
	{
		global $orbicon_x, $orbx_log;
		$mod_marks = array();

		foreach($this->all_modules as $module) {
			$module = $this->_trim_mod_name($module);
			$frontend = $this->load_info($module);

			// we're a box
			if($frontend['module']['runtime'] == 'box') {
				// we're ready to be implemented
				if(!empty($frontend['module']['template_mark'])) {
					if(scan_templates('<!>' . $frontend['module']['template_mark']) > 0) {
						$mod_marks[$frontend['module']['template_mark']] = include DOC_ROOT . '/orbicon/modules/' . $module . '/' . $frontend['module']['frontend'];

						/*if($mod_marks[$frontend['module']['template_mark']] === false) {
							$orbx_log->ewrite('failed to load "' . $frontend['module']['frontend'] . '(' . DOC_ROOT . '/orbicon/modules/' . $module . '/' . $frontend['module']['frontend'] . ')" for module ' . $frontend['module']['name'], __LINE__, __FUNCTION__);
						}*/
					}
					else {
						$orbx_log->dwrite('skipping box module "' . $module . '". template mark not found in HTML template file', __LINE__, __FUNCTION__);
					}
				}
				else {
					$orbx_log->ewrite('found box module of name "' . $frontend['module']['runtime'] . '" but with an empty "template_mark" value', __LINE__, __FUNCTION__);
				}
			}
		}

		return $mod_marks;
	}

	/**
	 * get and parse valid page module for use in template engine
	 *
	 * @param string $permalink
	 * @return array
	 */
	function get_page_module($permalink)
	{
		global $orbicon_x, $orbx_log;

		// cut out "mod." to get module name
		$module = strtolower(substr($permalink, 4));
		$module = $this->_trim_mod_name($module);
		$frontend = $this->load_info($module);
		$page_mod = array();

		// i am a full page module
		if($frontend['module']['runtime'] == 'page') {
			if($permalink == 'mod.' . $module) {
				$page_mod['title'] = _L($module);
				$page_mod['id'] = $page_mod['title'];
				$page_mod['magister_content'] = include_once DOC_ROOT . '/orbicon/modules/'.$module.'/' . $frontend['module']['frontend'];

				if($page_mod['magister_content'] === false) {
					$orbx_log->ewrite('failed to load "' . $frontend['module']['frontend'] . '" for module ' . $frontend['module']['name'], __LINE__, __FUNCTION__);
				}

				return $page_mod;
			}
		}

		return null;
	}

	/**
	 * return an array of information for module's backend
	 *
	 * @param string $module
	 * @return array
	 */
	function get_module_backend($module)
	{
		global $orbx_log;

		$backend = $this->load_info($module);
		$backend_page = array();

		// i have the translation file
		if(!empty($backend['module']['backend'])) {
			$backend_page['title'] = _L($module);
			$backend_page['module_backend_file'] = DOC_ROOT . '/orbicon/modules/'.$module.'/' . $backend['module']['backend'];
			return $backend_page;
		}

		return null;
	}

	/**
	 * return a filtered array of modules
	 *
	 * @param array $modules
	 * @return array
	 */
	function build_module_lists($modules)
	{
		global$orbicon_x;

		foreach($this->all_modules as $module) {

			$module = $this->_trim_mod_name($module);
			$backend = $this->load_info($module);
			if(!empty($backend['module']['backend'])) {
				if(!in_array($module, $modules)) {
					$disallowed .= sprintf('<option value="%s">%s</option>', $module, _L($module));
				}
				else {
					$allowed .= sprintf('<option value="%s">%s</option>', $module, _L($module));
				}
			}
		}

		return array($disallowed, $allowed);
	}
}

?>