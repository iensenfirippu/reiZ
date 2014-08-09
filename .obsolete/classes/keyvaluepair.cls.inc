<?php
/*
 * Configuration class, for containing the config file
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	class KeyValuePair
	{
		protected $_key = EMPTYSTRING;
		protected $_value = null;
		
		public function __construct($key, $value=null)
		{
			if ($key != null)
			{
				$this->_key  = $key;
			}
			
			$this->_value  = $value;
		}
		
		public function GetValue()			{ return $this->_value; }
		public function SetValue($value)	{ $this->_value = $value; }
		
		public function __tostring()
		{
			return $this->_key."=".$this->_value;
		}
	}
}
?>
