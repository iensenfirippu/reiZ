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
		
		public function __construct($name=EMPTYSTRING, $title=EMPTYSTRING, $author='John Doe', $version=0.1, $description=EMPTYSTRING)
		{
			$this->_name = $name;
			$this->_title = $title;
			$this->_author = $author;
			$this->_version = $version;
			$this->_description = $description;
		}
		
		public function GetName()			{ return $this->_name;				}
		public function GetTitle()			{ return $this->_title;				}
		public function GetAuthor()			{ return $this->_author;			}
		public function GetVersion()		{ return $this->_version;			}
		public function GetDescription()	{ return $this->_description;		}
		public function GetStylesheets()	{ return $this->_stylesheets;		}
		public function GetJavascripts()	{ return $this->_javascripts;		}
		
		public function GetPath()		{ return reiZ::url_append(FOLDERMODULES, $this->_name);	}
		public function GetClassPath()	{ return reiZ::url_append($this->GetPath(), FOLDERCLASSES);	}
		public function GetLayoutPath()	{ return reiZ::url_append($this->GetPath(), FOLDERLAYOUTS);	}
		public function GetStylePath()	{ return reiZ::url_append($this->GetPath(), FOLDERSTYLES);	}
		public function GetScriptPath()	{ return reiZ::url_append($this->GetPath(), FOLDERLAYOUTS);	}
		
		public function Initialize()
		{
			$this->LoadAll();
		}
		
		public function Install()
		{
			// Do something when the module is enabled/installed
			// Like create filestructure, database tables etc.
		}
		
		public function Uninstall()
		{
			// Do something when the module is disabled/uninstalled
			// Like cleaning up filestructure, database tables etc.
		}
		
		public function isInstalled()
		{
			// TODO: Module installation management 
			return true;
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
		
		public function TranslateBreadcrumb($breadcrumb)
		{
			// This method is for implenting "translation" of breadcrumbs.
			// E.G. from "prod-cat-86" to "e-books". This logic can be customized for every module.
			// If not implemented in the child class, the given breadcrumb will be unaffected.
		}
		
		public function LoadAll()
		{
			$this->LoadClasses();
			$this->LoadLayouts();
			$this->LoadStyles();
			$this->LoadScripts();
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
