<?php
//------------------------------------------------------------
// Project:		project-name
// License:		GPL v2
//
// Contents:		class-name
// Created by:		author (email)
// Class version:	1.0
// Date:				2015/11/24
//
// (opt) Examples:
// class-examples
//
// (opt) Latest additions:	reorganized class / removed redundant code
//							Added DBQT and DBOP Enums
//							Added DBPREFIX support
//							GroupBy support
//							InnerJoin support
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * class-description
	 * @extends parent-class.
	 */
	class className extends anotherClass
	{
		// define class specific constants
		const UNIVERSAL_CONSTANT = 42;
		
		// define static variables
		protected static $_static1 = 1;
		protected static $_static2 = 2;
		
		// define variables
		private $_var1 = null;
		private $_var2 = null;
		
		// define "Accessor" functions (preferably in one line each)
		public function GetVar1()	{ return $this->_var1; }
		public function GetVar2()	{ return $this->_var2; }
		
		// define "Mutator" functions (preferably in one line each)
		public function SetVar1($value)	{ $this->_var1 = $value; }
		public function SetVar2($value)	{ $this->_var2 = $value; }
		
		// define "magic functions", start with "__construct", "__tostring" (where appropriate).
		protected function __construct($args = null)
		{
			parent::__construct($args);
			
			$this::$_var1 = 2;
			$this::$_var2 = 1;
		}
		
		public function __tostring()
		{
			return $this::$_var1;
		}
		
		// followed by any custom methods (use pascal case)
		// you can optionally sort custom methods, by putting them into groups
		
		/** (optional) method-group-name -> **/
		
		/**
		 * method-description
		 * @param param, param-description.
		 */
		public function CustomMethod($param=null)
		{
			echo $param;
			return $this::$_var1 + $this::$_var2;
		}
		
		/** <- method-group-name **/
	}
}

?>