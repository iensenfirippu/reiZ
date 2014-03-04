<?php

class BlogPagination
{
	protected $_html = null;
	
	public function __construct($p, $amount, $catortag, $page)
	{
		$this->_html = new HtmlElement('ul', 'class="pagination"');
		$firstpage = 1;
		$lastpage = ceil($amount / BLOGPOSTPRPAGE);
		$lowerlimit = ($page - BLOGPAGINATIONLINKS);
		$upperlimit = ($page + BLOGPAGINATIONLINKS);
		
		// First, previous
		if ($page > $firstpage)
		{
			$this->_html->AddChild(
				new HtmlElement('li', '', '',
					new HtmlElement('a', 'href="'.URLPAGE.$p.URLARGS.$catortag.'/'.$firstpage.'/"', BLOGPAGINATIONFIRST)
				)
			);
			$this->_html->AddChild(
				new HtmlElement('li', '', '',
					new HtmlElement('a', 'href="'.URLPAGE.$p.URLARGS.$catortag.'/'.($page - 1).'/"', BLOGPAGINATIONPREV)
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
						new HtmlElement('a', 'href="'.URLPAGE.$p.URLARGS.$catortag.'/'.$i.'/"', $i)
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
					new HtmlElement('a', 'href="'.URLPAGE.$p.URLARGS.$catortag.'/'.($page + 1).'/"', BLOGPAGINATIONNEXT)
				)
			);
			$this->_html->AddChild(
				new HtmlElement('li', '', '',
					new HtmlElement('a', 'href="'.URLPAGE.$p.URLARGS.$catortag.'/'.$lastpage.'/"', BLOGPAGINATIONLAST)
				)
			);
		}
		else
		{
			$this->_html->AddChild(new HtmlElement('li', '', '&nbsp;'));
			$this->_html->AddChild(new HtmlElement('li', '', '&nbsp;'));
		}
	}
	
	public function GetHtml() { return $this->_html; }
}
?>
