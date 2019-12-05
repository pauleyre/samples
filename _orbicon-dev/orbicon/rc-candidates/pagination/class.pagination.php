<?php
/**
* Class that is responsible for paginating data. Takes passed parameters and
* creates pagination links.
*
* Here is an inline example:
* <code>
* <?php
* require_once 'class.pagination.php';
*
* $pagination = new Pagination('p', 'pp');
*
* $pagination->total = 100;
*
* $pagination->split();
*
* echo $pagination->construct('http://mypage.com/abcd.php');
*
* $pagination = null;
* ?>
* </code>
*
* @author Pavle Gardijan <pavle.gardijan@gmail.com>
* @copyright Copyright (c) 2007, Pavle Gardijan
* @package Codex
* @subpackage Pagination
* @version 1.00
* @link http://sourceforge.net/projects/codexsys
* @license http://www.gnu.org/copyleft/gpl.html GPL version 3 or any later version
* @since 2007-05-23
*
*----------------------------------------------------------------------------------------*/

class Pagination
{

	/**
	 * Default page
	 *
	 */
	const DEFAULT_PAGE = 1;

	/**
	 * Default perpage
	 *
	 */
	const DEFAULT_PERPAGE = 10;

	/**
	 * Default perpage max value
	 *
	 */
	const DEFAULT_PERPAGE_MAX = 100;

	/**
	* Current page number
	* @var	integer
	*/
	private $page;

	/**
	* Per-page value
	* @var	integer
	*/
	private $perpage;

	/**
	* Number of page links
	* @var	integer
	*/
	private $pagelinks;

	/**
	* Total number of results
	* @var	integer
	*/
	public $total;

	/**
	* Total number of pages
	* @var	integer
	*/
	private $pagecount;

	/**
	* Variable names
	* @var	array
	*/
	private $vars;

	/**
	 * Sanitize variables
	 *
	 * @param string $page
	 * @param string $perpage
	 */
	function __construct($page, $perpage)
	{
		$this->page = intval($_GET[$page]);
		$this->perpage = intval($_GET[$perpage]);
		$this->pagelinks = 4;

		$this->vars = array('p' => $page, 'pp' => $perpage);

		// sanity checks
		if($this->page <= 0) {
			$this->page = self::DEFAULT_PAGE;
		}

		if($this->perpage <= 0) {
			$this->perpage = self::DEFAULT_PERPAGE;
		}
		elseif($this->perpage > self::DEFAULT_PERPAGE_MAX) {
			$this->perpage = self::DEFAULT_PERPAGE_MAX;
		}

		$this->perpage = intval($this->perpage);
	}

	/**
	 * Split pages
	 *
	 * @access protected
	 *
	 */
	protected function split()
	{
		$this->pagecount = ceil($this->total / $this->perpage);
		if($this->pagelinks == 0) {
			$this->pagelinks = $this->pagecount;
		}
	}

	/**
	 * Construct HTML pagination
	 *
	 * @param string $baselink
	 * @return string
	 */
	public function construct($baselink)
	{
		// quick exit
		if($this->total < 1) {
			return false;
		}

		// handle base link
		if(strpos($baselink, '?') === false) {
			$baselink .= '?';
		}
		else if(!preg_match('#\?$#', $baselink) && !preg_match('#(&|&amp;)$#', $baselink)) {
			$baselink .= '&amp;';
		}

		// first page number in page nav
		$startpage = ($this->page - $this->pagelinks);
		if($startpage < 1) {
			$startpage = 1;
		}

		// last page number in page nav
		$endpage = ($this->page + $this->pagelinks);
		if($endpage > $this->pagecount) {
			$endpage = $this->pagecount;
		}

		// prev page in page nav
		$prevpage = ($this->page - 1);
		if($prevpage < 1) {
			$prevpage = 1;
		}

		// next page in page nav
		$nextpage = ($this->page + 1);
		if($nextpage > $this->pagecount) {
			$nextpage = $this->pagecount;
		}

		// show the prev page
		$show['prev'] = true;
		if($this->page == $startpage) {
			$show['prev'] = false;
		}

		// show the next page
		$show['next'] = true;
		if($this->page == $endpage) {
			$show['next'] = false;
		}

		// show the first page
		$show['first'] = false;
		if($startpage > 1) {
			$show['first'] = true;
		}

		// show the last page
		$show['last'] = false;
		if($endpage < $this->pagecount) {
			$show['last'] = true;
		}

		// construct the page bits
		$pagebits = array();
		for($i = $startpage; $i <= $endpage; $i++) {
			if($i == $this->page) {
				$nolink = true;
			}
			else {
				$nolink = false;
			}

			if($nolink) {
				$pagebits[] .= "<strong class=\"current\">$i</strong>";
			}
			else {
				$pagebits[] .= "<a class=\"bit\" href=\"$baselink{$this->vars['p']}=$i&amp;{$this->vars['pp']}={$this->perpage}\">$i</a>";
			}
		}

		$pagebits = implode(', ', $pagebits);

		$pagenav = '<div class="pagination">';

		if($show['first']) {
			$pagenav .= "<a class=\"first\" href=\"$baselink{$this->vars['p']}=1&amp;{$this->vars['pp']}={$this->perpage}\">"._L('first').'</a> ...';
		}

		if($show['prev']) {
			$pagenav .= "<a class=\"previous\" href=\"$baselink{$this->vars['p']}=$prevpage&amp;{$this->vars['pp']}={$this->perpage}\">"._L('previous').'</a> ...';
		}

		$pagenav .= $pagebits;
		// free memory
		unset($pagebits);

		if($show['next']) {
			$pagenav .= "... <a class=\"next\" href=\"$baselink{$this->vars['p']}=$nextpage&amp;{$this->vars['pp']}={$this->perpage}\">"._L('next').'</a>';
		}

		if($show['last']) {
			$pagenav .= "... <a class=\"last\" href=\"$baselink{$this->vars['p']}={$this->pagecount}&amp;{$this->vars['pp']}={$this->perpage}\">"._L('last').'</a>';
		}

		// free memory
		unset($show);

		$pagenav .= '</div>';

		return $pagenav;
	}
}

?>