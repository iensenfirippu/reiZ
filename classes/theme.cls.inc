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
		
		public function __construct($name='default', $site='default')
		{
			$this->_name = $name;
			$this->_site = $site;
		}
		
		public function GetDirectory()	{ return FOLDERTHEMES.'/'.$this->_name.'/'.$this->_site; }
	}
}
?>
