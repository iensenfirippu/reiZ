<?php
/*
 * Theme class, for containing display theme
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	class Theme
	{
		protected $_name = null;
		
		public function __construct($name)
		{
			$this->_name = $name;
		}
		
		public function GetDirectory()	{ return FOLDERTHEMES.'/'.$this->_name; }
	}
}
?>
