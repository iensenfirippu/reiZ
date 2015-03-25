<?php

class MenuModule extends Module
{
	public function __construct($initialize = true)
	{
		$name = 'menu';
		$title = 'Menu module';
		$author = 'Philip Jensen';
		$version = 0.1;
		$description = 'Provides a much needed navigational menu.';
		parent::__construct($name, $title, $author, $version, $description);
		
		if ($initialize) { $this->Initialize(); }
	}
	
	public function Initialize()
	{
		//foreach (glob('modules/'.$name.'/classes/*.php') as $classfile) { include_once($classfile); }
		//$this->_stylesheets = array('modules/'.$name.'/styles/style.css');
		//$this->_javascripts = array('modules/'.$name.'/js/hider.js');
	}
	
	public function GetHtml()
	{
		if ($this->_html == null)
		{
			$current = $GLOBALS['PAGE']->GetName();
			$pages = Page::LoadPagenamesByWeight();
			
			$this->_html = new HtmlElement('ul', 'class="menu"');
			foreach ($pages as $page)
			{
				$putextra = EMPTYSTRING;
				if ($page == $current) { $putextra = 'class="selected" '; }
				$this->_html->AddChild(
					new HtmlElement('li', EMPTYSTRING, EMPTYSTRING,
						new HtmlElement('a', $putextra.'href="'.URLROOT.INDEXFILE.URLPAGE.$page.'/"', $page)
					)
				);
			}
		}
		return $this->_html;
	}
}

$MODULE = new MenuModule(false);
?>
