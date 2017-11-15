<?php
/*
 * Page class, for containing display theme
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
// TODO: Convert to DBO subclass

if (defined('reiZ') or exit(1))
{
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
			
			$results = Database::RunQuery($query);
			foreach ($results as $result)
			{
				array_push($this->_modules, $result['name']);
			}
		}
		
		public static function Load($id)
		{
			$masterpage = null;
			
			if (intval($id) > 0)
			{
				$query = new Query();
				$query->SetType('select');
				$query->AddFields(array('id', 'name'));
				$query->AddTable('masterpage');
				$query->AddCondition('id', '=', $id);
				
				$results = Database::RunQuery($query);
				if (sizeof($results) > 0)
				{
					$row = $results[0];
					$masterpage = new Masterpage($row['id'], $row['name']);
				}
				else { $masterpage = Masterpage::LoadByName(DEFAULTMASTER); }
			}
			else { $masterpage = Masterpage::LoadByName(DEFAULTMASTER); }
			
			return $masterpage;
		}
		
		public static function LoadAll()
		{
			$array = array();
			
			$query = new Query();
			$query->SetType('select');
			$query->AddFields(array('id', 'name'));
			$query->AddTable('masterpage');
			
			$results = Database::RunQuery($query);
			foreach ($results as $result)
			{
				array_push($array, new Masterpage($result['id'], $result['name']));
			}
			
			return $array;
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
			
			$results = Database::RunQuery($query);
			if (sizeof($results) > 0)
			{
				$row = $results[0];
				$masterpage = new Masterpage($row['id'], $row['name']);
			}
			
			return $masterpage;
		}
	}
}
?>
