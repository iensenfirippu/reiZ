<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		database field definition
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	1.0.0
// Date:				2016/12/03
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Field class for containing a database field (with or without content)
	 **/
	class Field
	{
		private $_name = null;
		private $_alias = null;
		private $_param = null;
		
		public function GetName() { return $this->_name; }
		public function GetAlias() { return $this->_alias; }
		public function GetParam() { return $this->_param; }
		
		public function GetValue() { return ($this->_param != null) ? $this->_param->GetValue() : null; }
		public function GetParamName() { return ($this->_param != null) ? $this->_param->GetName() : null; }
	
		public function __construct($name, $value=null, $alias=null, $type=PDO::PARAM_STR, $size=255)
		{
			$this->_name = $name;
			$this->_alias = $alias;
			
			if ($value != null) {
				$this->_param = new Param(':F_'.str_replace('.', EMPTYSTRING, $name), $value, $type, $size);
			}
		}
	}
}
?>
