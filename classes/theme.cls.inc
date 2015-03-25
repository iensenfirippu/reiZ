<?php
/*
 * Theme class, for containing display theme
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	class Theme
	{
		protected $_name = null; // foldername for the theme
		protected $_site = null; // foldername for the site (i.e. default|admin|mobile)
		
		public function GetName()		{ return $this->_name; }
		public function GetSite()		{ return $this->_site; }
		public function GetUrl()		{ return reiZ::url_append($this->_name, $this->_site); }
		
		//public function SetName($value)	{ $this->_name = $value; }
		//public function SetSite($value)	{ $this->_site = $value; }
		
		public function __construct($name='default', $site='default')
		{
			$this->_name = $name;
			$this->_site = $site;
			
			// Load theme config files // WARNING: Was theme config removed? or not created?
			$config = FOLDERTHEMES.'/'.$this->_name.'/config.cfg.inc';
			$default = FOLDERCONFIG.'/visual.cfg.inc';
			if (file_exists($config)) { include($config); }
			if (file_exists($default)) { include($default); }
		}
		
		/**
		 *@obsolete Please use GetPath() instead
		 **/
		public function GetDirectory()	{ return $this->GetPath(); }
		public function GetPath()	{ return reiZ::url_append(FOLDERTHEMES, $this->GetUrl()); }
	}
}
?>
