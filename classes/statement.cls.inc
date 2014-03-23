<?php
/**************************************************** /
/ Query class for containing a Sql query              /
/ Created by Philip Rune Jensen (me@iensenfirippu.dk) /
/ Version: 1.0.2        Date: 2013/09/13              /
/ Latest additions: GroupBy support                   /
/                   InnerJoin support                 /
/                   SetTable() readded                /
/                   Condition support                 /
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
							
							$this->query .= $name;
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
							$name = $this->fieldnames[$i];
							
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
							$name = $this->fieldnames[$i];
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
		
		public function SetTable(/*string*/$string)
		{
			$this->tables = array($string);
		}
		
		public function AddTable(/*string*/$string)
		{
			array_push($this->tables, $string);
		}
		
		public function AddField(/*string*/$name, /*string*/$value = null)
		{
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
			array_push($this->innerjoins, array($table1, $table2, $field1, $field2));
		}
		
		public function ClearInnerJoin()
		{
			$this->innerjoins = array();
		}
		
		public function AddCondition(/*string*/$fieldname, /*string*/$operator, /*string*/$value, /*boolean*/$valueneedsquotes = true)
		{
			//if ($valueneedsquotes) { $value = '"'.$value.'"'; }
			$condition = array($fieldname, $operator, $value);
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
	}
}
?>
