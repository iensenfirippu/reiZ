<?php

if (defined('reiZ') or exit(1))
{
	class Breadcrumb
	{
		protected $_url = '';
		protected $_name = '';
		
		public function __construct($url, $name)
		{
			$this->_url = $url;
			$this->_name = $name;
		}
		
		public function GetUrl() { return $this->_url; }
		public function GetName() { return $this->_name; }
		
		/*public function __construct($name, $url, $addseperator = true, $class = '')
		{
			if ($this->_count > 0)
			{
				if ($addseparator && BREADCRUMBSEPARATOR != '')
				{
					$this->_tag = 'span';
					$this->_attributes = 'class="breadcrumbseparator"', BREADCRUMBSEPARATOR)
					);
				}
			}
			
			$breadcrumbs->AddChild(
				new HtmlElement('li', $class, '',
					new HtmlElement('a', $name, $url)
				)
			);
			
			$this->_count++;
		}*/
		
		//public GetHtmlElement() { return $this->_htmlelement; }
	}
}
?>
