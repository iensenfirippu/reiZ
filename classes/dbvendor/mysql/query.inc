<?php
/**************************************************** /
/ Query class for containing a Sql query              /
/ Created by Philip Rune Jensen (me@iensenfirippu.dk) /
/ Version: 1.0.4        Date: 2014/07/24              /
/ Latest additions: Added DBQT and DBOP Enums         /
/                   Added DBPREFIX support            /
/                   GroupBy support                   /
/                   InnerJoin support                 /
/                   SetTable() readded                /
/ ****************************************************/

if (defined('reiZ') or exit(1))
{
	class Query
	{
		private $type;
		private $tables = array();
		private $fieldnames = array();
		private $fieldvalues = array();
		private $innerjoins = array();
		private $conditions = array();
		private $conditionlimiter;
		private $limit;
		private $groupby;
		private $orderby;
	
		private $query;
		private $error = false;
	
		public function __construct()
		{
			$this->conditionlimiter = 'AND';
		}
	
		public function __tostring()
		{
			switch ($this->type)
			{
				case 'select':
					$this->query = 'SELECT ';
	
					if (!empty($this->fieldnames))
					{
						$numberOfFields = sizeof($this->fieldnames) - 1;
						for ($i = 0; $i <= $numberOfFields; $i++)
						{
							$name = $this->fieldnames[$i];
							
							$this->query .= $name[0].' as '.$name[1];
							
							if ($i != $numberOfFields)
							{
								$this->query .= ', ';
							}
							else
							{
								$this->query .= ' ';
							}
						}
					}
					else
					{
						$this->query .= '* ';
					}
	
					if (!empty($this->tables))
					{
						$this->query .= 'FROM ';
					
						$numberOfTables = sizeof($this->tables) - 1;
						for ($i = 0; $i <= $numberOfTables; $i++)
						{
							$name = $this->tables[$i];
	
							$this->query .= $name;
							if ($i != $numberOfTables)
							{
								$this->query .= ',';
							}
							$this->query .= ' ';
						}
					}
	
					if (!empty($this->innerjoins))
					{
						$numberOfInnerjoins = sizeof($this->innerjoins) - 1;
						for ($i = 0; $i <= $numberOfInnerjoins; $i++)
						{
							$table1 = $this->innerjoins[$i][0];
							$table2 = $this->innerjoins[$i][1];
							$field1 = $this->innerjoins[$i][2];
							$field2 = $this->innerjoins[$i][3];
	
							$this->query .= 'INNER JOIN '.$table2.' ON '.
								$table1.'.'.$field1.'='.$table2.'.'.$field2.' ';
						}
					}
	
					if (!empty($this->conditions))
					{
						$this->query .= 'WHERE ';
					
						$numberOfConditions = sizeof($this->conditions) - 1;
						for ($i = 0; $i <= $numberOfConditions; $i++)
						{
							$name = $this->conditions[$i][0];
							$operator = $this->conditions[$i][1];
							$value = $this->conditions[$i][2];
							
							if (is_string($value))
							{
								$this->query .= $name.$operator.'"'.$value.'" ';
							}
							else
							{
								$this->query .= $name.$operator.$value.' ';
							}
							
							if ($i != $numberOfConditions)
							{
								$this->query .= $this->conditionlimiter.' ';
							}
						}
					}
	
					if (!empty($this->groupby))
					{
						$this->query .= $this->groupby.' ';
					}
	
					if (!empty($this->orderby))
					{
						$this->query .= $this->orderby.' ';
					}
	
					if (!empty($this->limit))
					{
						$this->query .= $this->limit.' ';
					}
					break;
				case 'insert':
					$this->query = 'INSERT INTO '.$this->tables[0].' (';
	
					if (!empty($this->fieldnames))
					{
						$numberOfFields = sizeof($this->fieldnames) - 1;
						for ($i = 0; $i <= $numberOfFields; $i++)
						{
							$name = $this->fieldnames[$i][0].' as '.$this->fieldnames[$i][1];
							
							$this->query .= $name;
							if ($i != $numberOfFields)
							{
								$this->query .= ', ';
							}
						}
					}
					else
					{
						$this->error = true;
						break;
					}
	
					$this->query .= ') VALUES (';
	
					if (!empty($this->fieldvalues))
					{
						$numberOfFields = sizeof($this->fieldvalues) - 1;
						for ($i = 0; $i <= $numberOfFields; $i++)
						{
							$value = $this->fieldvalues[$i];
							
							if (is_string($value))
							{
								$this->query .= '"'.$value.'"';
							}
							else
							{
								$this->query .= $value;
							}
							
							if ($i != $numberOfFields)
							{
								$this->query .= ', ';
							}
						}
					}
					else
					{
						$this->error = true;
						break;
					}
	
					$this->query .= ')';
					break;
				case 'update':
					$this->query = 'UPDATE '.$this->tables[0].' SET ';
	
					if (!empty($this->fieldnames) && !empty($this->fieldvalues))
					{
						$numberOfFields = sizeof($this->fieldnames) - 1;
						for ($i = 0; $i <= $numberOfFields; $i++)
						{
							$name = $this->fieldnames[$i][0];
							$value = $this->fieldvalues[$i];
							
							if (is_string($value))
							{
								$this->query .= $name.'="'.$value.'" ';
							}
							else
							{
								$this->query .= $name.'='.$value.' ';
							}
							
							if ($i != $numberOfFields)
							{
								$this->query .= ', ';
							}
						}
					}
					else
					{
						$this->error = true;
						break;
					}
	
					if (!empty($this->conditions))
					{
						$this->query .= 'WHERE ';
					
						$numberOfConditions = sizeof($this->conditions) - 1;
						for ($i = 0; $i <= $numberOfConditions; $i++)
						{
							$name = $this->conditions[$i][0];
							$operator = $this->conditions[$i][1];
							$value = $this->conditions[$i][2];
							
							if (is_string($value))
							{
								$this->query .= $name.$operator.'"'.$value.'" ';
							}
							else
							{
								$this->query .= $name.$operator.$value.' ';
							}
							
							if ($i != $numberOfConditions)
							{
								$this->query .= $conditionlimiter.' ';
							}
						}
					}
	
					if (!empty($this->orderby))
					{
						$this->query .= $this->orderby.' ';
					}
	
					if (!empty($this->limit))
					{
						$this->query .= $this->limit.' ';
					}
					break;
				case 'delete':
					$this->query = 'DELETE FROM '.$this->tables[0].' ';
	
					if (!empty($this->conditions))
					{
						$this->query .= 'WHERE ';
					
						$numberOfConditions = sizeof($this->conditions) - 1;
						for ($i = 0; $i <= $numberOfConditions; $i++)
						{
							$name = $this->conditions[$i][0];
							$operator = $this->conditions[$i][1];
							$value = $this->conditions[$i][2];
							
							if (is_string($value))
							{
								$this->query .= $name.$operator.'"'.$value.'" ';
							}
							else
							{
								$this->query .= $name.$operator.$value.' ';
							}
							
							if ($i != $numberOfConditions)
							{
								$this->query .= $conditionlimiter.' ';
							}
						}
					}
	
					if (!empty($this->orderby))
					{
						$this->query .= $this->orderby.' ';
					}
	
					if (!empty($this->limit))
					{
						$this->query .= $this->limit.' ';
					}
					break;
				default:
					$this->error = true;
					break;
			}
	
			if ($this->error == true)
			{
				return 'ERROR';
			}
			else
			{
				return trim($this->query).';';
			}
		}
	
		public function SetType(/*string*/$string)
		{
			$this->type = $string;
		}
	
		public function SetTable(/*string*/$name)
		{
			$name = $this->PrefixTableName($name);
			$this->tables = array($name);
		}
	
		public function AddTable(/*string*/$name)
		{
			$name = $this->PrefixTableName($name);
			array_push($this->tables, $name);
		}
	
		public function AddField(/*string*/$name, /*string*/$value=null, /*string*/$as=null)
		{
			$name = array($this->PrefixFieldName($name), $as);
			if ($name[1] == null) $name[1] = $this->TrimFieldName($name[0]);
			
			array_push($this->fieldnames, $name);
			if ($value != null)
			{
				array_push($this->fieldvalues, $value);
			}
			else
			{
				array_push($this->fieldvalues, '');
			}
		}
		
		// TODO: Make RemoveField($name)
	
		public function AddFields(/*array(string)*/$names)
		{
			foreach ($names as $name)
			{
				if (is_array($name))
					$name[0] = $this->PrefixFieldName($name[0]);
				else
					$name = array($this->PrefixFieldName($name), $this->TrimFieldName($name));
				
				array_push($this->fieldnames, $name);
				array_push($this->fieldvalues, '');
			}
		}
		
		public function ClearFields()
		{
			$this->fieldnames = array();
			$this->fieldvalues = array();
		}
	
		public function AddInnerJoin(/*string*/$table1, /*string*/$table2, /*string*/$field1, /*string*/$field2)
		{
			if ($table1 == '') { $table1 = $this->tables[0]; }
			$table1 = $this->PrefixTableName($table1);
			$table2 = $this->PrefixTableName($table2);
			array_push($this->innerjoins, array($table1, $table2, $field1, $field2));
		}
		
		public function ClearInnerJoin()
		{
			$this->innerjoins = array();
		}
		
		public function AddCondition(/*string*/$fieldname, /*string*/$operator, /*string*/$value, /*boolean*/$valueneedsquotes = true)
		{
			//if ($valueneedsquotes) { $value = '"'.$value.'"'; }
			$condition = array($this->PrefixFieldName($fieldname), $operator, $value);
			array_push($this->conditions, $condition);
		}
		
		// TODO: Make RemoveCondition($fieldname)
		
		public function ClearConditions()
		{
			$this->conditions = array();
		}
	
		public function SetConditionlimiter(/*string*/$string)
		{
			$value = 'AND';
			if (strtoupper($string) == 'OR') { $value == $string; }
			$this->conditions = $value;
		}
	
		public function SetGroupBy(/*string*/$name)
		{
			$this->groupby = 'GROUP BY '.$name;
		}
	
		public function SetOrderBy(/*string*/$name, /*string*/$direction)
		{
			$value = 'ASC';
			if (strtoupper($direction) == 'DESC') { $value = 'DESC'; }
			$this->orderby = 'ORDER BY '.$name.' '.$value;
		}
		
		public function ClearOrderBy()
		{
			$this->orderby = null;
		}
	
		public function SetLimit(/*integer*/$int1, /*integer*/$int2)
		{
			$this->limit = 'LIMIT '.$int1.', '.$int2;
		}
		
		private function PrefixTableName($name)
		{
			if (!reiZ::string_beginswith($name, DBPREFIX)) $name = DBPREFIX.$name;
			return $name;
		}
		
		private function PrefixFieldName($name)
		{
			if (reiZ::string_contains($name, '.')
				&& !reiZ::string_contains($name, DBPREFIX))
					if (($pos = strrpos($name, '(') + 1) > 1)
						$name = substr($name, 0, $pos).DBPREFIX.substr($name, $pos);
					else
						$name = DBPREFIX.$name;
			return $name;
			
			/*if (!reiZ::string_contains($name, DBPREFIX))
			{
				$pos = 0;
				if (reiZ::string_contains($name, '(', -1, $pos)) {
					$name = substr($name, 0, $pos+1).DBPREFIX.substr($name, $pos+1);
				} else {
					$name = DBPREFIX.$name;
				}
			}
			return $name;*/
			
			/*if (reiZ::string_contains($name, '.')
				&& !reiZ::string_contains($name, DBPREFIX))
					if (reiZ::string_contains($name, '(')) {
						$insertat = strpos($name, '(') + 1;
						$name = substr($name, 0, $insertat).DBPREFIX.substr($name, $insertat);
					} else { $name = DBPREFIX.$name; }
			return $name;*/
		}
		
		private function TrimFieldName($name)
		{
			$pos = strrpos($name, '.');
			if ($pos !== false)
				return substr($name, $pos + 1);
			else
				return $name;
		}
	}
	
	abstract class DB_QueryType
	{
		const Select = 'SELECT';
		const Insert = 'INSERT';
		const Update = 'UPDATE';
		const Delete = 'DELETE';
	}
	abstract class DBQT extends DB_QueryType {}
	
	abstract class DB_Operator
	{
		const Is = '=';
		const LessThan = '<';
		const MoreThan = '>';
		const IsNot = '!=';
		const LessThanOrEqual = '<=';
		const MoreThanOrEqual = '>=';
	}
	abstract class DBOP extends DB_Operator {}
	
	abstract class DB_OrderDirection
	{
		const Asc			= DB_OrderDirection::Ascending;
		const Ascending		= 'ASC';
		const Desc			= DB_OrderDirection::Descending;
		const Descending	= 'DESC';
	}
	abstract class DBOD extends DB_OrderDirection {}
}
?>
