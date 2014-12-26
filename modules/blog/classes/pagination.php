<?php

define("PAGINATIONLINKS", 3);			// amount of page-links in either direction
define("PAGINATIONFIRST", '&Lt;');		// text on button for: first page
define("PAGINATIONLAST", '&Gt;');		// text on button for: last page
define("PAGINATIONPREV", '&lt;');		// text on button for: previous page
define("PAGINATIONNEXT", '&gt;');		// text on button for: next page
define("PAGINATIONSHOWEMPTY", false);	// determines weither or not to display an empty pagination

class Pagination
{
	protected $_html = null;
	
	public function __construct($baseurl, $amount, $perpage, $page)
	{
		if ($amount > $perpage || PAGINATIONSHOWEMPTY)
		{
			$this->_html = new HtmlElement('ul', 'class="pagination"');
			$firstpage = 1;
			$lastpage = ceil($amount / $perpage);
			$lowerlimit = ($page - PAGINATIONLINKS);
			$upperlimit = ($page + PAGINATIONLINKS);
			
			// First, previous
			if ($page > $firstpage)
			{
				$this->_html->AddChild(
					new HtmlElement('li', '', '',
						new HtmlElement('a', 'href="'.$baseurl.$firstpage.'/"', PAGINATIONFIRST)
					)
				);
				$this->_html->AddChild(
					new HtmlElement('li', '', '',
						new HtmlElement('a', 'href="'.$baseurl.($page - 1).'/"', PAGINATIONPREV)
					)
				);
			}
			else
			{
				$this->_html->AddChild(new HtmlElement('li', '', '&nbsp;'));
				$this->_html->AddChild(new HtmlElement('li', '', '&nbsp;'));
			}
			
			// Available page numbers
			for ($i = $lowerlimit; $i <= $upperlimit; $i++)
			{
				
				if ($i == $page) { $this->_html->AddChild(new HtmlElement('li', 'class="current"', $page)); }
				elseif ($i >= $firstpage && $i <= $lastpage)
				{
					$this->_html->AddChild(
						new HtmlElement('li', '', '',
							new HtmlElement('a', 'href="'.$baseurl.$i.'/"', $i)
						)
					);
				}
				else { $this->_html->AddChild(new HtmlElement('li', '', '&nbsp;')); }
			}
			
			// Next Page, Last Page
			if ($page < $lastpage)
			{
				$this->_html->AddChild(
					new HtmlElement('li', '', '',
						new HtmlElement('a', 'href="'.$baseurl.($page + 1).'/"', PAGINATIONNEXT)
					)
				);
				$this->_html->AddChild(
					new HtmlElement('li', '', '',
						new HtmlElement('a', 'href="'.$baseurl.$lastpage.'/"', PAGINATIONLAST)
					)
				);
			}
			else
			{
				$this->_html->AddChild(new HtmlElement('li', '', '&nbsp;'));
				$this->_html->AddChild(new HtmlElement('li', '', '&nbsp;'));
			}
		}
		else
		{
			$this->_html = new HtmlElement();
		}
	}
	
	public function GetHtml() { return $this->_html; }
}
?>
