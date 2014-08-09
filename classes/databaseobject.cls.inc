<?php
/* ######################################################
Generic Database Object for reiZ CMS
Class to easily map a database table to a class.
###################################################### */
// TODO: Enable excluding fields from loadall
// TODO: Fix the way innerjoins are handled, to allow for automatic inclusion of table-names, rather than the current static always-on solution.

if (defined('reiZ') or exit(1))
{
	define('reiZ_GDO_DESCR', 'reiZ generic database object: ');
	
	class reiZ_DatabaseObject
	{
		protected static $_key = 'uuid';
		protected static $_fields = array('');
		//protected static $_keyindex = 0;
		protected static $_orderindex = -1;
		protected static $_orderdirection = 'ASC';
		protected static $_itemsperpage = ITEMSPERPAGE;
		protected static $_type = EMPTYSTRING;
		protected static $_dbtable = EMPTYSTRING;
		protected static $_dbtable_dbo = 'dbo';
		
		protected $_id = null;
		protected $_values = array();
		protected $_indb = false;
		
		public function GetID()				{ return $this->_id; }
		//protected function SetID($value)	{ $this->_id = $value; }
		
		protected function __construct($values)
		{
			if (is_array($values))
			{
				if (isset($values[static::$_key]))	{ $this->_id = $values[static::$_key]; }
			}
			
			if ($this->_id == null)
			{
				$this->_id = UUID::Create();
				//$this->_created = time();
			}
		}
		
		public function __tostring()
		{
			return reiZ_GDO_DESCR.$this::$_type.'('.$this::$_key.'='.$this->_id.')';
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
			$query->AddTable(DBPREFIX.$this::$_dbtable);
			$query->AddInnerJoin('', static::$_dbtable_dbo, 'id', static::$_dbtable_dbo.'.id');
			
			if (!$this->_indb)
			{
				$this->EnsureUniqueId();
				
				$query->ClearFields();
				$query->ClearConditions();
				$query->SetType(DB_QueryType::Insert);
				$query->AddField($this::$_key, $this->_id);
				$query->AddField(static::$_dbtable_dbo.'.added', time());
				$query->AddField(static::$_dbtable_dbo.'.type', static::$_dbtable);
				for ($i = 0; $i < sizeof($this->_values); $i++)
				{
					$field = each($this->_values);
					$query->AddField($field[0], $field[1]);
				}
			}
			else
			{
				$query->SetType(DB_QueryType::Update);
				$query->AddField(static::$_dbtable_dbo.'.updated', time());
				for ($i = 0; $i < sizeof($this->_values); $i++)
				{
					$field = each($this->_values);
					$query->AddField($field[0], $field[1]);
				}
				$query->AddCondition($this::$_key, DBOP::Is, $this->_id);
			}
			
			$result = $GLOBALS['DB']->RunNonQuery($query);
			
			if ($result) { $this->_indb = true; }
			
			return $result;
		}
		
		protected static function Load($conditions)
		{
			return self::RunLoadQuery(self::PrepareLoadQuery($conditions));			
		}
		
		protected static function PrepareLoadQuery($conditions)
		{
			$query = new Query();
			$query->SetType('select');
			$query->AddField(static::$_dbtable.'.'.static::$_key);
			/*for ($i = 0; $i < sizeof(static::$_fields); $i++)
			{
				$query->AddField(static::$_fields[$i]);
			}*/
			foreach (static::$_fields as $field)
			{
				$query->AddField(static::$_dbtable.'.'.$field);
			}
			$query->AddTable(DBPREFIX.static::$_dbtable);
			//for ($i = 0; $i < sizeof($conditions); $i++)
			foreach ($conditions as $condition)
			{
			//	$condition = each($conditions);
				$query->AddCondition(static::$_dbtable.'.'.$condition[0], $condition[1], $condition[2]);
				//if ($condition[0] == static::$_key) { $query->RemoveField($condition[0]); }
			}
			return $query;
		}
		
		protected static function RunLoadQuery($query)
		{
			//$object = null;
			
			$result = $GLOBALS['DB']->RunQuery($query);
			return $GLOBALS['DB']->GetArray($result);
			
			//if ($row != null) { $object = $row; }
			//return $object;
		}
		
		protected static function LoadAll($conditions = null, $limit_start = null, $limit_amount = null, $order_by = null, $order_direction = null)
		{
			if ($limit_start == null) { $limit_start = 0; }
			if ($limit_amount == null) { $limit_amount = static::$_itemsperpage; }
			if ($order_by == null) { $order_by = static::GetOrderBy(); }
			if ($order_direction == null) { $order_direction = static::$_orderdirection; }
			
			$array = array();
			
			$query = new Query();
			$query->SetType('select');
			for ($i = 0; $i < sizeof(static::$_fields); $i++)
			{
				$query->AddField(static::$_fields[$i]);
			}
			$query->AddTable(DBPREFIX.static::$_dbtable);
			for ($i = 0; $i < sizeof($conditions); $i++)
			{
				$condition = each($conditions);
				$query->AddCondition($condition[0], '=', $condition[1]);
			}
			$query->SetOrderBy($order_by, $order_direction);
			$query->SetLimit($limit_start, $limit_amount);
			
			$result = $GLOBALS['DB']->RunQuery($query);
			//var_dump($query->__tostring());
			echo $GLOBALS['DB']->GetError();
			while ($row = $GLOBALS['DB']->GetArray($result))
			{
				array_push($array, $row);
			}
			
			return $array;
		}
		
		protected function Delete($final = true)
		{
			$result = false;
			$query = new Query();
			
			// Delete from class table
			if ($this->_indb)
			{
				$query->SetType(DB_QueryType::Delete);
				$query->SetTable(DBPREFIX.static::$_dbtable);
				$query->AddCondition(self::$_key, DB_Operator::Is, $this->_id);
				
				$result = $GLOBALS['DB']->RunNonQuery($query);
				
				if ($result) { $this->_indb = false; }
			}
			
			// Update or Delete from DBO table
			$query->SetTable(DBPREFIX.static::$_dbtable_dbo);
			if ($final)
			{
				$query->SetType(DB_QueryType::Delete);
			}
			else
			{
				// if not final delete, store serialized object in database
				$query->SetType(DB_QueryType::Update);
				$query->SetTable(DBPREFIX.static::$_dbtable_dbo);
				$query->AddField('deleted', time());
				$query->AddField('note', $this->__tostring());
				$query->AddField('object', serialize($this));
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
			$query->AddTable(DBPREFIX.static::$_dbtable_dbo);
			$query->AddCondition(self::$_key, DBOP::Is, $this->_id);
			$result = $GLOBALS['DB']->RunQuery($query);
			if ($result)
			{
				$row = $GLOBALS['DB']->GetArray($result);
				if ($result = (isset($row['object']) && $row['object'] != null))
				{
					$object = unserialize($row['object']);
					
					if ($result = $object->Save())
					{
						$query->SetType(DBQT::Update);
						$query->AddField('deleted', 0);
						$query->AddField('note', EMPTYSTRING);
						$query->AddField('object', null);
						$result = $GLOBALS['DB']->RunNonQuery($query);
					}
				}
			}
			
			return $result;
		}
		
		protected function EnsureUniqueId()
		{
			$isunique = false;
			
			if (!$this->_indb) // If object was loaded from database, return false
			{
				while (!$isunique)
				{
					$query->SetType('select');
					$query->AddField('COUNT('.self::$_key.') AS count');
					$query->AddTable(DBPREFIX.static::$_dbtable_dbo);
					$query->AddCondition(self::$_key, DBOP::Is, $this->_id);
					$row = $GLOBALS['DB']->GetArray($GLOBALS['DB']->RunQuery($query));
					if ($row['count'] == 0) { $result = $isunique; }
					else { $this->_id = UUID::Create(); }
				}
			}
			
			return $isunique;
		}
	}
}

foreach(glob(FOLDERCLASSES.'/dbo/*.inc') as $classfile) { include_once($classfile); }
?>