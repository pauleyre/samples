<?php

class Pagination
{
	/**
	* Current page number
	* @var	integer
	*/
	var $page;

	/**
	* Per-page value
	* @var	integer
	*/
	var $perpage;

	/**
	* Number of page links
	* @var	integer
	*/
	var $pagelinks;

	/**
	* Total number of results
	* @var	integer
	*/
	var $total;

	/**
	* Total number of pages
	* @var	integer
	*/
	var $pagecount;

	/**
	* Variable names
	* @var	array
	*/
	var $vars;

	/**
	* Constructor: sanitize incoming variables
	*
	* @param	string	Name of page number variable
	* @param	string	Name of per-page variable
	*/
	function __construct($page, $perpage)
	{
		$this->page = intval($_GET[$page]);
		$this->perpage = intval($_GET[$perpage]);
		$this->pagelinks = 4;

		$this->vars = array('p' => $page, 'pp' => $perpage);

		if($this->page <= 0) {
			$this->page = 1;
		}

		// default
		if($this->perpage <= 0) {
			$this->perpage = 10;
		}
		// max
		if($this->perpage > 100) {
			$this->perpage = 100;
		}

		$this->perpage = intval($this->perpage);
	}

	/**
	 * constructor for PHP 4
	 *
	 * @param string $page
	 * @param string $perpage
	 */
	function Pagination($page, $perpage)
	{
		$this->__construct($page, $perpage);
	}

	/**
	* Takes the variables and splits up the pages
	*
	* @access	protected
	*/
	function split_pages()
	{
		$this->pagecount = ceil($this->total / $this->perpage);
		if($this->pagelinks == 0) {
			$this->pagelinks = $this->pagecount;
		}
	}

	/**
	* Returns the lower limit of the pages
	*
	* @access	public
	*
	* @param	integer	Page number
	*
	* @return	integer	Lower result limit
	*/
	function fetch_limit($page = null)
	{
		if($page === null) {
			$page = $this->page;
		}

		$limit = $page * $this->perpage;

		if($page < 1) {
			$page = 1;
			$limit = 0;
		}
		else if($page > $this->pagecount) {
			$page = $this->pagecount - 1;
			$limit = $this->total;
		}

		if($limit < 0) {
			return 0;
		}
		else if($limit > $this->total) {
			return $this->total;
		}
		else {
			return $limit;
		}
	}

	/**
	* Constructs the page navigator
	*
	* @access	public
	*
	* @param	string	Base link path
	*
	* @return	string	Generated HTML page navigator
	*/
	function construct_page_nav($baselink)
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

		$baselink2 = ($baselink == './?') ? './' : $baselink;

		if(substr($baselink2, -5) == '&amp;') {
			$baselink2 = substr($baselink2, 0, -5);
		}

		if(substr($baselink2, -1) == '=') {
			$baselink2 = substr($baselink2, 0, -1);
		}

		//var_dump($baselink2);

		// first page number in page nav
		$startpage = $this->page - $this->pagelinks;
		if($startpage < 1) {
			$startpage = 1;
		}

		// last page number in page nav
		$endpage = $this->page + $this->pagelinks;
		if($endpage > $this->pagecount) {
			$endpage = $this->pagecount;
		}

		// prev page in page nav
		$prevpage = $this->page - 1;
		if($prevpage < 1) {
			$prevpage = 1;
		}

		// next page in page nav
		$nextpage = $this->page + 1;
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

			if($nolink == true) {
				$pagebits[] .= "<span class=\"current\">$i</span>";
			}
			else {
				if($i == 1) {
					$pagebits[] .= '<a class="bit" href="'.$baselink2.'">1</a>';
				}
				else {
					$pagebits[] .= "<a class=\"bit\" href=\"$baselink{$this->vars['p']}=$i&amp;{$this->vars['pp']}={$this->perpage}\">$i</a>";
				}
			}
		}

		$pagebits = implode(', ', $pagebits);

		$pagenav = '<div class="pagination">';

		if($show['first'] == true) {
			//$pagenav .= "<a class=\"first\" href=\"$baselink{$this->vars['p']}=1&amp;{$this->vars['pp']}={$this->perpage}\">Prva</a> ...";
			$pagenav .= '<a class="first" href="'.$baselink2.'">Prva</a> ...';
		}

		if($show['prev'] == true) {
			if($prevpage == 1) {
				$pagenav .= '<a class="previous" href="'.$baselink2.'">Prethodna</a> ...';
			}
			else {
				$pagenav .= "<a class=\"previous\" href=\"$baselink{$this->vars['p']}=$prevpage&amp;{$this->vars['pp']}={$this->perpage}\">Prethodna</a> ...";
			}
		}

		$pagenav .= $pagebits;
		// free memory
		unset($pagebits);

		if($show['next'] == true) {
			$pagenav .= "... <a class=\"next\" href=\"$baselink{$this->vars['p']}=$nextpage&amp;{$this->vars['pp']}={$this->perpage}\">Sljedeća</a>";
		}

		if($show['last'] == true) {
			$pagenav .= "... <a class=\"last\" href=\"$baselink{$this->vars['p']}={$this->pagecount}&amp;{$this->vars['pp']}={$this->perpage}\">Zadnja</a>";
		}

		// free memory
		unset($show);

		$pagenav .= '</div>';

		return $pagenav;
	}
}

?>