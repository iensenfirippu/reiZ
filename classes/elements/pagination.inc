<?php

class Pagination extends HtmlElement
{
	public function __construct($baseurl, $amount, $perpage, $page)
	{
		if ($amount > $perpage || PAGINATIONSHOWEMPTY)
		{
			parent::__construct('ul', 'class="pagination"');
			$firstpage = 1;
			$lastpage = ceil($amount / $perpage);
			$lowerlimit = ($page - PAGINATIONLINKS);
			$upperlimit = ($page + PAGINATIONLINKS);
			
			// First, previous
			if ($page > $firstpage)
			{
				$this->AddChild(
					new HtmlElement('li', '', '',
						new HtmlElement('a', 'href="'.$baseurl.$firstpage.'/"', PAGINATIONFIRST)
					)
				);
				$this->AddChild(
					new HtmlElement('li', '', '',
						new HtmlElement('a', 'href="'.$baseurl.($page - 1).'/"', PAGINATIONPREV)
					)
				);
			}
			else
			{
				$this->AddChild(new HtmlElement('li', '', '&nbsp;'));
				$this->AddChild(new HtmlElement('li', '', '&nbsp;'));
			}
			
			// Available page numbers
			for ($i = $lowerlimit; $i <= $upperlimit; $i++)
			{
				
				if ($i == $page) { $this->AddChild(new HtmlElement('li', 'class="current"', $page)); }
				elseif ($i >= $firstpage && $i <= $lastpage)
				{
					$this->AddChild(
						new HtmlElement('li', '', '',
							new HtmlElement('a', 'href="'.$baseurl.$i.'/"', $i)
						)
					);
				}
				else { $this->AddChild(new HtmlElement('li', '', '&nbsp;')); }
			}
			
			// Next Page, Last Page
			if ($page < $lastpage)
			{
				$this->AddChild(
					new HtmlElement('li', '', '',
						new HtmlElement('a', 'href="'.$baseurl.($page + 1).'/"', PAGINATIONNEXT)
					)
				);
				$this->AddChild(
					new HtmlElement('li', '', '',
						new HtmlElement('a', 'href="'.$baseurl.$lastpage.'/"', PAGINATIONLAST)
					)
				);
			}
			else
			{
				$this->AddChild(new HtmlElement('li', '', '&nbsp;'));
				$this->AddChild(new HtmlElement('li', '', '&nbsp;'));
			}
		}
		else
		{
			parent::__construct();
		}
	}
	
	public function __tostring()
	{
		return parent::__tostring();
	}
	
	//public function GetHtml() { return $this; }
}
?>
