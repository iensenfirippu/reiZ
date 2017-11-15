<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		database condition definition
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	1.0.0
// Date:				2016/12/03
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Condition class for containing a condition in a database query
	 **/
	class Condition
	{
		private $_field = null;
		private $_operator = null;
		private $_param = null;
		
		public function GetField() { return $this->_field; }
		public function GetOperator() { return $this->_operator; }
		public function GetParam() { return $this->_param; }
		
		public function GetValue() { return ($this->_param != null) ? $this->_param->GetValue() : null; }
		public function GetParamName() { return ($this->_param != null) ? $this->_param->GetName() : null; }
	
		public function __construct($field, $operator, $value, $type=null, $size=null)
		{
			$this->_field = $field;
			$this->_operator = $operator;
			
			if ($value != null) {
				$this->_param = new Param(':C_'.str_replace('.', EMPTYSTRING, $field), $value, $type, $size);
			}
		}
	}
}
?>
