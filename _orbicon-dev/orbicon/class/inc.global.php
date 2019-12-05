<?php
/**
 * Global library
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemFE
 * @subpackage Global
 * @version 1.00
 * @link http://
 * @license http://
 * @since 2006-07-01
 */

	/**
	 * set read/write rights to $filename
	 *
	 * @param string $filename
	 * @param int $mod
	 * @param bool $backup
	 * @return bool
	 */
	function chmod_unlock($filename, $mod = 0666, $backup = true)
	{
		// save umask to memory
		$_SESSION['chmod'][base64_encode($filename)] = umask(0);
		$b = chmod($filename, $mod);

		global $orbx_log;
		if(!$b) {
			$orbx_log->ewrite('unable to chmod '.$filename.' to ' . decoct($mod) . ' for user ' . get_current_user() . '(UID ' . getmyuid() . ')', __LINE__, __FUNCTION__);
		}
		else {
			$orbx_log->dwrite('set chmod '.$filename.' to '. decoct($mod), __LINE__, __FUNCTION__);

            // backup if we aren't a php file
			if($backup && (get_extension($filename) !== 'php') && is_file($filename)) {
				$bck_dir = dirname($filename) . '/bck';

				if(!is_dir($bck_dir)) {
					mkdir($bck_dir, 0777);
				}

				$info = pathinfo($filename);
				$do_copy = copy($filename, $bck_dir . '/' . $info['basename'] . '.bk');

				if(!$do_copy) {
					$orbx_log->ewrite('unable to backup '.$filename, __LINE__, __FUNCTION__);
				}
			}
		}
		return $b;
	}

	/**
	 * set write rights from $filename
	 *
	 * @param string $filename
	 * @param int $mod
	 * @return bool
	 */
	function chmod_lock($filename, $mod = 0644)
	{
		$umsk_file = $_SESSION['chmod'][base64_encode($filename)];
		$b = chmod($filename, $mod);
		umask($umsk_file);
		// release memory
		unset($umsk_file);

		global $orbx_log;
		if(!$b) {
			$orbx_log->ewrite('unable to chmod '.$filename.' to ' . decoct($mod) . ' for user ' . get_current_user() . '(UID ' . getmyuid() . ')', __LINE__, __FUNCTION__);
		}
		else {
			$orbx_log->dwrite('set chmod '.$filename.' to '. decoct($mod), __LINE__, __FUNCTION__);
		}

		return $b;
	}

	/**
	 * this function follows RFC2396 guidelines available at http://www.ietf.org/rfc/rfc2396.txt
	 * and makes sure the output turns out as lowercase alphanumeric. supports UTF8
	 * returns "permalinked" $input
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $input
	 * @return string
	 */
	function get_permalink($input)
	{
		// quick exit
		if($input == '') {
			return '';
		}

		if(!function_exists('mb_str_replace')) {
			include DOC_ROOT . '/orbicon/lib/php-compat/mb_str_replace.php';
		}

		// for multibyte
		if(function_exists('mb_strtolower')) {
			$output = trim(mb_strtolower($input, 'UTF-8'));
		}
		// for singlebyte
		else {
			$output = trim(strtolower($input));
		}
		unset($input);

		$replace = array(

		// remove
		'*' => '',
		'>' => '',
		'<' => '',
		'|' => '',
		'"' => '',
		'\'' => '',
		'~' => '',
		'?' => '',
		'#' => '',
		'%' => '',
		'$' => '',
		'!' => '',
		// remove these as well. users used them!
		"\r" => '',
		"\n" => '',
		"\t" => '',
		// unwise
		'{' => '',
		'}' => '',
		'\\' => '',
		'^' => '',
		'[' => '',
		']' => '',
		'`' => '',
		// replace
		'=' => '-',
		'&' => '-',
		'+' => '-',
		' ' => '-',
		'/' => '-',
		':' => '-',
		';' => '-',
		'@' => '-',
		',' => '-',
		'.' => '-'
		);

		$for_removal = array_keys($replace);
		$for_replacement = array_values($replace);

		// for multibyte
		if(function_exists('mb_strtolower')) {
			$output = mb_strtolower(mb_str_replace($for_removal, $for_replacement, $output, 'UTF-8'), 'UTF-8');
		}
		// for singlebyte
		else {
			$output = strtolower(str_replace($for_removal, $for_replacement, $output));
		}

		// replace any double -- with single -
		// for multibyte
		if(function_exists('mb_strtolower')) {
			$double_m = mb_strpos($output, '--', NULL, 'UTF-8');
			while($double_m !== false) {
				$output = mb_str_replace('--', '-', $output, 'UTF-8');
				$double_m = mb_strpos($output, '--', NULL, 'UTF-8');
			}

			// remove dot or minus at the end
			$last = mb_substr($output, -1, 1, 'UTF-8');
			while(($last == '.') || ($last == '-')) {
				$output = mb_substr($output, 0, -1, 'UTF-8');
				$last = mb_substr($output, -1, 1, 'UTF-8');
			}
		}
		// for singlebyte
		else {
			$double_m = strpos($output, '--');
			while($double_m !== false) {
				$output = str_replace('--', '-', $output);
				$double_m = strpos($output, '--');
			}

			// remove dot or minus at the end
			$last = substr($output, -1, 1);
			while(($last == '.') || ($last == '-')) {
				$output = substr($output, 0, -1);
				$last = substr($output, -1, 1);
			}
		}

		return $output;
	}

	/**
	 * generate keywords from $input
	 *
	 * @param string $input
	 * @param bool $slice
	 * @return string
	 */
	function keyword_generator($input, $slice = true)
	{
		// quick exit
		if($input == '') {
			trigger_error('keyword_generator() expects parameter 1 to be non-empty', E_USER_NOTICE);
			return '';
		}

		$replace_me = array('.', ',', ':', '!', '?', '(', ')', '"', '\'', '*', "\r", "\n", "\t");
		$input = str_replace($replace_me, ' ', $input);
		$input = explode(' ', $input);

		foreach($input as $value) {
			$value = trim($value);
			if(strlen($value) > 2 && !empty($value) && !is_numeric($value)) {
				$keywords[] = $value;
			}
		}

		unset($input);
		$keywords = array_unique($keywords);

		if($slice) {
			// sort by number of occurences
			$keywords = array_count_values($keywords);
			$limit = intval(count($keywords) / 2);
			$limit = ($limit > 20) ? 20 : $limit;
			$keywords = array_slice($keywords, 0, $limit);
			$keywords = array_keys($keywords);
		}

		$keywords = implode(', ', $keywords);
		$keywords = preg_replace('/\s\s+/', ' ', $keywords);
		return $keywords;
	}

	/**
	 * remove excessive whitespace, linefeeds, etc.
	 *
	 * @param string $string
	 * @param bool $trim_tags
	 * @return string
	 */
	function min_str($string, $trim_tags = false)
	{
		// quick exit
		if($string == '') {
			trigger_error('min_str() expects parameter 1 to be non-empty', E_USER_NOTICE);
			return '';
		}

		// tags
		if($trim_tags) {
			return str_sanitize($string, STR_SANITIZE_HTML);
		}

		// Added from PHP5 In Practice, Alen Novakovic-13/11/06
		//-- remove leading whitespace
		$string = trim($string);

		//-- remove any double-up whitespace
		$string = preg_replace('/\s(?=\s)/', '', $string);

		//-- replace any non-space, with a space
		$string = preg_replace('/[\n\r\t]/', ' ', $string);
		//-- ADD finished -------------------------------------

		return $string;
	}

	/**
	 * create empty $filename
	 *
	 * @param string $filename
	 * @return bool
	 */
	function create_empty_file($filename)
	{
		if($filename == '') {
			trigger_error('create_empty_file() expects parameter 1 to be non-empty', E_USER_WARNING);
			return false;
		}

		if(!is_writable(dirname($filename))) {
			trigger_error($filename . ' could not be created because parent dir is not writable', E_USER_WARNING);
			return false;
		}

		$r = fopen($filename, 'wb');

		if(!$r) {
			trigger_error($filename . ' was not created', E_USER_WARNING);
			return false;
		}

		return fclose($r);
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

		return (rounddown($bytes, 2).' '.$sizes[$n]);
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
					$size += filesize("$dirname/$file", false);
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
	 * Adler-32 CRC
	 *
	 */
	define('MOD_ADLER', 65521);

	/**
	 * return Adler-32 for $data
	 *
	 * @param string $data
	 * @return int
	 */
	function adler32($data)
	{
		settype($data, 'string');
		$len = strlen($data);
		$a = 1;
		$b = 0;
		$n = 0;

		while($len) {
			$tlen = ($len > 5550) ? 5550 : $len;
			$len -= $tlen;

			do {
				$a += ord($data[$n]);
				$b += $a;
				$n++;
			} while (--$tlen);

			$a = ($a & 0xFFFF) + ($a >> 16) * 15; // (65536 - MOD_ADLER)
			$b = ($b & 0xFFFF) + ($b >> 16) * 15;
		}

		// it can be shown that a <= 0x1013A here, so a single subtract will do.
		if($a >= MOD_ADLER) {
			$a -= MOD_ADLER;
		}

		// it can be shown that b can reach 0xFFEF1 here.
		$b = ($b & 0xFFFF) + ($b >> 16) * 15;

		if($b >= MOD_ADLER) {
			$b -= MOD_ADLER;
		}
		return ($b << 16) | $a;
	}

	/**
	 * return's true if client's a search bot, false otherwise
	 *
	 * @return bool
	 */
	function get_is_search_engine_bot()
	{
		// return result if we calculated it earlier
		if((session_id() !== '') && isset($_SESSION['__is_search_bot'])) {
			return (bool) $_SESSION['__is_search_bot'];
		}
		// robot's only use this method
		if(strtoupper($_SERVER['REQUEST_METHOD']) == 'HEAD') {
			return true;
		}

		$robot = false;
		$ua = strtolower(ORBX_USER_AGENT);

		// whitelist
		if(strpos($ua, 'gohome') !== false) {
			return false;
		}

		// popular search bots
		$bots = array(
			'snoopy',
			'googlebot',
			'webcrawler',
			'grub.org',
			'slurp',
			'openfind',
			'antibot',
			'netresearchserver',
			'nutch',
			'ia_archiver',
			'scooter',
			'fluffy',
			'msnbot',
			'gigabot',
			'teoma',
			'baidu',
			'pogodak',
			'ia_archiver',
			'crawl',
			'bot/',
			'bot-',
			'spider',
			'robot',
			'yandex',
			'larbin'
		);

/*

Gromit
www.WebWombat.com.au
Suchknecht.at-Robot
RHCS
Pjspide
robocrawl
spider_monkey
BaiDuSpider
felix
Denmex
jubii
finnish
antibot
DeepIndex
Pompos
Exalead
xyro
xyro_
NG/1.0
NG/2.0
SynoBot
VoilaBot
SurferF3
cosmos
AbachoBOT
Acoon
motor)German
    * Die Blinde Kuh (User-agent: blindekuh)German
    * Fireball.de (User-agent: Firefly KIT-Fireball)German
    * Nathan (User-agent: Tarantula)German
    * SpeedFind (User-agent: speedfind ramBot xtreme)German
    * Suchbot (User-agent: suchbot)German
    * Suchmaschine21 (User-agent: CoolBot)German
    * Tigersuche.de (User-agent: Tigersuche)German
    * WebSearchBench (User-agent: WebSearchBench)German
    * Webspinne.de (User-agent: Webspinne)German

Hong Kong

    * Suntek search engine (User-agent: suntek)Chinese

Hungary

    * Goliat (User-agent: Goliat goliatspider263)Hungarian
    * Let Find It Now! (User-agent: ELFINBOT)English
    * Sztaki.hu (User-agent: Computer_and_Automation_Research_Institute_Crawler)English

Ireland

    * ADSA (User-agent: ADSARobot)English

Italy

    * Arianna (User-agent: arianna)Italian
    * Iltrovatore (User-agent: IlTrovatore-Setaccio)Italian

Japan

    * @nifty (User-agent: InfoNaviRobot(F109))Japanese
    * Goo (User-agent: moget)Japanese
    * Kototoi (User-agent: Kototoi)Japanese
    * Lisa (User-agent: Voyager)Japanese
    * Navi (User-agent: griffon nttdirectory_robot)Japanese
    * NEC-MeshExplorer (User-agent: meshexplorer)Japanese
    * NTT Directory (User-agent: nttdirectory_robot)Japanese
    * Steeler (User-agent: Steeler)English
    * Verno (User-agent: iron33)Javanese
    * Yappo (User-agent: ko_yappo_robot)Japanese
    * YokogaoSearch (User-agent: suzuran swbot/0.9c libwww/5.3.1)English

Korea, Republic of

    * Daum (User-agent: RaBot)Korean
    * Naver.com (User-agent: NaverBot dloader dumrobo NaverRobot Cowbot)Korean

Netherlands, The

    * Ingrid (User-agent: INGRID)Dutch

New Zealand

    * Katipo (User-agent: kapito)English
    * nzexplorer (User-agent: nzexplorer)English

Norway

    * Boitho (User-agent: boitho)Norwegian
    * Kvasir (User-agent: Solbot)Norwegian

Poland

    * Szukacz (User-agent: Szukacz)Polish

Portugal

    * Cusco (User-agent: cusco)Portuguese

Russian Federation

    * Rambler (User-agent: StackRambler)Russian
    * UDMSearch (User-agent: mnoGoSearch)English
    * Yandex (User-agent: Yandex)Russian

Spain

    * Abcdatos (User-agent: abcdatos_botlink)Spanish

Sweden

    * EntireWeb (User-agent: speedy)English
    * Euroseek (User-agent: Freecrawl Arachnoidea)English
    * The NWI Robot (User-agent: w3index)English

Switzerland

    * Alkaline (User-agent: AlkalineBOT)English
    * Augurfind (User-agent: augurfind)English
    * Search.ch (User-agent: search.ch)German

Taiwan

    * Openfind (User-agent: Openbot Openfind)English

Thailand

    * SpiderKU (User-agent: SpiderKU)Thai

USA

    * ABCsearch (User-agent: ABCsearch)English
    * AESOP (User-agent: AESOP_com_SpiderMan)English
    * Ah-ha (User-agent: ah-ha.com)English
    * Alexa (User-agent: ia_archiver)English
    * AllTheWeb (User-agent: FAST-WebCrawler FASTCrawler FAST-RealWebCrawler)English
    * AltaVista (User-agent: Scooter AVFetch AltaVista Mercator roach.smo.av.com Tv33_Mercator)English
    * Anzwers (User-agent: AnzwersCrawl)English
    * Araneo (User-agent: araneo)Esperanto
    * ARIADNE (User-agent: ariadne)English
    * AskJeeves (User-agent: Jeeves askjeeves)English
    * ASPseek (User-agent: ASPseek)English
    * ASTRAFIND (User-agent: AstraSpider)English
    * Atomz.com (User-agent: Atomz)English
    * BBot (User-agent: BBot)English
    * Boito (User-agent: boito)English
    * Borg-Bot (User-agent: borg-bot)English
    * ChristCENTRAL.com (User-agent: christcrawler)English
    * ComputingSite Robi (User-agent: robi)English
    * Ditto (User-agent: DittoSpyder)English
    * Eureka (User-agent: EZResult)English
    * Excite (User-agent: ArchitextSpider)English
    * FastHealth (User-agent: lachesis)English
    * FastSearch (User-agent: Fast)English
    * FirstGov (User-agent: FirstGov.gov)English
    * Fluid Dynamics (User-agent: FDSE)English
    * FusionBOT (User-agent: galaxy)English
    * GAIS (User-agent: Gaisbot)English
    * GeckoBot (User-agent: geckobot)English
    * GenDoor (User-agent: GenCrawler)English
    * Greenpac.com (User-agent: inspectorwww Greenpac)English
    * Grub (User-agent: grub-client)English
    * Hometown Spider Pro (User-agent: hometown)English
    * Hoppa (User-agent: Toutatis)English
    * ht://Dig (User-agent: htdig)English
    * Hubat (User-agent: Hubater)English
    * IBM_Planetwide (User-agent: IBM_Planetwide almaden)English
    * IncyWincy (User-agent: incywincy NetResearchServer)English
    * Infoseek.com (User-agent: infoseek infoseeksidewinder UltraSeek)English
    * Inktomi (User-agent: Slurp LG_catalog moget)English
    * Intelliseek (User-agent: Intelliseek)English
    * InternetSeer.com (User-agent: internetseer)English
    * IP3000 (User-agent: ip3000.com-crawler)English
    * Larbin (User-agent: larbin larbin_devel)English
    * Lexis-Nexis (User-agent: LNSpiderguy)English
    * Look (User-agent: Look.com)English
    * Looksmart (User-agent: MantraAgent WISENutBot WiseNut)English
    * Lycos (User-agent: Lycos funnelweb WhoWhere)English
    * MagPortal (User-agent: legs)English
    * Maxbot (User-agent: maxbot)English
    * MerzScope (User-agent: MerzScope)English
    * Mopilot.com (User-agent: Wapspider)English
    * MSNBOT (User-agent: MSNBOT)English
    * NationalDirectory (User-agent: NationalDirectory-SuperSpider NationalDirectory-WebSpider)English
    * NetNose (User-agent: NetNose)English
    * Northern Light (User-agent: Gulliver)English
    * NPBot (User-agent: NPBot)English
    * Nutch (User-agent: NutchOrg NutchCVS nutch)English
    * Only.com (User-agent: Spida)English
perlcrawler
phpdig
psbot
CrawlerBoy
Fido
poppi
portalb
QuepasaCreep
Raven
robozilla
roverbot
Scrubby
Fluffy
searchhippo.com
SearchSpider
SightQuestBot
asterias
skymob
Sleek
Slider_Search_v1-de
StackRambler
Surfnomore
SurveyBot
teomaagent
teomaagent1
directhit
Teradex
webmoose
T-H-U-N-D-E-R-S-T-O-N-E
Topiclink
tutorgig
Unitek
VeryGoodSearch
appie
MuscatFerret

whatUseek_winona
whatuseek
WhizBangLab
WSCbot
wotbox
YahooBot
YahooSeeker
Yahoo-MMCrawler
gulperbot
Zippy
SEARCH.COM.UA
Uaportal
UASEARCH.KIEV.UA
AltaVista-Intranet
HenryTheMiragoRobot
SearchUK
MegaSheep
Superewe
UKSearcher
WIRE
Yellopet-Spider
jcrawler
cruiser*/


		foreach($bots as $value) {
			if($robot === false) {
				$robot = strpos($ua, strtolower($value));
			}
		}
		unset($bots);

		if($robot !== false) {
			$robot = true;
		}

		// save result
		if((session_id() !== '') && !isset($_SESSION['__is_search_bot'])) {
			$_SESSION['__is_search_bot'] = $robot;
		}

		return $robot;
	}

	/**
	 * Validate email format
	 *
	 * @param string $email
	 * @return bool
	 */
	function is_email($email)
	{
		$email = trim($email);

		// quick exit
		if($email == '') {
			return false;
		}

		// these are minimum requirements
		if(strpos($email, '@') !== false && strpos($email, '.') !== false) {
			// THE pattern
			$pattern = '/^[a-z0-9_-][a-z0-9._-]+@([a-z0-9][a-z0-9-]*\.)+[a-z]{2,6}$/i';
			// go for it
			if(preg_match($pattern, $email)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * deletes empty keys from array. returns -1 on error
	 *
	 * @param array $array
	 * @return array
	 */
	function array_remove_empty($array)
	{
		if(is_array($array)) {
			return array_diff($array, array('', NULL, 0, 0.0, false));
		}
		return -1;
	}

	/**
	 * better implementation of round function
	 *
	 * @param float $float
	 * @param int $precision
	 * @return float
	 * @todo implement sprintf for larger numbers
	 */
	function rounddown($float, $precision = 0)
	{
		//sprintf('%01.2f', $float);
		$float += 0.0000001;
		return (round($float, $precision));
	}

	/**
	 * returns password of length $pwd_length or NULL on failure
	 * minimum length 1, maximum 255
	 *
	 * @param int $pwd_length		Password length
	 * @return string				New password
	 */
	function generate_password($pwd_length = 8)
	{
		$pwd_length = intval($pwd_length);

		// sanity check
		if($pwd_length < 1) {
			trigger_error('generate_password() parameter 1 value needs to be 1 or greater', E_USER_NOTICE);
			$pwd_length = 1;
		}
		else if ($pwd_length > 255) {
			trigger_error('generate_password() parameter 1 value needs to be 255 or lower', E_USER_NOTICE);
			$pwd_length = 255;
		}

		$pwd = '';
		$pwd_chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$pwd_chars_length = strlen($pwd_chars);

		for($i = 0; $i < $pwd_length; $i++) {
			$char = $pwd_chars[(rand(1, $pwd_chars_length) - 1)];
			// 50% chance to go uppercase
			if(!is_numeric($char) && (rand(0, 1) == 0)) {
				$char = strtoupper($char);
			}

			$pwd .= $char;
		}

		if(strlen($pwd) != $pwd_length) {
			return null;
		}

		return $pwd;
	}

	/**
	 * securely set cookie of $name to $value
	 *
	 * @param string $name
	 * @param string $value
	 * @param int $expires
	 */
	function secure_setcookie($name, $value, $expires)
	{
		setcookie($name, $value . '--' . md5($value . ORBX_UNIQUE_ID), $expires, '/', '.' . DOMAIN_NO_WWW);
	}

	/**
	 * securely retrieve value for cookie of $name. returns NULL on error
	 *
	 * @param string $name
	 * @return string
	 */
	function secure_getcookie($name)
	{
		if(isset($_COOKIE[$name])) {
			$cookie = explode('--', $_COOKIE[$name]);
			if(md5($cookie[0] . ORBX_UNIQUE_ID) === $cookie[1]) {
				return $cookie[0];
			}
		}
		return null;
	}

	/**
	 * convert UTF8 to HTML entities
	 *
	 * @param string $input			String to convert
	 * @param bool $reverse			Convert HTML entities to UTF8
	 * @return string
	 */
	function utf8_html_entities($input, $reverse = false)
	{
		// quick exit
		if($input == '') {
			return '';
		}

		include_once DOC_ROOT . '/orbicon/class/inc.utf8.php';

		if($reverse) {
			return htmlutf8($input);
		}
		else {
			return utf8html($input);
		}
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

	/**
	 * A function to creating a lock directory for us, getting around the need to use a flock()
	 *
	 * @param string $filename			File to lock access
	 * @return bool
	 */
	function lock($filename)
	{
		clearstatcache();
		$dirname = DOC_ROOT . '/site/mercury/' . basename($filename) . '.dirlock';
		global $orbx_log;

		// Remove the ability for user aborts until we are finished
		ignore_user_abort(true);

		// Start counter, we only want to try to obtain a lock
		// a certain number of times
		$counter = 0;

		// Until we get a lock, or die trying, go for it
		do {
			// Sleep for counter-squared seconds, longer each time
			sleep($counter * $counter);

			// Make the directory
			$success = mkdir($dirname, 0777);
		} while (!$success && ($counter++ < 10));

		// If counter is 11, then we never got the lock
		if($counter == 11) {
			$orbx_log->ewrite("could not obtain exclusive lock for $filename", __LINE__, __FUNCTION__);
			return false;
		}
		// Otherwise we are done
		$orbx_log->dwrite("obtained exclusive lock for $filename", __LINE__, __FUNCTION__);
		return true;
	}

	/**
	 * Unlock access to file which was locked with lock()
	 *
	 * @param string $filename		File to unlock access
	 */
	function unlock($filename)
	{
		clearstatcache();
		global $orbx_log;

		$dirname = DOC_ROOT . '/site/mercury/' . basename($filename) . '.dirlock';

		// Remove the directory
		if(is_dir($dirname)) {
			if(!rmdir($dirname)) {
				$orbx_log->ewrite("could not undo exclusive lock for $filename", __LINE__, __FUNCTION__);
			}
			else {
				$orbx_log->dwrite("released exclusive lock for $filename", __LINE__, __FUNCTION__);
			}
		}
		else {
			$orbx_log->ewrite($dirname . ' is not a directory', __LINE__, __FUNCTION__);
		}

		// Remove the restrictions on user aborts
		ignore_user_abort(false);
	}

	// Now we have to write function for displaying error to above function
	// system_crash_cleanup()
	function get_detailed_error($errno, $errstr, $errfile, $errline, $errcontext)
	{
		global $_system_crash_status;
		$_system_crash_status = true;

		$trace = debug_backtrace();

		// First of all, begin by noting that fatal error occured, and
		// basic details just like a normal error report
		$error_body = "<p>FATAL ERROR - $errstr<p>";

		// Now use debug_print_backtrace to give the function call list
		// in a compressed format easy for quick scanning
		$error_body.= '<p>--------------------------------<p>';

		foreach($trace as $k => $v){
			$error_body .= "<p> $k : $v</p>";
		}

		unset($trace);
		return $error_body;
	}

	/**
	 * Trucantes text without chopping it off on wrong places
	 *
	 * @param string $string			String to truncate
	 * @param int $max					Max length
	 * @param string $moretext			String to append to truncated text
	 * @return string					Truncated text
	 */
	function truncate_text($string, $max, $moretext)
	{
		if(strlen($string) > $max) {
			$max -= strlen($moretext);

			$string = strrev(strstr(strrev(substr($string, 0, $max)), ' '));
			$string .= $moretext;
		}
		return $string;
	}

	/**
	 * set flag on/off
	 *
	 * @param int $var
	 * @param int $flag
	 * @param bool $set
	 */
	function set_flag(&$var, $flag, $set = true)
	{
		$var = ($set) ? ($var | $flag) : ($var & ~$flag);
	}

	/**
	 * return MIME type by extenstion
	 *
	 * @param string $extension
	 * @return string
	 */
	function get_mime_by_ext($extension)
	{
		global $dbc;
		$q = sprintf('	SELECT 		mime
						FROM 		'.TABLE_MIME_TYPES.'
						WHERE		(ext = %s)
						LIMIT		1', $dbc->_db->quote($extension));

		$a = $dbc->_db->get_cache($q, true);
		if($a !== null) {
			return $a;
		}

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		if($a['mime'] != '') {
			$dbc->_db->put_cache($a['mime'], $q, true);
			return $a['mime'];
		}

		return 'application/octet-stream';
	}

	/**
	 * Print HTML select menu
	 *
	 * @param array $options		List of options
	 * @param string $default		Selected option
	 * @param bool $keys_values
	 * @return string				HTML option tags
	 *
	 */
	function print_select_menu($options, $default = null, $keys_values = false)
	{
		if(!is_array($options)) {
			//trigger_error('print_select_menu() expects parameter 1 to be array, '.gettype($options).' given', E_USER_WARNING);
			return false;
		}

		$menu = '';

		if($keys_values) {
			foreach($options as $k => $v) {
				$selected = ($k == $default) ? ' selected="selected"' : '';
				$menu .= '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
			}
		}
		else {
			foreach($options as $option) {
				$selected = ($option == $default) ? ' selected="selected"' : '';
				$menu .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
			}
		}

		return $menu;
	}

	/**
	 * Remove UTF BOM from string. supports all UTF encodings
	 * (bom can prevent pages or even PHP from functioning properly)
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $string
	 * @return string
	 */
	function remove_utf8bom($string)
	{
		global $orbx_log;

		$utf_boms = array(
			 array("\xEF\xBB\xBF", 			3, 'UTF-8'),
			 array("\xFE\xFF", 				2, 'UTF-16 Big Endian'),
			 array("\xFF\xFE", 				2, 'UTF-16 Little Endian'),
			 array("\x00\x00\xFE\xFF", 		4, 'UTF-32 Big Endian'),
			 array("\xFF\xFE\x00\x00", 		4, 'UTF-32 Little Endian'),
			 array("\xOE\xFE\xFF", 			3, 'SCSU'),
			 array("\x2B\x2F\x76\x38\x2D", 	5, 'UTF-7 (38 2D)'),
			 array("\x2B\x2F\x76\x38", 		4, 'UTF-7 (38)'),
			 array("\x2B\x2F\x76\x39", 		4, 'UTF-7 (39)'),
			 array("\x2B\x2F\x76\x2B", 		4, 'UTF-7 (2B)'),
			 array("\x2B\x2F\x76\x2F", 		4, 'UTF-7 (2F)'),
			 array("\xDD\x73\x66\x73", 		4, 'UTF-EBCDIC'),
			 array("\xFB\xEE\x28", 			3, 'BOCU-1')
		);

		// loop through all BOMS
		foreach ($utf_boms as $v) {
			list($bom, $length, $name) = $v;

			// detected UTF BOM
			if(substr($string, 0, $length) === $bom) {
				$orbx_log->dwrite('removing '.$name.' BOM');
				return substr($string, $length);
			}
		}

		return $string;
	}

	/**
	 * Properly redirect to new URL
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $url
	 */
	function redirect($url)
	{
		if(!is_string($url)) {
			trigger_error('redirect() expects parameter 1 to be string, ' . gettype($url) . ' given', E_USER_WARNING);
			return false;
		}

		if($url == '') {
			trigger_error('redirect() expects parameter 1 to be non-empty', E_USER_WARNING);
			return false;
		}

		if(!parse_url($url)) {
			trigger_error('redirect() expects parameter 1 to be a valid URL', E_USER_WARNING);
			return false;
		}

		// these may occur so we want to remove them
		$url = str_replace('&amp;', '&', $url);
		// close session if open properly
		if(session_id() != '') {
			session_write_close();
		}

		// set location header, this will redirect
		header('Location: ' . $url);
		exit();
	}

	/**
	 * sanitize for use in JavaScript
	 *
	 */
	define('STR_SANITIZE_JAVASCRIPT', 1);

	/**
	 * sanitize for use in HTML
	 *
	 */
	define('STR_SANITIZE_HTML', 2);

	/**
	 * sanitize for use in value attribute of HTML input tag (type="text")
	 * <code>
	 * <input type="text" value="<?php echo str_sanitize($my_column['title'], STR_SANITIZE_INPUT_TEXT_VALUE); ?>" />
	 * </code>
	 *
	 */
	define('STR_SANITIZE_INPUT_TEXT_VALUE', 3);

	/**
	 * sanitize for use with search bots
	 *
	 */
	define('STR_SANITIZE_SEARCHBOT', 4);

	/**
	 * sanitize for use in XML
	 *
	 */
	define('STR_SANITIZE_XML', 5);

	/**
	 * sanitize for XHTML validators
	 *
	 */
	define('STR_SANITIZE_HTML_VALIDATOR', 6);

	/**
	 * remove Carriage Returns, Linefeed and Tabs from $input
	 *
	 * @param string $input
	 * @return string
	 */
	function str_sanitize($string, $type)
	{
		if(($type == STR_SANITIZE_JAVASCRIPT) || ($type == STR_SANITIZE_XML)) {
			return str_replace(array("\r", "\n", "\t"), '', $string);
		}
		else if ($type == STR_SANITIZE_HTML) {
			$string = str_replace(' />', '/>', $string);
			return preg_replace('/>\s+/', '>', $string);
		}
		else if ($type == STR_SANITIZE_INPUT_TEXT_VALUE) {
			return str_replace('&amp;', '&', htmlspecialchars($string));
		}
		else if ($type == STR_SANITIZE_SEARCHBOT) {
			// doctype is causing a problem, remove ! and strip tags will handle the rest below
			$string = str_replace('<!DOCTYPE', '<DOCTYPE', $string);
			$string = strip_tags($string, '<html><body><head><title><meta><link><a><p><br><ul><ol><li><h1><h2><h3><h4><h5><h6><abbr><acronym><blockquote><q><script><noscript><div><img><dt><dl><dd><table><tr><td><th><tbody>');

			$remove = array(
				'@<script[^>]*?>.*?</script>@si',  	// strip out javascript
           		'@<style[^>]*?>.*?</style>@siU',    // strip style tags properly
            	'@<![\s\S]*?--[ \t\n\r]*>@',        // strip multi-line comments including CDATA
            	"' (style|class)=\"(.*?)\"'i"		// strip style and class attributes
			);
			$string = preg_replace($remove, '', $string);
		}
		elseif ($type == STR_SANITIZE_HTML_VALIDATOR) {
			$remove = array(
           		'@<object[^>]*?>.*?</object>@siU'    // Strip object tags properly
			);

			$string = preg_replace($remove, '', $string);
		}

		return $string;
	}

    /**
     * Converts HTML to plain text suitable for e-mail messages
     *
     * @author Pavle Gardijan <pavle.gardijan@gmail.com>
     * @since 2007-09-03
     * @param string $html
     * @return string
     */
    function html2text($html)
    {
    	$txt = str_replace(array("\r", "\n"), '', strip_tags($html, '<b><strong><u><i><li><br><p>'));
		// paragraph
		$txt = str_replace(array('<p>', '</p>'), array('', "\r\n"), $txt);
		// break
		$txt = str_replace(array('<br>', '<br />', '<br/>'), "\r\n", $txt);
		// bold
		$txt = str_replace(array('<b>', '</b>', '<strong>', '</strong>'), '*', $txt);
		// underline
		$txt = str_replace(array('<u>', '</u>'), '_', $txt);
		// italics
		$txt = str_replace(array('<i>', '</i>'), '/', $txt);
		// list
		$txt = str_replace(array('<li>', '</li>'), array('* ', "\r\n"), $txt);
		$txt = trim($txt);
		$txt = str_replace('&nbsp;', ' ', utf8_html_entities($txt, true));

		return $txt;
    }

    /**
     * Check if User Agent is W3C validator
     *
     * @author Pavle Gardijan <pavle.gardijan@gmail.com>
     * @return bool
     */
    function get_is_w3c_validator()
    {
    	return (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'w3c_validator') !== false);
    }

    /**
     * Enter description here...
     *
     * @author Pavle Gardijan <pavle.gardijan@gmail.com>
     * @param unknown_type $phone
     * @param unknown_type $phone_a
     * @param unknown_type $phone_b
     * @return string
     */
    function format_phone($phone, $phone_a = null, $phone_b = null)
	{
		if($phone && $phone_a && $phone_b) {
			return "($phone_a) $phone_b $phone";
		}

		if ($phone && $phone_b) {
			return "($phone_b) $phone";
		}

		// Strip out any extra characters that we do not need only keep letters and numbers
		$phone = preg_replace('/[^0-9A-Za-z]/', '', $phone);
		$len = strlen($phone);

		// Perform phone number formatting here
		if ($len == 7) {
			return preg_replace('/([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/', '$1-$2', $phone);
		}
		elseif ($len == 9) {
			return preg_replace('/([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})/', '($1) $2-$3', $phone);
		}
		elseif ($len == 10) {
			return preg_replace('/([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/', '($1) $2-$3', $phone);
		}
		/*elseif ($len == 11) {
			return preg_replace('/([0-9a-zA-Z]{1})([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/', '$1($2) $3-$4', $phone);
		}*/
		return $phone;
	}

	/*function strip_selected_tags_by_id_or_class($array_of_id_or_class, $text)
	{
		$array_quoted = array_map('preg_quote', $array_of_id_or_class);
		$name = implode('|', $array_quoted);
		$regex = '/<(\w+)\s[^>]*(?:class|id)\s*=\s*([\'"])(?:' . $name . ')\2[^>]*>.*</\\1>/isU';

		return preg_replace($regex, '', $text);
	}*/

	function strip_selected_tags_by_id_or_class($array_of_id_or_class, $text)
	{
		$array_of_id_or_class = array_map('preg_quote', $array_of_id_or_class);
		$name = implode('|', $array_of_id_or_class);
		$regex = '#<(\w+)\s[^>]*(class|id)\s*=\s*[\'"](' . $name . ')[\'"][^>]*>.*</\\1>#isU';
		return(preg_replace($regex, '', $text));
	}

    if(!function_exists('glob')) {
    	include DOC_ROOT . '/orbicon/3rdParty/php-compat/glob.php';
    }

    if(!function_exists('file_get_contents')) {
    	include DOC_ROOT . '/orbicon/3rdParty/php-compat/file_get_contents.php';
    }

    if(!function_exists('ob_clean')) {
		include DOC_ROOT . '/orbicon/3rdParty/php-compat/ob_clean.php';
	}

	if(!function_exists('range')) {
		include DOC_ROOT . '/orbicon/3rdParty/php-compat/range.php';
	}

	if(!function_exists('http_build_query')) {
		include DOC_ROOT . '/orbicon/lib/php-compat/http_build_query.php';
	}

	if(!function_exists('stripos')) {
		include DOC_ROOT . '/orbicon/3rdParty/php-compat/stripos.php';
	}

?>