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
		parent::Initialize();
	}
	
	public function GetHtml()
	{
		if ($this->_html == null)
		{
			$selected = $GLOBALS['PAGE']->GetName();
			$pages = Page::LoadPagenamesByWeight();
			
			$this->_html = new HtmlElement_Menu($this, $pages, $selected);
		}
		return $this->_html;
	}
}

$MODULE = new MenuModule(false);
?>
