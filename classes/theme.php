<?php
/*
 * Theme class, for containing display theme
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
class Theme
{
	protected $_name = null;
	
	public function __construct($name)
	{
		$this->_name = $name;
	}
	
	public function GetDirectory()	{ return FOLDERTHEMES.'/'.$this->_name; }
}
?>
