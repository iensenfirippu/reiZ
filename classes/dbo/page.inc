<?php
/*
 * Page class, for containing display theme
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	class Page extends reiZ_DatabaseObject
	{
		protected static $_fields = array("masterpage", "name","content","inmenu", "weight");
		protected static $_keyindex = 0;
		protected static $_orderindex = 4;
		protected static $_orderdirection = "ASC";
		protected static $_itemsperpage = 10;
		protected static $_type = "dbo_page";
		protected static $_dbtable = "page";
		
		private $_theme = null;
		private $_masterpage = null;
		private $_name = null;
		private $_content = null;
		private $_inmenu = null;
		private $_weight = null;
		//private $_file = null;
		private $_modules = null;
		private $_notices = null;
		private $_errors = null;
		
		public function GetTheme()			{ return $this->_themeobject; }
		public function GetMasterpage()		{ return $this->_masterpagebject; }
		public function GetName()			{ return $this->_name; }
		public function GetContent()		{ return $this->_content; }
		public function GetInMenu()			{ return $this->_inmenu; }
		public function GetWeight()			{ return $this->_weight; }
		//public function GetFile()			{ return $this->_file; }
		//public function GetFilename()		{ return $this->_file.".php"; }
		public function GetFilenameMaster()	{ return $this->_masterpage.'.php'; }
		public function GetModules()		{ return $this->_modules; }
		public function GetNotices()		{ return $this->_notices; }
		public function GetErrors()			{ return $this->_errors; }
		
		public function SetTheme($value)		{ $this->_theme = $value; /*$this->_values[self::$_fields[0]] = $this->_theme->GetUrl();*/ }
		public function SetMasterpage($value)	{ $this->_masterpage = $value; $this->_values[self::$_fields[0]] = $this->_masterpage->GetId(); }
		public function SetName($value)			{ $this->_values[self::$_fields[1]] = $this->_name = $value; }
		public function SetContent($value)		{ $this->_values[self::$_fields[2]] = $this->_content = $value; }
		public function SetInMenu($value)		{ $this->_values[self::$_fields[3]] = $this->_inmenu = $value; }
		public function SetWeight($value)		{ $this->_values[self::$_fields[4]] = $this->_weight = $value; }
		//public function SetFile($value)		{ $this->_values[self::$_fields[5]] = $this->_file = $value; }
		//public function SetModules($value)	{ $this->_modules = $value; }
		
		protected function __construct($values = null)
		{
			parent::__construct($values);
			
			if (is_array($values))
			{
				//if (isset($values[self::$_fields[0]]))	{ self::SetTheme		($values[self::$_fields[0]]); }
				if (isset($values[self::$_fields[0]]))	{ self::SetMasterpage	(Masterpage::Load($values[self::$_fields[0]])); }
				if (isset($values[self::$_fields[1]]))	{ self::SetName			($values[self::$_fields[1]]); }
				if (isset($values[self::$_fields[2]]))	{ self::SetContent		($values[self::$_fields[2]]); }
				if (isset($values[self::$_fields[3]]))	{ self::SetInMenu		($values[self::$_fields[3]]); }
				if (isset($values[self::$_fields[4]]))	{ self::SetWeight		($values[self::$_fields[4]]); }
				//if (isset($values[self::$_fields[5]]))	{ self::SetFile			($values[self::$_fields[5]]); }
				$this->_modules = array();
				$this->LoadModules();
			}
		}
		
		public function __tostring()
		{
			return reiZ_GDO_DESCR.$this::$_type."(".$this::$_fields[1]."=".$this->_values[$this::$_fields[1]].")";
		}
		
		public static function Create($name=EMPTYSTRING, $content=EMPTYSTRING, $masterpage=0, $inmenu=false, $weight=0, $file=EMPTYSTRING)
		{
			if		(is_a($masterpiece, "Masterpage"))	{ $masterpage = $masterpage->GetId(); }
			elseif	(!is_integer($masterpage))			{ $masterpage = 0; }
			if		(!is_bool($inmenu))					{ $inmenu = false; }
			if		(!is_integer($weight))				{ $weight = 0; }
			
			return new Page(array(
				self::$_fields[0] => $masterpage,
				self::$_fields[1] => $name,
				self::$_fields[2] => $content,
				self::$_fields[3] => $inmenu,
				self::$_fields[4] => $weight
				//self::$_fields[5] => $file
			));
		}
		
		public static function CreateNew()
		{
			return new Page();
		}
		
		public function Save()
		{
			return parent::Save();
		}
		
		public static function Load($conditions)
		{
			$query = parent::PrepareLoadQuery($conditions);
			self::InnerJoinMasterpage($query);
			$row = parent::RunLoadQuery($query);
			$object = null;
			if ($row != null)
			{
				$object = new Page($row);
			}
			else
			{
				$values = array();
				//$values[self::$_fields[0]] = 0;
				$values[self::$_fields[0]] = DEFAULTMASTER;
				$values[self::$_fields[1]] = '404';
				$values[self::$_fields[2]] = '404: not found';
				$values[self::$_fields[3]] = true;
				$values[self::$_fields[4]] = 1;
				$object = new Page($values);
			}
			$object->_indb = true;
			return $object;
		}
		
		public static function LoadAll($conditions = null, $limit_start = null, $limit_amount = null, $order_by = null, $order_direction = null)
		{
			$tos = array();
			$rows = parent::LoadAll($conditions, $limit_start, $limit_amount, $order_by, $order_direction);
			foreach ($rows as $row) { array_push($tos, new Page($row)); }
			return $tos;
		}
		
		public function Delete($final = true)
		{
			parent::Delete($final);
		}
		
		// Convenience load Methods
		
		public static function LoadFromID($id)
		{
			return self::Load(array(array(self::_key, DB_Operator::Is, $id)));
			/*$object = null;
			$row = self::Load(array(array(self::_key, DB_Operator::Is, $id)));
			if ($row != null)
			{
				$object = new Page($row);
				$object->_indb = true;
			}
			return $object;*/
		}
		
		public static function LoadByName($name)
		{
			//$object = null;
			return self::Load(array(array(self::$_fields[1],DB_Operator::Is,$name)));
			/*if ($row != null)
			{
				$object = new Page($row);
				$object->_indb = true;
			}
			return $object;*/
			//return parent::Load(array(array(self::$_fields[1],DB_Operator::Is,$name)));
		}
		
		// Custom load Methods
		
		private function LoadModules()
		{
			$query = new Query();
			$query->SetType('select');
			$query->AddFields(array('module.name', 'page_module.method'));
			$query->AddTable('module');
			$query->AddInnerJoin('', 'page_module', 'id', 'm_id');
			$query->AddCondition('page_module.p_id', '=', $this->_id);
			$result = $GLOBALS['DB']->RunQuery($query);
			while ($row = $GLOBALS['DB']->GetArray($result))
			{
				$method = null; if ($row['method'] != EMPTYSTRING) { $method = $row['method']; }
				array_push($this->_modules, array($row['name'], $method));
			}
		}
		
		public static function LoadPagenamesByWeight($limittoinmenu = true, $asc = true)
		{
			$pages = array();
			$orderdirection = 'asc';
			if (!$asc) { $orderdirection = 'desc'; }
			
			$query = new Query();
			$query->SetType('select');
			$query->AddField('name');
			$query->AddTable('page');
			$query->SetOrderby('weight', $orderdirection);
			if ($limittoinmenu) { $query->AddCondition('inmenu', '=', 1); }
			$result = $GLOBALS['DB']->RunQuery($query);
			while ($row = $GLOBALS['DB']->GetArray($result))
			{
				array_push($pages, $row['name']);
			}
			
			return $pages;
		}
		
		// Inner-joins
		
		public static function InnerJoinMasterpage($query)
		{
			$query->AddFields(array(array('masterpage.id', 'mp_id'), array('masterpage.name', 'mp_name')));
			$query->AddInnerJoin('', 'masterpage', 'masterpage', 'id');
		}
	}
}
?>