<?php

class reiZ_DatbaseObject
{
	protected $_type = EMPTYSTRING;
	protected $_dbtable = EMPTYSTRING;
	protected $_fields = array();
	protected $_values = array();
	protected $_indb = false;
	
	protected function __construct($type, $fields, $values)
	{
		
	}
	
	protected function Save()
	{
		if ($this->_indb) { $this->_update(); }
		else { $this->_insert(); }
	}
	
	private function _insert()
	{
		
	}
	
	private function _update()
	{
		
	}
	
	protected static function Load()
	{
		
	}
	
	protected static function Search()
	{
		
	}
	
	protected static function Find()
	{
		
	}
	
	protected function LoadAll()
	{
		
	}
}
?>