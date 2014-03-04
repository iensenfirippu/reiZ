<?php
/*
 * Page class, for containing display theme
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
class Masterpage
{
	protected $_id = null;
	protected $_name = null;
	protected $_modules = null;
	
	public function __construct($id, $name)
	{
		$this->_id = $id;
		$this->_name = $name;
		$this->_modules = array();
		$this->LoadModules();
	}
	
	public function __tostring()
	{
		return $this->_name;
	}
	
	public function GetId()				{ return $this->_id; }
	public function GetName()				{ return $this->_name; }
	public function GetFilename()			{ return $this->_name.".php"; }
	public function GetModules()			{ return $this->_modules; }
	
	private function LoadModules()
	{
		$query = new Query();
		$query->SetType('select');
		$query->AddField('module.name');
		$query->AddTable('module');
		$query->AddInnerJoin('', 'masterpage_module', 'id', 'm_id');
		$query->AddCondition('masterpage_module.mp_id', '=', $this->_id);
		$result = $GLOBALS['DB']->RunQuery($query);
		while ($row = $GLOBALS['DB']->GetArray($result))
		{
			array_push($this->_modules, $row['name']);
		}
	}
	
	public static function Load($id)
	{
		$masterpage = null;
		
		$query = new Query();
		$query->SetType('select');
		$query->AddFields(array('id', 'name'));
		$query->AddTable('masterpage');
		$query->AddCondition('id', '=', $id);
		$result = $GLOBALS['DB']->RunQuery($query);
		$row = $GLOBALS['DB']->GetArray($result);
		if ($row != null)
		{
			$masterpage = new Masterpage($row['id'], $row['name']);
		}
		else
		{
			$masterpage = Masterpage::LoadByName(DEFAULTMASTER);
		}
		
		return $masterpage;
	}
	
	public static function LoadByName($name)
	{
		$masterpage = null;
		
		$query = new Query();
		$query->SetType('select');
		$query->AddFields(array('id', 'name'));
		$query->AddTable('masterpage');
		$query->AddCondition('name', '=', $name);
		$result = $GLOBALS['DB']->RunQuery($query);
		$row = $GLOBALS['DB']->GetArray($result);
		if ($row != null)
		{
			$masterpage = new Masterpage($row['id'], $row['name']);
		}
		
		return $masterpage;
	}
}
?>
