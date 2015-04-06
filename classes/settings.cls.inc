<?php
/*
 * Settings class, for containing config file content.
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	class Settings extends ListObject
	{
		public function Add($name, $title, $type, $default, $options=null)
		{
			$this->_list[] = new Setting($name, $title, $type, $default, $options);
			//$this[] = new Setting($name, $title, $default, $options);
		}
	}
	
	class Setting
	{
		private $_name = EMPTYSTRING;
		private $_title = EMPTYSTRING;
		private $_type = ST::Bool;
		private $_default = null;
		private $_options = null;
		private $_value = null;
		
		public function GetName()		{ return $this->_name; }
		public function GetTitle()		{ return $this->_title; }
		public function GetType()		{ return $this->_type; }
		public function GetDefault()	{ return $this->_default; }
		public function GetOptions()	{ return $this->_options; }
		public function GetValue()		{ return $this->_value; }
		
		public function SetValue($value)	{ $this->_value = $value; }
		
		public function __construct($name, $title, $type, $default, $options=null)
		{
			$this->_name = $name;
			$this->_title = $title;
			$this->_type = $type;
			$this->_default = $default;
			$this->_options = $options;
			$this->_value = $default;
		}
	}
	
	abstract class SettingType
	{
		const String	= 'str';
		const Strings	= 'csv';
		const Integer	= 'int';
		const Bool		= 'bool';
	}
	abstract class ST extends SettingType {}
}
?>
