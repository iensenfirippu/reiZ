<?php
/*
 * Page class, for containing display theme
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
// TODO: Remove hardcoded strings

if (defined('reiZ') or exit(1))
{
	class BlogCategory
	{
		private static $_dbtable = "blogcategory";
		private static $_cache = array();
		
		private $_id = EMPTYSTRING;
		private $_name = EMPTYSTRING;
		private $_title = EMPTYSTRING;
		private $_indb = false;
		
		public function GetID()				{ return $this->_id; }
		public function GetName()			{ return $this->_name; }
		public function GetTitle()			{ return $this->_title; }
		
		public function SetId($value)		{ $this->_id = $value; }
		public function SetName($value)		{ $this->_name = $value; }
		public function SetTitle($value)	{ $this->_title = $value; }
		
		private function __construct($id=null, $name=EMPTYSTRING, $title=EMPTYSTRING)
		{
			$this->_id = $id;
			$this->_name = $name;
			$this->_title = $title;
			
			static::CacheObject($this);
		}
		
		private static function CacheObject($object)
		{
			if ($object->GetID() != null)
			{
				if (!isset(static::$_cache[$object->GetID()]))
				{
					static::$_cache[$object->GetID()] = $object;
				}
			}
		}
		
		public function Save()
		{
			if ($this->_indb == true) { $this->Update(); }
			else { $this->Insert(); }
		}
		
		private function Update()
		{
			$saved = false;
			
			$query = new Query();
			$query->SetType(DBQT::Update);
			$query->SetTable(static::$_dbtable);
			$query->AddFields(array(array('name', $this->_name),array('title', $this->_name)));
			$query->AddCondition('c_id', DBOP::Is, $this->_id);
			$saved = $GLOBALS['DB']->RunNonQuery($query);
			$saved = true;
			
			return $saved;
		}
		
		private function Insert()
		{
			$saved = false;
			
			$query = new Query();
			$query->SetType(DBQT::Insert);
			$query->SetTable(static::$_dbtable);
			$query->AddField('c_id', $this->_id);
			$query->AddFields(array(array('name', $this->_name),array('title', $this->_name)));
			$result = $GLOBALS['DB']->RunNonQuery($query);
			
			if ($result != false)
			{
				$this->_indb = true;
				$this->_id = mysql_insert_id();
				$saved = true;
			}
			
			return $saved;
		}
		
		public static function LoadFromID($id)
		{
			$category = null;
			
			if (isset(static::$_cache[$id]) && static::$_cache[$id] != null)
			{
				$category = static::$_cache[$id];
			}
			else
			{
				$query = new Query();
				$query->SetType(DBQT::Select);
				$query->AddTable(static::$_dbtable);
				$query->AddFields(array('blogcategory.c_id','blogcategory.name','blogcategory.title'));
				$query->AddCondition('c_id', '=', $id);
				$results = $GLOBALS['DB']->RunQuery($query);
				$row = SetAndNotNull($results, 0) ? $results[0] : null;
				
				if ($row != null)
				{
					$category = new BlogCategory($row['c_id'], $row['name'], $row['title']);
				}
			}
			
			return $category;
		}
		
		public static function LoadFromName($name)
		{
			$category = null;
			
			foreach (static::$_cache as $object) { if ($object->GetName() == $name) { $category = $object; } }
			
			if ($category == null)
			{
				$query = new Query();
				$query->SetType(DBQT::Select);
				$query->SetTable(static::$_dbtable);
				$query->AddFields(array('blogcategory.c_id','blogcategory.name','blogcategory.title'));
				$query->AddCondition('name', '=', $name);
				$results = $GLOBALS['DB']->RunQuery($query);
				$row = SetAndNotNull($results, 0) ? $results[0] : null;
				
				if ($row != null)
				{
					$category = new BlogCategory($row['c_id'], $row['name'], $row['title']);
				}
			}
			
			return $category;
		}
		
		public static function LoadAll()
		{
			$query = new Query();
			$query->SetType(DBQT::Select);
			$query->SetTable(static::$_dbtable);
			$query->AddFields(array('blogcategory.c_id','blogcategory.name','blogcategory.title'));
			$query->SetOrderby('c_id', DBOD::Asc);
			$results = $GLOBALS['DB']->RunQuery($query);
			
			foreach ($results as $row)
			{
				new BlogCategory($row['c_id'], $row['name'], $row['title']);
			}
			
			return static::$_cache;
		}
		
		/* if we ever decide to go back to DBO for blog categories */
		
		//extends reiZ_DatabaseObject
		
		//protected static $_fields = array("name", "title");
		//protected static $_keyindex = 0;
		//protected static $_orderindex = 0;
		//protected static $_orderdirection = "ASC";
		//protected static $_itemsperpage = 10;
		//protected static $_type = "dbo_blogcategory";
		
		//public function SetName($value)		{ $this->_values[self::$_fields[0]] = $this->_name = $value; }
		//public function SetTitle($value)	{ $this->_values[self::$_fields[1]] = $this->_title = $value; }
		
		/*protected function __construct($values = null)
		{
			parent::__construct($values);
			
			if (is_array($values))
			{
				if (isset($values[self::$_fields[0]]))	{ self::SetName		($values[self::$_fields[0]]); }
				if (isset($values[self::$_fields[1]]))	{ self::SetTitle	($values[self::$_fields[1]]); }
			}
		}*/
		
		/*public static function Create($name=EMPTYSTRING, $title=EMPTYSTRING)
		{
			return new BlogCategory(array(
				self::$_fields[0] => $name,
				self::$_fields[1] => $title
			));
		}*/
		
		/*public static function CreateNew()
		{
			return new BlogCategory();
		}*/
		
		/*public static function Load($conditions)
		{
			$row = parent::Load($conditions);
			$object = null;
			if ($row != null)
			{
				$object = new BlogCategory($row);
				$object->_indb = true;
			}
			return $object;
		}*/
		
		/*public static function LoadAll($conditions=null, $limit_start=null, $limit_amount=null, $order_by=null, $order_direction=null, &$count=null)
		{
			$categories = array();
			$rows = parent::LoadAll($conditions, $limit_start, $limit_amount, $order_by, $order_direction, $count);
			foreach ($rows as $row) { array_push($categories, new BlogCategory($row)); }
			return $categories;
		}*/
		
		/*public static function LoadFromID($id)
		{
			return self::Load(array(array(self::$_key, DB_Operator::Is, $id)));
		}*/
		
		/*public static function LoadFromName($name)
		{
			return self::Load(array(array(self::$_fields[0], DBOP::Is, $name)));
		}*/
	}
}
?>
