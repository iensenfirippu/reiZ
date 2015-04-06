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
		protected $_author = 0;
		protected $_version = 0;
		protected $_description = EMPTYSTRING;
		protected $_html = null;
		protected $_htmlextra = array();
		protected $_stylesheets = array();
		protected $_javascripts = array();
		protected $_dbid = -1;
		protected $_dbpage = null;
		protected $_exists = true;
		protected $_hasconfig = false;
		
		public function GetName()			{ return $this->_name;			}
		public function GetTitle()			{ return $this->_title;			}
		public function GetAuthor()			{ return $this->_author;		}
		public function GetVersion()		{ return $this->_version;		}
		public function GetDescription()	{ return $this->_description;	}
		public function GetStylesheets()	{ return $this->_stylesheets;	}
		public function GetJavascripts()	{ return $this->_javascripts;	}
		public function GetId()				{ return $this->_dbid;			}
		public function GetPage()			{ return $this->_dbpage;		}
		public function Exists()			{ return $this->_exists;		}
		public function HasConfig()			{ return $this->_hasconfig;		}
		
		public function GetPath()		{ return reiZ::url_append(FOLDERMODULES, $this->_name);	}
		public function GetClassFile()	{ return reiZ::url_append($this->GetPath(), 'module.php');	}
		public function GetClassPath()	{ return reiZ::url_append($this->GetPath(), FOLDERCLASSES);	}
		public function GetLayoutPath()	{ return reiZ::url_append($this->GetPath(), FOLDERLAYOUTS);	}
		public function GetStylePath()	{ return reiZ::url_append($this->GetPath(), FOLDERSTYLES);	}
		public function GetScriptPath()	{ return reiZ::url_append($this->GetPath(), FOLDERLAYOUTS);	}
		
		public function __construct($name=EMPTYSTRING, $title=EMPTYSTRING, $author=EMPTYSTRING, $version=EMPTYSTRING, $description=EMPTYSTRING)
		{
			$this->_name = $name;
			$this->_title = $title;
			$this->_author = $author;
			$this->_version = $version;
			$this->_description = $description;
		}
		
		public static function Load($name)
		{
			$result = false;
			
			// Check if it's already loaded
			foreach ($GLOBALS['MODULES'] as $module)
			{
				if ($module instanceof Module && $module->GetName() == $name) { $result = $module; }
			}
			
			// ...or try to load it
			if (!$result)
			{
				$dir = reiZ::url_append(FOLDERMODULES, $name);
				$file = reiZ::url_append($dir, 'module.php');
				$config = reiZ::url_append($dir, 'config.inc');
				
				if (file_exists($file))
				{
					include_once($file);
					$result = end($GLOBALS['MODULES']);
				}
				elseif (file_exists($dir))
				{
					$result = new Module($name);
					$result->_exists = false;
				}
				if (file_exists($config)) { $result->_hasconfig = true; }
			}
			
			return $result;
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
			
			$DB = $GLOBALS['DB'];
			
			$query = new Query();
			$query->SetType(DBQT::Select);
			$query->AddField('name');
			$query->SetTable('module');
			
			$result = $GLOBALS['DB']->RunQuery($query);
			
			while ($row = $GLOBALS['DB']->GetArray($result))
			{
				array_push($return, $row['name']);
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
			$this->LoadAll();
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
			return new Settings();
		}
		
		public function TranslateBreadcrumb($breadcrumb)
		{
			// This method is for implenting "translation" of breadcrumbs.
			// E.G. from "prod-cat-86" to "e-books". This logic can be customized for every module.
			// If not implemented in the child class, the given breadcrumb will be unaffected.
		}
		
		public function GetTitleFromUrl($url)
		{
			return EMPTYSTRING;
		}
		
		public function LoadAll()
		{
			$this->LoadConfig();
			$this->LoadClasses();
			$this->LoadLayouts();
			$this->LoadStyles();
			$this->LoadScripts();
		}
		
		public function LoadConfig()
		{
			if ($this->HasConfig()) { include_once(reiZ::url_append($this->GetPath(), 'config.inc')); }
		}
		
		public function LoadClasses()
		{
			foreach (glob(reiZ::url_append($this->GetClassPath(), '*.php')) as $file) { include_once($file); }
		}
		
		public function LoadStyles()
		{
			foreach (glob(reiZ::url_append($this->GetStylePath(), '*.css')) as $file) { array_push($this->_stylesheets, $file); }
		}
		
		public function LoadScripts()
		{
			foreach (glob(reiZ::url_append($this->GetScriptPath(), '*.js')) as $file) { array_push($this->_javascripts, $file); }
		}
		
		public function LoadLayouts()
		{
			foreach (glob(reiZ::url_append($this->GetLayoutPath(), '*.inc')) as $file) { $this->LoadLayout($file); }
		}
		
		public function LoadLayout($path)
		{
			//$defaultlayout = reiZ::url_append($this->GetPath(), $name);
			$customlayout = reiZ::url_append($GLOBALS['THEME']->GetPath(), $path);
			if (file_exists($customlayout)) { include_once($customlayout); }
			else { include_once($path); }
		}
	}
}
?>
