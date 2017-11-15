<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		Request class
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Class used to validate and process a request.
	 */
	class Request
	{
		private $_uri = null;
		private $_params = array();
		private $_page = null;
		
		public function __construct()
		{
			$this->_uri = trim($_SERVER['REQUEST_URI'], '/');
			if (!$this->ValidateParameters()) {
				echo "failed to validate parameters";
			}
		}
		
		public function GetParameters()     { return $this->_params; }
		public function GetParameter($name) { return key_exists($name, $this->_params) ? $this->_params : false; }
		public function GetPage()           { return $this->_page; }
		
		private function ValidateParameters()
		{
			$result = true;
			$uriparts = explode('/', $this->_uri);
			
			$count = sizeof($uriparts);
			for ($i=1; $i<$count; $i++) {
				if (!key_exists($uriparts[$i], $this->_params) && $count>$i+1) {
					$this->_params[$uriparts[$i]] = $uriparts[$i+1];
					$i++;
				} else {
					$result = false;
					$i = $count;
				}
			}
			
			if ($result) {
				$this->_page = Page::LoadByName($uriparts[0]);
			}
			
			return $result;
		}
	}
}
?>
