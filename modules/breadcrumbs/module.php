<?php

define("BREADCRUMBSEPARATOR", "&gt;");
define("BREADCRUMBROOT", URLROOT.INDEXFILE.URLPAGE);
define("BREADCRUMBINDEX", "home");

class BreadcrumbsModule extends Module
{
	private $_breadcrumbs = array();
	private $_module = null;
	
	public function __construct($module = null, $initialize = true)
	{
		$name = 'breadcrumbs';
		$title = 'Breadcrumbs module';
		$author = 'Philip Jensen';
		$version = 0.1;
		$description = 'Generates breadcrumbs from the URL, for easy site navigation.';
		parent::__construct($name, $title, $author, $version, $description);
		
		$_module = $module;
		if ($initialize) { $this->Initialize(); }
	}
	
	public function Initialize()
	{
		//foreach (glob('modules/'.$this->_name.'/classes/*.php') as $classfile) { include_once($classfile); }
		$this->_stylesheets = array('modules/'.$this->_name.'/styles/style.css');
		//$this->_javascripts = array('modules/'.$this->_name.'/js/hider.js');
		
		$args = GetSafeArgument(GETARGS);
		if ($args != '')
		{
			$parts = explode('/', $args);
			$url = '';
			foreach ($parts as $part)
			{
				$url .= $part.'/';
				$crumb = new Breadcrumb($url, $part);
				// TODO: Load module corresponding to page and translate
				//$module = ???;
				//$module->TranslateBreadcrumb($crumb);
				array_push($this->_breadcrumbs, $crumb);
			}
		}
	}
	
	public function GetHtml()
	{
		if ($this->_html == null)
		{
			$this->GenerateHtml();
		}
		return $this->_html;
	}
	
	private function GenerateHtml()
	{
		$this->_html = new HtmlElement('ul');
		$first = true;
		
		$page = GetSafeArgument(GETPAGE);
		if (BREADCRUMBINDEX != '' && BREADCRUMBINDEX != $page)
		{
			$first = false;
			$this->AddBreadcrumb(BREADCRUMBINDEX, BREADCRUMBROOT.BREADCRUMBINDEX.'/', -1);
		}
		if ($page != '')
		{
			$flag = 0;
			if ($first) { $flag = -1; $first = false; }
			else { $this->AddSeparator(); }
			$this->AddBreadcrumb($page, BREADCRUMBROOT.$page.'/', $flag);
		}
		
		$arraysize = sizeof($this->_breadcrumbs);
		if ($arraysize > 0)
		{
			$urlbase = BREADCRUMBROOT.$page.URLARGS;
			for ($i = 0; $i < $arraysize; $i++)
			{
				$flag = 0;
				if ($first) { $flag = -1; $first = false; }
				else { $this->AddSeparator(); }
				if ($i == $arraysize) { $flag = 1; }
				$crumb = $this->_breadcrumbs[$i];
				$this->AddBreadcrumb($crumb->GetName(), $urlbase.$crumb->GetUrl(), $flag);
			}
		}
	}
		
	private function MakeLink($page, $argsarray, $index)
	{
		$return = '';
		if ($argsarray[$index] != '')
		{
			$return = BREADCRUMBROOT.$page.URLARGS;
			for ($i = 0; $i <= $index; $i++) { $return .= $argsarray[$i].'/'; }
		}
		return $return;
	}
	
	private function AddBreadcrumb($name, $url, $flag = 0)
	{
		$class = '';
		if ($flag < 0) { $class = 'class="first"'; }
		if ($flag > 0) { $class = 'class="last"'; }
		$this->_html->AddChild(
			new HtmlElement('li', $class, '',
				new HtmlElement('a', 'href="'.$url.'"', $name)
			)
		);
	}
	
	private function AddSeparator()
	{
		if (BREADCRUMBSEPARATOR != '')
		{
			$this->_html->AddChild(
				new HtmlElement('li', '', '',
					new HtmlElement('span', 'class="separator"', BREADCRUMBSEPARATOR)
				)
			);
		}
	}
}

$MODULE = new BreadcrumbsModule(false);
?>
