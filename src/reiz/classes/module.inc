<?php
/*
 * Module class, for containing module code
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	class Module
	{
		protected $_name = EMPTYSTRING;
		protected $_title = EMPTYSTRING;
		protected $_author = EMPTYSTRING;
		protected $_version = 0;
		protected $_description = EMPTYSTRING;
		protected $_html = null;
		protected $_htmlextra = array();
		//protected $_stylesheets = array();
		//protected $_javascripts = array();
		protected $_dbid = -1;
		protected $_dbpage = null;
		protected $_pagename = EMPTYSTRING;
		protected $_installed = false;
		protected $_initialized = false;
		//protected $_settings = null;
		
		public function GetName() { return $this->_name; }
		public function GetTitle() { return $this->_title; }
		public function GetAuthor() { return $this->_author; }
		public function GetVersion() { return $this->_version; }
		public function GetDescription() { return $this->_description; }
		//public function GetStylesheets() { return $this->_stylesheets; }
		//public function GetJavascripts() { return $this->_javascripts; }
		public function GetId() { return $this->_dbid; }
		public function GetPage() { return $this->_dbpage; }
		public function GetPagename() { return $this->_pagename; }
		
		public function Installed() { return $this->_installed; }
		public function Exists() { return file_exists($this->GetClassFile()); }
		public function HasConfig() { return file_exists($this->GetConfigFile()); }
		public function HasIcon() { return file_exists($this->GetIcon()); }
		
		public function GetPath() { return url_append('reiz/modules', $this->_name);			}
		public function GetClassFile() { return url_append($this->GetPath(), 'module.php');		}
		public function GetConfigFile() { return url_append($this->GetPath(), 'config.inc');		}
		public function GetIcon() { return url_append($this->GetPath(), 'icon.png');		}
		public function GetClassPath() { return url_append($this->GetPath(), 'classes');	}
		public function GetLayoutPath() { return url_append($this->GetPath(), 'layouts');	}
		public function GetStylePath() { return url_append($this->GetPath(), 'styles');		}
		public function GetScriptPath() { return url_append($this->GetPath(), 'layouts');	}
		
		public function SetPageName($value) { $this->_pagename = $value; }
		
		public function __construct($name=EMPTYSTRING, $title=EMPTYSTRING, $author=EMPTYSTRING, $version=EMPTYSTRING, $description=EMPTYSTRING)
		{
			$this->_name = $name;
			$this->_title = $title;
			$this->_author = $author;
			$this->_version = $version;
			$this->_description = $description;
		}
		
		/**
		 * Tries to load a Module by a given name
		 */
		public static function Load($name)
		{
			$result = false;
			$modules = $GLOBALS['MODULES'];
			
			// Check if it's already loaded
			$result = Module::Get($name);
			
			if (!$result)
			{
				$dir = url_append('reiz/modules', $name);
				$file = url_append($dir, 'module.php');
				
				if (file_exists($file))
				{
					include_once($file);
					$result = Module::Get($name);
				}
				elseif (file_exists($dir))
				{
					$result = Module::Register(new Module($name, false), true);
				}
			}
			
			return $result;
		}
		
		public static function Execute ($module, $method=null, $args=null)
		{
			$value = null;
			
			if (is_string($module)) { $module = Module::Load($module, true); }
			if ($module instanceof Module)
			{
				//reiZ::vd($method);
				$module->Initialize();
				$function = 'GetHtml';
				//if (is_string($method)) { $function .= '_'.$method; }
				if ($args === null) { $value = call_user_func(array($module, $function), $method); }
				else { $value = call_user_func(array($module, $function), $method, $args); }
			}
			else { vd('Module "'.$module.'" not found'); }
			
			return $value;
		}
		
		public function LoadInfo()
		{
			$return = false;
			
			$DB = $GLOBALS['DB'];
			
			$query = new Query();
			$query->SetType(DBQT::Select);
			$query->AddFields(array('id', 'title', 'page'));
			$query->SetTable('module');
			$query->AddCondition('name', DBOP::Is, $this->_name);
			
			$result = $GLOBALS['DB']->RunQuery($query);
			$row = $GLOBALS['DB']->GetArray($result);
			
			if ($row['id'] != null) { $this->_dbid = $row['id']; $return = true; }
			if ($this->_title == EMPTYSTRING && $row['title'] != null) { $this->_title = $row['title']; $return = true; }
			if ($row['page'] != null) { $this->_dbpage = $row['page']; $return = true; }
			
			if ($return) { $this->LoadConfig(); }
			return $return;
		}
		
		public static function LoadInstalledNames()
		{
			$return = array();
			
			$query = new Query();
			$query->SetType(DBQT::Select);
			$query->AddField('name');
			$query->SetTable('module');
			
			$results = Database::RunQuery($query);
			foreach ($results as $result)
			{
				array_push($return, $result['name']);
			}
			
			return $return;
		}
		
		public function SetPage($value)
		{
			$result = false;
			
			if ($this->_dbpage != $value)
			{
				$DB = $GLOBALS['DB'];
				
				$query = new Query();
				$query->SetType(DBQT::Update);
				$query->AddField('page', $value);
				$query->SetTable('module');
				$query->AddCondition('name', DBOP::Is, $this->_name);
				
				$result = $GLOBALS['DB']->RunNonQuery($query);
				
				if ($result) { $this->_dbpage = $value; }
			}
			
			return $result;
		}
		
		public function Initialize()
		{
			if (!$this->_initialized)
			{
				$this->LoadAll();
				$this->_initialized = true;
			}
		}
		
		public function Install()
		{
			$return = false;
			
			if ($this->LoadInfo() == false)
			{
				$DB = $GLOBALS['DB'];
				
				$query = new Query();
				$query->SetType(DBQT::Insert);
				$query->AddField('name', $this->_name);
				$query->AddField('title', $this->_title);
				$query->SetTable('module');
				
				$result = $GLOBALS['DB']->RunNonQuery($query);
				$return = true;
			}
			
			return $return;
			
			// After calling this function from the subclass, do stuff like:
			// Create filestructure, fetch additional files, set up database tables etc.
		}
		
		public function Uninstall()
		{
			// Before calling this function from the subclass, do stuff like:
			// Backup Data, clean up filestructure, remove database tables etc.
			
			$return = false;
			
			if ($this->LoadInfo() == true)
			{
				$DB = $GLOBALS['DB'];
				
				$query = new Query();
				$query->SetType(DBQT::Delete);
				$query->SetTable('module');
				$query->AddCondition('name', DBOP::Is, $this->_name);
				
				$result = $GLOBALS['DB']->RunNonQuery($query);
				$return = true;
			}
			
			return $return;
		}
		
		public function isInstalled()
		{
			if ($this->_dbid == -1) { $this->LoadInfo(); }
			return ($this->_dbid != -1);
		}
		
		public function GetHtml()
		{
			// Alternate Html output methods can also be defined in the child class.
			// For ease of use, please stick to this naming "GetHtml_" plus the title of that output.
			// E.G. "GetHtml_Small()", "GetHtml_GalleryView($url)" etc.
			// This will allow modules to make use of each others functionality.
			// These methods should always return a valid HtmlElement object.
			return new HtmlElement("comment", "No GetHtml() method was defined in this module");
		}
		
		public function GetSettings()
		{
			// Make and return a list of the settings for the config file.
			$settings = new Settings($this->GetConfigFile());
			$settings->Load();
			return $settings;
		}
		
		/*public function LoadSettings()
		{
			// Make and return a list of the settings for the config file.
			$settings = new Settings();
			return $settings;
		}*/
		
		/*public function SaveSettings($settings)
		{
			$return = false;
			
			if ($settings instanceof Settings && $this->HasConfig())
			{
				$file = $this->GetConfigFile();
				$content = file_get_contents($file);
				
				if ($content != EMPTYSTRING)
				{
					foreach ($settings as $setting)
					{
						$search = '/define\(\s*"'.$setting->GetName().'"\s*,.+\);/';
						$replace = 'define("'.$setting->GetName().'", '.$setting->GetValueString().');';
						//if ($setting->GetType() == ST::IntegerValue) { reiZ::vdd($setting); }
						$content = preg_replace($search, $replace, $content);
					}
					
					reiZ::WriteToFile($file, $content, true);
				}
			}
			
			return $return;
		}*/
		
		/*public function TranslateBreadcrumb($breadcrumb)
		{
			// This method is for implenting "translation" of breadcrumbs.
			// E.G. from "prod-cat-86" to "e-books". This logic can be customized for every module.
			// If not implemented in the child class, the given breadcrumb will be unaffected.
		}*/
		
		public function GetTitleFromUrl($url)
		{
			return false;
		}
		
		public function LoadAll()
		{
			$this->LoadConfig();
			$this->LoadClasses();
			$this->LoadLayouts();
			$this->LoadStyles();
			$this->LoadScripts();
		}
		
		protected function LoadConfig()
		{
			//reiZ::vd($this->GetConfigFile().'='.reiZ::DisplayBoolean($this->HasConfig()));
			if ($this->HasConfig()) { include_once($this->GetConfigFile()); }
		}
		
		protected function LoadClasses()
		{
			foreach (glob(url_append($this->GetClassPath(), '*.php')) as $file) { include_once($file); }
		}
		
		protected function LoadStyles()
		{
			//foreach (glob(reiZ::url_append($this->GetStylePath(), '*.css')) as $file) { array_push($this->_stylesheets, $file); }
			foreach (glob(url_append($this->GetStylePath(), '*.css')) as $file) { $GLOBALS['HTML']->AddStylesheet($file); }
		}
		
		protected function LoadScripts()
		{
			//foreach (glob(reiZ::url_append($this->GetScriptPath(), '*.js')) as $file) { array_push($this->_javascripts, $file); }
			foreach (glob(url_append($this->GetStylePath(), '*.js')) as $file) { $GLOBALS['HTML']->AddJavascript($file); }
		}
		
		protected function LoadLayouts()
		{
			foreach (glob(url_append($this->GetLayoutPath(), '*.inc')) as $file) { $this->LoadLayout($file); }
		}
		
		protected function LoadLayout($path)
		{
			//$defaultlayout = reiZ::url_append($this->GetPath(), $name);
			$customlayout = url_append($GLOBALS['THEME']->GetPath(), $path);
			if (file_exists($customlayout)) { include_once($customlayout); }
			else { include_once($path); }
		}
		
		/* Functions for controlling the "Modules" GLOBAL */
		
		public static function InitializeModules()
		{
			if (is_array($GLOBALS['MODULES']) && empty($GLOBALS['MODULES']))
			{
				foreach (Module::LoadInstalledNames() as $name) { $GLOBALS['MODULES'][$name] = null; }
			}
		}
		
		public static function Register($module, $error=false)
		{
			if ($module instanceof Module)
			{
				$name = $module->GetName();
				if (array_key_exists($name, $GLOBALS['MODULES']))
				{
					$module->_installed = true;
					$GLOBALS['MODULES'][$name] = $module;
				}
				else
				{
					$module->_installed = false;
					if ($error == true) { $GLOBALS['MODULES']['?'.$name] = $module; }
					else { $GLOBALS['MODULES']['!'.$name] = $module; }
				}
			}
		}
		
		public static function Get($name)
		{
			$result = false;
			if (is_string($name))
			{
				if (array_key_exists($name, $GLOBALS['MODULES'])) { $result = $GLOBALS['MODULES'][$name]; }
				elseif (array_key_exists('!'.$name, $GLOBALS['MODULES'])) { $result = $GLOBALS['MODULES']['!'.$name]; }
				elseif (array_key_exists('?'.$name, $GLOBALS['MODULES'])) { $result = $GLOBALS['MODULES']['?'.$name]; }
			}
			return $result;
		}
	}
	
	Module::InitializeModules();
}
?>
