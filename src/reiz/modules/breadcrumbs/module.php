<?php
if (defined('reiZ') or exit(1))
{
	class BreadcrumbsModule extends Module
	{
		private $_breadcrumbs = array();
		private $_module = null;
		
		public function SetModule($module) { $this->_module = $module; }
		
		public function __construct($initialize = true)
		{
			$name = 'breadcrumbs';
			$title = 'Breadcrumbs';
			$author = 'Philip Jensen';
			$version = 0.1;
			$description = 'Generates breadcrumbs from the URL, for easy site navigation.';
			parent::__construct($name, $title, $author, $version, $description);
			
			if ($initialize) { $this->Initialize(); }
		}
		
		public function Initialize()
		{
			parent::Initialize();
			
			$page = GetSafeArgument(GETPAGE);
			$args = GetSafeArgument(GETARGS);
			$args = $args != EMPTYSTRING ? explode(SINGLESLASH, $args) : null;
			//$argscount = 0; if ($args != EMPTYSTRING) {
			$argscount = sizeof($args);
			//}
			$url = new URL(array($page, URLARGS));
			
			// Add breadcrumb for index/home 
			if (BREADCRUMBINDEX != EMPTYSTRING && BREADCRUMBINDEX != $page)
			{
				$this->AddBreadcrumb(BREADCRUMBINDEX, new URL(BREADCRUMBINDEX));
			}
			
			// Add breadcrumb for current page
			if ($argscount > 0 || BREADCRUMBINCLUDECURRENT)
			{
				$this->AddBreadcrumb($page, new URL($url));
			}
			
			if ($argscount > 0)
			{
				if (!BREADCRUMBINCLUDECURRENT) { $argscount--; }
				
				for ($i = 0; $i < $argscount; $i++)
				{
					$arg = $args[$i];
					$url->Append($arg);
					// TODO: use subset of $url
					$crumb = new Breadcrumb(new URL($url), $arg);
					array_push($this->_breadcrumbs, $crumb);
				}
			}
			
			/*if (reiZ::SetAndNotNull($GLOBALS, 'PAGE'))
			{
				$this->_module = $GLOBALS['PAGE']->GetModule();
			}*/
		}
		
		public function GetHtml()
		{
			if ($this->_initialized && $this->_html == null)
			{
				if ($this->_module instanceof Module)
				{
					foreach ($this->_breadcrumbs as $crumb)
					{
						$url = String_RemovePrefix($crumb->GetUrl(), BREADCRUMBROOT);
						$url = explode(SINGLESLASH, $url, 2);
						if ($url[0] == $this->_module->GetPagename())
						{
							$title = $this->_module->GetTitleFromUrl($url[1]);
							if ($title != null) { $crumb->SetName($title); }
						}
					}
				}
				
				$this->_html = new HtmlElement_Breadcrumbs($this, $this->_breadcrumbs);
				//$this->GenerateHtml();
			}
			return $this->_html;
		}
		
		/*private function GenerateHtml()
		{
			$this->_html = new HtmlElement('ul');
			$first = true;
			
			$page = reiZ::GetSafeArgument(GETPAGE);
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
		}*/
			
		/*private function MakeLink($page, $argsarray, $index)
		{
			$return = EMPTYSTRING;
			if ($argsarray[$index] != EMPTYSTRING)
			{
				$return = BREADCRUMBROOT.$page.URLARGS;
				for ($i = 0; $i <= $index; $i++) { $return .= $argsarray[$i].SINGLESLASH; }
			}
			return $return;
		}*/
		
		private function AddBreadcrumb($name, $url, $flag=0)
		{
			//debug_print_backtrace();
			array_push($this->_breadcrumbs, new Breadcrumb($url, $name));
			
			/*$class = EMPTYSTRING;
			if ($flag < 0) { $class = 'class="first"'; }
			if ($flag > 0) { $class = 'class="last"'; }
			
			$this->_html->AddChild(
				new HtmlElement('li', $class, EMPTYSTRING,
					new HtmlElement('a', 'href="'.$url.'"', $name)
				)
			);*/
		}
		
		/*private function AddSeparator()
		{
			if (BREADCRUMBSEPARATOR != EMPTYSTRING)
			{
				$this->_html->AddChild(
					new HtmlElement('li', EMPTYSTRING, EMPTYSTRING,
						new HtmlElement('span', 'class="separator"', BREADCRUMBSEPARATOR)
					)
				);
			}
		}*/
		
		public function GetSettings()
		{
			$settings = new Settings($this->GetConfigFile());
			$settings->Add('BREADCRUMBSEPARATOR',			'Separator',				ST::StringValue,	'&gt;');
			$settings->Add('BREADCRUMBROOT',					'URL root',					ST::StringValue,	URLROOT.INDEXFILE.URLPAGE);
			$settings->Add('BREADCRUMBINDEX',				'Index page',				ST::StringValue,	'home');
			$settings->Add('BREADCRUMBINCLUDECURRENT',	'Include current page',	ST::BooleanValue,	false);
			$settings->Load();
			return $settings;
		}
	}
	
	Module::Register(new BreadcrumbsModule());
}
?>