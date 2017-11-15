<?php
echo "<pre>";
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		Generic Database Object for reiZ CMS
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//
// TODO: Enable excluding fields from loadall
// TODO: Fix the way innerjoins are handled, to allow for automatic inclusion of table-names, rather than the current static always-on solution.
// TODO: Include timestamps as proper fields
// TODO: Allow for custom ID's, or at least to select between UUID and auto_incrementing numbers
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	// define constant for printing a database object
	// TODO: Revise
	define('reiZ_GDO_DESCR', 'reiZ generic database object: ');

	/**
	 * Class to easily map a database table to a class
	 **/	
	class reiZ_DatabaseObject
	{
		protected static $_key = 'uuid';
		protected static $_fields = array('');
		protected static $_fields_dbo = array('added', 'updated', 'deleted');
		protected static $_altered_fields = array();
		//protected static $_keyindex = 0;
		protected static $_orderindex = -1;
		protected static $_orderdirection = 'ASC';
		protected static $_itemsperpage = ITEMSPERPAGE;
		protected static $_type = EMPTYSTRING;
		protected static $_dbtable = EMPTYSTRING;
		protected static $_dbtable_dbo = 'dbo';
		protected static $_cache = array();
		
		public static function GetCachedObject($id)	{ if (isset(static::$_cache[$id])) { return static::$_cache[$id]; } else { return false; } }
		public static function CacheObject($object)	{ if (!isset(static::$_cache[$object->GetID()])) { static::$_cache[$object->GetID()] = $object; } }
		
		protected $_id = null;
		protected $_added = 0;
		protected $_updated = 0;
		protected $_deleted = 0;
		protected $_values = array();
		protected $_indb = false;
		
		public function GetID()					{ return $this->_id; }
		//protected function SetID($value)		{ $this->_id = $value; }
		
		protected function __construct($values)
		{
			if (is_array($values))
			{
				if (isset($values[static::$_key]))			{ $this->_id = $values[static::$_key]; }
				if (isset($values[self::$_fields_dbo[0]]))	{ $this->_added = intval($values[self::$_fields_dbo[0]]); }
				if (isset($values[self::$_fields_dbo[1]]))	{ $this->_updated = intval($values[self::$_fields_dbo[1]]); }
				if (isset($values[self::$_fields_dbo[2]]))	{ $this->_deleted = intval($values[self::$_fields_dbo[2]]); }
			}
			
			if ($this->_id == null)
			{
				$this->_id = UUID::Create();
				//$this->_created = time();
			}
			
			static::CacheObject($this);
		}
		
		public function __tostring()
		{
			return reiZ_GDO_DESCR.$this::$_type.'('.$this::$_key.'='.$this->_id.')';
		}
		
		public function ChangeValue($key, $value)
		{
			$result = false;
			if (array_key_exists($key, $this->_values)) {
				$this->_values[$key] = $value;
				if (!array_key_exists($key, static::$_altered_fields)) {
					static::$_altered_fields[] = $key;
				}
				$result = true;
			}
			return $result;
		}
		
		public static function GetColumns()
		{
			return static::$_fields;
		}
		
		protected static function GetOrderBy()
		{
			if (static::$_orderindex < 0) { return static::$_key; }
			else { return static::$_fields[static::$_orderindex]; }
		}
		
		protected function Save()
		{
			$result = false;
			
			$query = new Query();
			
			if (!$this->_indb) // Create new object
			{
				$this->EnsureUniqueId();
				
				$query->SetType(DBQT::Insert);
				$query->SetTable(static::$_dbtable_dbo);
				$query->AddField($this::$_key, $this->_id);
				$query->AddField(static::$_dbtable_dbo.'.added', time());
				$query->AddField(static::$_dbtable_dbo.'.type', static::$_type);
				$GLOBALS['DB']->RunNonQuery($query);
				
				$query->ClearFields();
				$query->SetTable(DBPREFIX.$this::$_dbtable);
				$query->AddField($this::$_key, $this->_id);
				for ($i = 0; $i < sizeof($this->_values); $i++)
				{
					$field = each($this->_values);
					$query->AddField($field[0], String_Sanitize($field[1]));
				}
			}
			else // Update existing object
			{
				$query->SetType(DBQT::Update);
				$query->SetTable(DBPREFIX.$this::$_dbtable);
				$query->AddInnerJoin(EMPTYSTRING, static::$_dbtable_dbo, static::$_key, self::$_key);
				$query->AddField(static::$_dbtable_dbo.'.updated', time());
				for ($i = 0; $i < sizeof($this->_values); $i++)
				{
					$field = each($this->_values);
					if (Array_Contains($this->_altered_fields, $field[0])) {
						$query->AddField($field[0], $field[1]);
					}
				}
				$query->AddCondition(static::$_dbtable_dbo.'.'.$this::$_key, DBOP::Is, $this->_id);
			}
			
			$result = $GLOBALS['DB']->RunNonQuery($query);
			if ($result) {
				$this->_indb = true;
				$this->_altered_fields = array();
			}
			
			return $result;
		}
		
		protected static function Load($conditions)
		{
			return self::RunLoadQuery(self::PrepareLoadQuery($conditions));
		}
		
		protected static function PrepareLoadQuery($conditions=null)
		{
			$query = new Query();
			$query->SetType('select');
			$query->AddField(static::$_dbtable.'.'.static::$_key);
			foreach (static::$_fields_dbo as $field)
			{
				$query->AddField(static::$_dbtable_dbo.'.'.$field);
			}
			foreach (static::$_fields as $field)
			{
				$query->AddField(static::$_dbtable.'.'.$field);
			}
			$query->AddTable(static::$_dbtable);
			$query->AddInnerJoin('', static::$_dbtable_dbo, static::$_key, self::$_key);
			
			if (is_array($conditions))
			{
				foreach ($conditions as $condition)
				{
					if (sizeof($condition) == 3) {
						$query->AddCondition($condition[0], $condition[1], $condition[2]);
					} else {
						$query->AddCondition($condition[0], DBOP::Is, $condition[1]);
					}
				}
			}
			
			return $query;
		}
		
		protected static function RunLoadQuery($query)
		{
			$result = $GLOBALS['DB']->RunQuery($query);
			return SetAndNotNull($result, 0) ? $result[0] : null;
		}
		
		protected static function LoadAll($conditions=null, $limit_start=null, $limit_amount=null, $order_by=null, $order_direction=null, &$count=null)
		{
			return self::RunLoadAllQuery(self::PrepareLoadAllQuery($conditions), $limit_start, $limit_amount, $order_by, $order_direction, $count);
		}
		
		protected static function PrepareLoadAllQuery($conditions=null)
		{
			$query = new Query();
			$query->SetType('select');
			$query->AddTable(static::$_dbtable);
			$query->AddInnerJoin(EMPTYSTRING, static::$_dbtable_dbo, static::$_key, self::$_key);
			
			if (is_array($conditions))
			{
				foreach ($conditions as $condition)
				{
					if (sizeof($condition) == 3) {
						$query->AddCondition($condition[0], $condition[1], $condition[2]);
					} else {
						$query->AddCondition($condition[0], DBOP::Is, $condition[1]);
					}
				}
			}
			
			return $query;
		}
		
		protected static function RunLoadAllQuery($query, $limit_start=null, $limit_amount=null, $order_by=null, $order_direction=null, &$count=null)
		{
			if ($limit_start == null) { $limit_start = 0; }
			if ($limit_amount == null) { $limit_amount = static::$_itemsperpage; }
			if ($order_by == null) { $order_by = static::GetOrderBy(); }
			if ($order_direction == null) { $order_direction = static::$_orderdirection; }
			
			// Finalize Query
			
			// if count variable set, fetch the amount of relevant entries in the database
			if (is_int($count)) { $count = static::CountItems(clone $query); }
			
			// Add the fields to fetch
			$query->AddField(static::$_dbtable.'.'.static::$_key);
			foreach (static::$_fields_dbo as $field)
			{
				$query->AddField(static::$_dbtable_dbo.'.'.$field);
			}
			foreach (static::$_fields as $field)
			{
				$query->AddField(static::$_dbtable.'.'.$field);
			}
			$query->SetOrderBy($order_by, $order_direction);
			$query->SetLimit($limit_start, $limit_amount);
			
			//$GLOBALS['test'] = 'yes';
			//echo "rlaq\n";
			$results = $GLOBALS['DB']->RunQuery($query);
			//vd($results);
			//echo "/rlaq\n";
			
			//vd($results);
			//$query->Debug();
			return $results;
		}
		
		private static function CountItems($query)
		{
			$result = null;
			if ($query instanceof Query) {
				$query->ClearFields();
				$query->AddField('COUNT('.static::$_dbtable.'.'.static::$_key.')', 'count');
				$result = $GLOBALS['DB']->RunQuery($query);
				$result = SetAndNotNull($result, 0) ? $result[0] : null;
				$result = SetAndNotNull($result, 'count') ? $result['count'] : null;
			}
			return $result;
		}
		
		protected function Delete($final=false)
		{
			$result = false;
			$query = new Query();
			
			// Delete from class table
			if ($this->_indb)
			{
				$query->SetType(DB_QueryType::Delete);
				$query->SetTable(static::$_dbtable);
				$query->AddCondition(self::$_key, DB_Operator::Is, $this->_id);
				
				$result = $GLOBALS['DB']->RunNonQuery($query);
				
				if ($result) { $this->_indb = false; }
			}
			
			// Update or Delete from DBO table
			$query->SetTable(static::$_dbtable_dbo);
			if ($final)
			{
				$query->SetType(DBQT::Delete);
			}
			else
			{
				// if not final delete, store serialized object in database
				$query->SetType(DB_QueryType::Update);
				$query->SetTable(static::$_dbtable_dbo);
				$query->AddField('deleted', time());
				$query->AddField('note', $this->__tostring());
				$query->AddField('object', Object_Serialize($this));
				$query->SetPrint(true);
			}
			$GLOBALS['DB']->RunNonQuery($query);
			
			return $result;
		}
		
		protected static function Undelete($id)
		{
			$result = false;
			$query = new Query();
			
			$query->SetType(DBQT::Select);
			$query->AddField('object', null);
			$query->SetTable(static::$_dbtable_dbo);
			$query->AddCondition(self::$_key, DBOP::Is, $this->_id);
			$results = $GLOBALS['DB']->RunQuery($query);
			
			foreach ($results as $result)
			{
				$object = SetAndNotNull($result, 'object') ? Object_Unserialize($result['object']) : null;
					
				if ($object != null && $object->Save())
				{
					$query->SetType(DBQT::Update);
					$query->AddField('deleted', 0);
					$query->AddField('note', EMPTYSTRING);
					$query->AddField('object', null);
					$result = $GLOBALS['DB']->RunNonQuery($query);
				}
			}
			
			return $result;
		}
		
		protected function EnsureUniqueId()
		{
			$isunique = false;
			
			if (!$this->_indb) // If object was loaded from database, return false
			{
				$query = new Query();
				$query->SetType(DBQT::Select);
				$query->AddField('COUNT('.self::$_key.')', null, 'count');
				$query->AddTable(static::$_dbtable_dbo);
				
				while (!$isunique)
				{
					$query->ClearConditions();
					$query->AddCondition(self::$_key, DBOP::Is, $this->_id);
					$results = $GLOBALS['DB']->RunQuery($query);
					
					$row = SetAndNotNull($results, 0) ? $results[0] : null;
					if (SetAndNotNull($row, 'count') && $row['count'] == 0) { $isunique = true; }
					else { $this->_id = UUID::Create(); }
				}
			}
			
			return $isunique;
		}
	}
}

//foreach(glob('reiz/classes/dbo/*.inc') as $classfile) { include_once($classfile); }
?>
