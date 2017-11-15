<?php
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains the definition of paginition in HTML
	 **/
	class RTK_Pagination extends RTK_List
	{
		/**
		 * A widget containing the links to different pages for a common URL
		 * @param string $baseurl The base part of the URL that all links in the paginition shares
		 * @param integer $amount The amount of items to divide into pages
		 * @param integer $perpage The amount of items per page
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 **/
		public function __construct($baseurl, $amount, $perpage, $page, $args=null)
		{
			HtmlAttributes::Assure($args);
			$args->Add('class', 'pagination');
			
			if ($amount > $perpage || PAGINATION_SHOWEMPTY)
			{
				$firstpage = 1;
				$lastpage = ceil($amount / $perpage);
				$lowerlimit = ($page - PAGINATION_LINKS);
				$upperlimit = ($page + PAGINATION_LINKS);
				$nolink = '&nbsp;';
				$items = array();
				
				// First, previous
				if ($page > $firstpage)
				{
					$items[] = new RTK_Link(new URL(array($baseurl, $firstpage)), PAGINATION_FIRST);
					$items[] = new RTK_Link(new URL(array($baseurl, ($page-1))), PAGINATION_PREV);
				}
				else
				{
					$items[] = $nolink;
					$items[] = $nolink;
				}
				
				// Available page numbers
				for ($i = $lowerlimit; $i <= $upperlimit; $i++)
				{
					if ($i >= $firstpage && $i <= $lastpage)
					{
						if ($i == $page) { $items[] = new HtmlElement('li', array('class' => 'current'), $page); }
						else { $items[] = new RTK_Link(new URL(array($baseurl, $i)), $i); }
					}
					else { $items[] = $nolink; }
				}
				
				// Next Page, Last Page
				if ($page < $lastpage)
				{
					$items[] = new RTK_Link(new URL(array($baseurl, ($page+1))), PAGINATION_NEXT);
					$items[] = new RTK_Link(new URL(array($baseurl, $lastpage)), PAGINATION_LAST);
				}
				else
				{
					$items[] = $nolink;
					$items[] = $nolink;
				}
			}
			
			parent::__construct($items, $args);
			
			
			
			
			//if ($amount > $perpage || PAGINATION_SHOWEMPTY)
			//{
				//parent::__construct('ul', $args);
				//$this->AddAttribute('class', 'pagination');
				//$firstpage = 1;
				//$lastpage = ceil($amount / $perpage);
				//$lowerlimit = ($page - PAGINATION_LINKS);
				//$upperlimit = ($page + PAGINATION_LINKS);
				//$nolink = new HtmlElement('li', EMPTYSTRING, '&nbsp;');
				
				//// First, previous
				//if ($page > $firstpage)
				//{
				//	$this->AddLink(new URL(array($baseurl, $firstpage)), PAGINATION_FIRST);
				//	$this->AddLink(new URL(array($baseurl, ($page-1))), PAGINATION_PREV);
				//}
				//else
				//{
				//	$this->AddChild($nolink);
				//	$this->AddChild($nolink);
				//}
				//
				//// Available page numbers
				//for ($i = $lowerlimit; $i <= $upperlimit; $i++)
				//{
				//	if ($i == $page) { $this->AddChild(new HtmlElement('li', array('class' => 'current'), $page)); }
				//	elseif ($i >= $firstpage && $i <= $lastpage)
				//	{
				//		$this->AddLink(new URL(array($baseurl, $i)), $i);
				//	}
				//	else { $this->AddChild($nolink); }
				//}
				
				// Next Page, Last Page
				//if ($page < $lastpage)
				//{
				//	$this->AddLink(new URL(array($baseurl, ($page+1))), PAGINATION_NEXT);
				//	$this->AddLink(new URL(array($baseurl, $lastpage)), PAGINATION_LAST);
				//}
				//else
				//{
				//	$this->AddChild($nolink);
				//	$this->AddChild($nolink);
				//}
			//}
			//else
			//{
			//	parent::__construct();
			//}
			
			//parent::__construct($items, $args);
		}
		
		private function AddLink($url, $title) {
			if ($url instanceof URL) {
				$this->AddChild(
					new HtmlElement('li', null, null,
						new RTK_Link($url, $title)
					)
				);
			}
		}
		
		public function __tostring()
		{
			return parent::__tostring();
		}
	}
}
?>