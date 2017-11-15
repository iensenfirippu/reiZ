<?php
if (defined('reiZ') or exit(1))
{
	class MenuModule extends Module
	{
		public function __construct($initialize=true)
		{
			$name = 'menu';
			$title = 'Menu';
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

				$this->_html = new RTK_Menu('mainmenu', 'menu', $pages, $selected);
			}
			return $this->_html;
		}
	}

	Module::Register(new MenuModule());
	//$GLOBALS['MODULES'][] = new MenuModule(false);
}
?>
