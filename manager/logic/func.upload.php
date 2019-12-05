<?php

	function generate_upload_applet($post_url = '')
	{
		$ini_size = ini_get('post_max_size');

		// Plus version
		if(is_file('logic/dndplus.jar')) {
			$dnd_applet = array(
			'name' => 'Rad Upload Plus',
			'url' => 'http://localhost/manager/logic/dndplus.jar',
			'param_code' => 'com.radinks.dnd.DNDAppletPlus',
			'msg' => 'Dodajte više dokumenata drag-n-drop metodom ili pomoću Browse gumba. Odjednom maksimalno možete dodati dokument(e) sveukupne veličine <b>' . $ini_size . 'B</b>'
			);
		}
		// Lite version
		else if(is_file('logic/dndlite.jar')) {
			$dnd_applet = array(
			'name' => 'Rad Upload Lite',
			'url' => 'http://localhost/manager/logic/dndlite.jar',
			'param_code' => 'com.radinks.dnd.DNDAppletLite',
			'msg' => 'Dodajte više dokumenata drag-n-drop metodom. Odjednom maksimalno možete dodati dokument(e) sveukupne veličine <b>' . $ini_size . 'b</b>'
			);
		}

		$applet = '';
		$use_applet = false;
		$ua = strtolower($_SERVER['HTTP_USER_AGENT']);

		$post_url = ($post_url == '') ? 'http://localhost/manager/logic/render.upload.php?credentials=' . get_ajax_id() : $post_url;

		if(strstr($ua, 'konqueror') || strstr($ua, 'macintosh') || strstr($ua, 'opera')) {
			$use_applet = true;
			$applet .= '<applet name="'.$dnd_applet['name'].'" archive="'.$dnd_applet['url'].'" code="'.$dnd_applet['param_code'].'" id="rup_applet" MAYSCRIPT="yes">';
		}
		else {
			if(strstr($ua, 'msie')) {
				$applet .= '<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" height="100" width="273" id="rup" name="rup" codebase="http://java.sun.com/update/1.5.0/jinstall-1_5-windows-i586.cab#version=1,4,1">';
			}
			else {
				$applet .= '<object type="application/x-java-applet;version=1.4.1" height="100" width="273" id="rup" name="rup">';
			}
			$applet .= '<param name="archive" value="'.$dnd_applet['url'].'">
				<param name="code" value="'.$dnd_applet['param_code'].'">
				<param name="name" value="'.$dnd_applet['name'].'">';
		}

		$query = http_build_query($_GET);
		if($query) {
			$query = '/?' . $query;
		}
		else {
			$query = '/';
		}

		$applet .= '
		<param name="wmode" value="transparent">
		<param name="max_upload" value="'.intval(get_php_ini_bytes($ini_size) / 1024).'">
		<param name="browse" value="1">
		<param name="browse_button" value="1">
		<param name="message" value="'.$dnd_applet['msg'].'">
		<param name="url" value="'.$post_url.'">
		<param name="image" value="http://localhost/manager/web/img/empty.gif">
		<param name="monitor.keep_visible" value="yes">
		<param name="external_redir" value="http://localhost/manager' . $query .'">
		<param name="external_target" value="_top">
		<param name="redirect_delay" value="1000">';

		if(isset($_SERVER['PHP_AUTH_USER'])) {
			$applet .= '<param name="chap" value="'.base64_encode($_SERVER['PHP_AUTH_USER'].':'.$_SERVER['PHP_AUTH_PW']).'">';
		}

		if($use_applet) {
			$applet .= '</applet>';
		}
		else {
			$applet .= '</object>';
		}

		return $applet;
	}

	/**
	 * convert php.ini setting value to bytes
	 *
	 * @param string $value
	 * @return int
	 */
	function get_php_ini_bytes($value)
	{
		$last = strtolower(substr(trim($value), -1, 1));

		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$value *= 1024;
			case 'm':
				$value *= 1024;
			case 'k':
				$value *= 1024;
		}
		return $value;
	}

	function get_ajax_id()
	{

		require_once 'logic/class.Employee.php';
		$e = new Employee();
		$e->getEmployee($_SESSION['employee']['id'], Employee::ACTIVE);

		return sprintf('%u', crc32($e->password));
	}

	function get_is_valid_ajax_id($request)
	{
		global $db;
		require_once 'logic/class.Employee.php';

		if((int) $_SESSION['employee']['flags'] & Employee::ADMIN & Employee::ACTIVE) {
			return true;
		}

		$e = new Employee();
		$r = $e->getEmployees();
		$a = $db->fetch_assoc($r);

		while($a) {
			if(sprintf('%u', crc32($a['password'])) === $request) {
				return $a['id'];
			}
			$a = $db->fetch_assoc($r);
		}

		return false;
	}

	function get_document_icon($ext)
	{
		$ext = strtolower($ext);

		switch($ext) {
			case 'rar':
			case 'zip':
			case 'gz':
			case 'tgz':
			case '7z':
				$pic = 'page_white_compressed.png';
			break;
			case 'xls': $pic = 'page_white_excel.png'; break;
			case 'swf': $pic = 'page_white_flash.png'; break;
			case 'as': $pic = 'page_white_flash.png'; break;
			case 'fh':
			case 'fh8':
			case 'fh9':
			case 'fh10':
			case 'fh11':
				$pic = 'page_white_freehand.png';
			break;
			case 'jpg':
			case 'jpeg':
			case 'tif':
			case 'tiff':
			case 'bmp':
			case 'gif':
			case 'png':
			case 'ico':
			case 'psd':
			case 'dib':
			case 'jpe':
			case 'jfif':
				$pic = 'page_white_picture.png';
			break;
			case 'pdf': $pic = 'page_white_acrobat.png'; break;
			case 'php':
			case 'php3':
			case 'phps':
				$pic = 'page_white_php.png';
			break;
			case 'mp3':
			case 'wav':
			case 'ogg':
			case 'wma':
				$pic = 'sound.png';
			break;
			case 'ppt': $pic = 'page_white_powerpoint.png'; break;
			case 'h': $pic = 'page_white_h.png'; break;
			case 'c': $pic = 'page_white_c.png'; break;
			case 'cpp': $pic = 'page_white_cplusplus.png'; break;
			case 'cs': $pic = 'page_white_csharp.png'; break;
			case 'doc': $pic = 'page_white_word.png'; break;
			case 'cfm': $pic = 'page_white_coldfusion.png'; break;
			case 'txt':
			case 'log':
				$pic = 'page_white_text.png'; break;
			case 'rtf': $pic = 'page_white_office.png'; break;
			case 'ai':
			case 'eps': $pic = 'page_white_vector.png'; break;
			case 'xml': $pic = 'page_white_code.png'; break;
			case 'flv':
			case 'mpg':
			case 'mpeg':
			case 'avi':
			case 'wmv':
				$pic = 'television.png';
			break;

			default: $pic = 'page_white.png'; break;
		}

		return '<img src="./web/img/ext_icons/'.$pic.'" title='.$ext.'>';
	}

	/**
	 * return extension of $filename
	 *
	 * @param string $filename
	 * @return string
	 */
	function get_extension($filename)
	{
		if($filename == '') {
			return null;
		}

		if(strpos($filename, '.') === false) {
			return 'FILE';
		}

		$parts = pathinfo($filename);
		return (strtolower($parts['extension']));
	}

		/**
	 * display $bytes in human-readable format
	 *
	 * YB; //yettabyte
	 * ZB; //zettabyte
	 * EB; //exabyte
	 * PB; //petabyte
	 * TB; //terabyte
	 * GB; //gigabyte
	 * MB; //megabyte
	 * KB; //kilobyte
	 * B; //byte
	 *
	 * @param int $bytes
	 * @return string
	 */
	function byte_size($bytes)
	{
		$n = 0;
		$sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

		while($bytes >= 1024) {
		   $bytes /= 1024;
		   $n ++;
		}

		return (round($bytes, 2).' '.$sizes[$n]);
	}

	/**
	 * return file size of $filename
	 *
	 * @param string $filename
	 * @param bool $format
	 * @return mixed
	 */
	function get_file_size($filename, $format = true)
	{
		if(!is_file($filename) || ($filename == '')) {
			trigger_error('get_file_size() expects parameter 1 to be file', E_USER_WARNING);
			return 'N/A';
		}

		$size = filesize($filename);
		if($format) {
			$size = byte_size($size);
		}
		return $size;
	}

	/**
	 * return human-readable directory size of $dirname
	 *
	 * @param string $dirname
	 * @param bool $format
	 * @return string
	 */
	function get_dir_size($dirname, $format = true)
	{
		if((!is_dir($dirname)) || ($dirname == '')) {
			trigger_error('get_dir_size() expects parameter 1 to be directory', E_USER_WARNING);
			return 'N/A';
		}

	  	$size = 0;
  		$_dir = dir($dirname);
		$file = $_dir->read();

  		while($file !== false) {
			if(($file != '.') && ($file != '..')) {
          		if(is_dir($dirname . $file)) {
          			// fetch dir size
					$size += get_dir_size("$dirname/$file", false);
				}
				else {
					// fetch file size
					$size += filesize("$dirname/$file");
				}
			}
			$file = $_dir->read();
		}
		$_dir->close();
		unset($_dir, $file);

		if($format) {
			return byte_size($size);
		}
		return $size;
	}

?>