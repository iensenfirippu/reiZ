<?php
/* ######################################################
Generic Database Object for reiZ CMS
Class to easily map a database table to a class.
###################################################### */
// TODO: Enable excluding fields from loadall
// TODO: Add timestamps for created inserted, updated, deleted (requires delete function to set deeted rather than actually deleting)

if (defined('reiZ') or exit(1))
{
	define('reiZ_GDO_DESCR', 'reiZ generic database object: ');
	
	class reiZ_DatabaseObject
	{
		protected static $_key = "uuid";
		protected static $_fields = array("");
		//protected static $_keyindex = 0;
		protected static $_orderindex = -1;
		protected static $_orderdirection = "ASC";
		protected static $_itemsperpage = ITEMSPERPAGE;
		protected static $_type = EMPTYSTRING;
		protected static $_dbtable = EMPTYSTRING;
		
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
			return reiZ_GDO_DESCR.$this::$_type."(".$this::$_key."=".$this->_id.")";
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
			
			if (!$this->_indb)
			{
				$query->SetType('select');
				$isunique = false;
				while (!$isunique)
				{
					$query->SetType('select');
					$query->AddField('COUNT('.self::$_key.') AS count');
					$query->AddCondition(self::$_key, '=', $this->_id);
					$row = $GLOBALS['DB']->GetArray($GLOBALS['DB']->RunQuery($query));
					if ($row['count'] == 0) { $isunique = true; }
					else { $this->_id = SS_UUID::Create(); }
				}
				$query->ClearFields();
				$query->ClearConditions();
				
				$query->SetType('insert');
				$query->AddField($this::$_key, $this->_id);
				$query->AddField('inserted', time());
				for ($i = 0; $i < sizeof($this->_values); $i++)
				{
					$field = each($this->_values);
					$query->AddField($field[0], $field[1]);
				}
			}
			else
			{
				$query->SetType('update');
				$query->AddField('updated', time());
				for ($i = 0; $i < sizeof($this->_values); $i++)
				{
					$field = each($this->_values);
					$query->AddField($field[0], $field[1]);
				}
				$query->AddCondition($this::$_key, '=', $this->_id);
			}
			
			$result = $GLOBALS['DB']->RunNonQuery($query);
			
			if ($result) { $this->_indb = true; }
			
			return $result;
		}
		
		protected static function Load($conditions)
		{
			$object = null;
			
			$query = new Query();
			$query->SetType('select');
			$query->AddField(static::$_key);
			for ($i = 0; $i < sizeof(static::$_fields); $i++)
			{
				$query->AddField(static::$_fields[$i]);
			}
			$query->AddTable(DBPREFIX.static::$_dbtable);
			for ($i = 0; $i < sizeof($conditions); $i++)
			{
				$condition = each($conditions);
				$query->AddCondition($condition[0], '=', $condition[1]);
				//if ($condition[0] == static::$_key) { $query->RemoveField($condition[0]); }
			}
			$result = $GLOBALS['DB']->RunQuery($query);
			$row = $GLOBALS['DB']->GetArray($result);
			
			if ($row != null) { $object = $row; }
			
			return $object;
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
			
			if ($this->_indb)
			{
				$query = new Query();
				$query->AddTable(DBPREFIX.static::$_dbtable);
					
				if($final)
				{
					$query->SetType('delete');
					$query->AddCondition(self::$_key, '=', $this->_id);
					
					$result = $GLOBALS['DB']->RunNonQuery($query);
					
					if ($result) { $this->_indb = false; }
				}
				else
				{
					$query->SetType('update');
					$query->AddField('deleted', time());
					$query->AddCondition(self::$_key, '=', $this->_id);
					
					$result = $GLOBALS['DB']->RunNonQuery($query);
				}
			}
			
			return $result;
		}
		
		protected function Undelete()
		{
			$result = false;
			
			if ($this->_indb)
			{
				$query = new Query();
				$query->SetType('update');
				$query->AddTable(DBPREFIX.static::$_dbtable);
				$query->AddField('deleted', null);
				$query->AddCondition(self::$_key, '=', $this->_id);
				$result = $GLOBALS['DB']->RunNonQuery($query);
			}
			
			return $result;
		}
	}
}
?>