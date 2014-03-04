<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
class AdminMenu
{
	public static function GetHtml()
	{
		$html = new HtmlElement('ul', 'class="menu"');
		$current = '';//$GLOBALS['PAGE']->GetName();
		$pages = array(array('exit', '/'));
		$ignore = array('admin', 'default');
		$search = array(FOLDERADMIN.'/', '.php');
		$replace = array('', '');
		foreach (glob(FOLDERADMIN.'/*.php') as $page)
		{
			$name = str_replace($search, $replace, $page);
			if (!in_array($name, $ignore))
			{
				array_push($pages, array($name, '/'.ADMINPAGE.'/'.$name.'/'));
			}
		}
		$search = array(FOLDERMODULES.'/', '/admin.php');
		foreach (glob(FOLDERMODULES.'/*/admin.php') as $page)
		{
			$name = str_replace($search, $replace, $page);
			array_push($pages, array($name, '/'.ADMINPAGE.'/'.$name.'/'));
		}
				
		foreach ($pages as $page)
		{
			$putextra = '';
			if ($page == $current) { $putextra = 'class="selected" '; }
			$html->AddChild(
				new HtmlElement('li', '', '',
					new HtmlElement('a', $putextra.'href="'.$page[1].'"', $page[0])
				)
			);
		}
		return $html;
	}
}
?>
