<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		DBO Param definition
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
	class Param
	{
		private $_name = null;
		private $_value = null;
		private $_type = null;
		private $_size = null;
		
		public function GetName() { return $this->_name; }
		public function GetValue() { return $this->_value; }
		public function GetType() { return $this->_type; }
		public function GetSize() { return $this->_size; }
	
		public function __construct($name, $value=null, $type=null, $size=null)
		{
			$this->_name = $name;
			
			if ($value != null) {
				$this->_value = $value;
				$this->_type = $this->CheckPDOType($type);
				$this->_size = $size;
			}
		}
		
		private function CheckPDOType($type) {
			switch ($type) {
				case PDO::PARAM_BOOL:
					break;
				case PDO::PARAM_NULL:
					break;
				case PDO::PARAM_INT:
					break;
				case PDO::PARAM_LOB:
					break;
				default:
					$type = PDO::PARAM_STR;
					break;
			}
			return $type;
		}
	}
}
?>
