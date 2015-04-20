<?php
/*
 * Settings class, for containing config file content.
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	class Settings extends ListObject
	{
		protected $_file = EMPTYSTRING;
		protected $_valid = false;
		
		public function FileExists() { return $this->_valid; }
		
		public function __construct($file)
		{
			$this->_file = $file;
			$this->_valid = file_exists($file);
		}
		
		public function Add($name, $title, $type, $default, $options=null)
		{
			if ($this->_valid) { $this->_list[] = new Setting($name, $title, $type, $default, $options); }
		}
		
		public function Load()
		{
			if ($this->_valid)
			{
				include_once($this->_file);
				
				foreach ($this->_list as $setting)
				{
					$setting->SetValue(constant($setting->GetName()));
				}
			}
		}
		
		public function Save()
		{
			$return = false;
			
			$content = file_get_contents($this->_file);
				
			if ($content != EMPTYSTRING)
			{
				foreach ($this->_list as $setting)
				{
					$search = '/define\(\s*"'.$setting->GetName().'"\s*,.+\);/';
					$replace = 'define("'.$setting->GetName().'", '.$setting->GetValueString().');';
					
					$content = preg_replace($search, $replace, $content);
				}
				
				reiZ::WriteToFile($this->_file, $content, true);
			}
			
			return $return;
		}
	}
	
	class Setting
	{
		private $_name = EMPTYSTRING;
		private $_title = EMPTYSTRING;
		private $_type = ST::BooleanValue;
		private $_default = null;
		private $_options = null;
		private $_value = null;
		
		public function GetName()		{ return $this->_name; }
		public function GetTitle()		{ return $this->_title; }
		public function GetType()		{ return $this->_type; }
		public function GetDefault()	{ return $this->_default; }
		public function GetOptions()	{ return $this->_options; }
		public function GetValue()		{ return htmlspecialchars($this->_value); }
		
		public function SetValue($value)
		{
			$result = true;
			
			if ($this->_type == ST::IntegerValue && is_numeric($value)) { $this->_value = intval($value); }
			elseif ($this->_type == ST::BooleanValue) { $this->_value = ($value == true); }
			elseif ($this->_type == ST::StringValue && is_string($value)) { $this->_value = $value; }
			elseif ($this->_type == ST::StringList && is_string($value)) { $this->_value = $value; }
			else { $result = false; }
			//reiZ::vd($this->_value);
			
			return $result;
		}
		
		public function __construct($name, $title, $type, $default, $options=null)
		{
			$this->_name = $name;
			$this->_title = $title;
			$this->_type = $type;
			$this->_default = $default;
			$this->_options = $options;
			$this->_value = $default;
		}
		
		public function GetValueString()
		{
			$return = $this->_value.EMPTYSTRING;
			if ($this->_type == ST::StringValue || $this->_type == ST::StringList)
			{
				$return = '"'.$return.'"';
			}
			elseif ($this->_type == ST::BooleanValue)
			{
				$return = reiZ::DisplayBoolean($return);
			}
			return $return;
		}
	}
	
	abstract class SettingType
	{
		const StringValue	= 'str';
		const StringList		= 'csv';
		const IntegerValue	= 'int';
		const BooleanValue	= 'bool';
	}
	abstract class ST extends SettingType
	{
		/*const S = parent::StringValue;
		const Ss = parent::StringList;
		const I = parent::IntegerValue;
		const B = parent::BooleanValue;*/
	}
}
?>
