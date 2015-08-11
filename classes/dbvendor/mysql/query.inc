<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		mySQL query definition
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	1.0.5
// Date:				2014/11/24
//
// Latest additions:	reorganized class / removed redundant code
//							Added DBQT and DBOP Enums
//							Added DBPREFIX support
//							GroupBy support
//							InnerJoin support
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Query class for containing a Sql query
	 **/
	class Query
	{
		private $query;
		private $_print = false;
		private $error = false;

		private $type;
		private $tables = array();
		private $fieldnames = array();
		private $fieldvalues = array();
		private $innerjoins = array();
		private $conditions = array();
		private $conditiondelimiter;
		private $limit = array();
		private $groupby;
		private $orderby = array();
	
		public function __construct()
		{
			$this->conditiondelimiter = DBCD::_AND;
		}

		public function SetPrint(/*bool*/$print)						{ $this->_print = $print === true; }
		public function SetType(/*string*/$string)						{ $this->type = strtoupper($string); }
		public function SetTable(/*string*/$name)						{ $this->tables = array($this->PrefixTableName($name)); } //$name = $this->PrefixTableName($name);
		public function SetConditionlimiter(/*string*/$string)			{ $value = DBCD::_AND; if (strtoupper($string) == DBCD::_OR) { $value = $string; } $this->conditions = $value; }
		public function SetGroupBy(/*string*/$name)						{ $this->groupby = $name; }
		public function SetOrderBy(/*string*/$name, /*DBOD*/$direction)	{ $value = DBOD::Asc; if ($direction == DBOD::Desc) { $value = DBOD::Desc; } $this->orderby = array($name, $value); }
		public function SetLimit(/*integer*/$int1, /*integer*/$int2)	{ $this->limit = array($int1, $int2); }
		
		public function AddTable(/*string*/$name) { array_push($this->tables, $this->PrefixTableName($name)); } //$name = $this->PrefixTableName($name);
		public function AddField(/*string*/$name, /*string*/$value=null, /*string*/$as=null) {
			$name = array($this->PrefixFieldName($name), $as);
			if ($name[1] == null) $name[1] = $this->TrimFieldName($name[0]);
			
			array_push($this->fieldnames, $name);
			if ($value != null) { array_push($this->fieldvalues, $value); }
			else { array_push($this->fieldvalues, EMPTYSTRING); }
		}
		public function AddFields(/*array(string)*/$names) {
			foreach ($names as $name)
			{
				if (is_array($name)) { $name[0] = $this->PrefixFieldName($name[0]); }
				else { $name = array($this->PrefixFieldName($name), $this->TrimFieldName($name)); }
				
				array_push($this->fieldnames, $name);
				array_push($this->fieldvalues, EMPTYSTRING);
			}
		}
		public function AddInnerJoin(/*string*/$table1, /*string*/$table2, /*string*/$field1, /*string*/$field2) {
			if ($table1 == EMPTYSTRING) { $table1 = $this->tables[0]; }
			$table1 = $this->PrefixTableName($table1);
			$table2 = $this->PrefixTableName($table2);
			array_push($this->innerjoins, array($table1, $table2, $field1, $field2));
		}
		public function AddCondition(/*string*/$fieldname, /*string*/$operator, /*string*/$value, /*boolean*/$valueneedsquotes = true) {
			//if ($valueneedsquotes) { $value = '"'.$value.'"'; }
			$condition = array($this->PrefixFieldName($fieldname), $operator, $value);
			array_push($this->conditions, $condition);
		}

		// TODO: Make RemoveField($name)
		// TODO: Make RemoveCondition($fieldname)
		
		public function ClearFields() { $this->fieldnames = $this->fieldvalues = array(); }
		public function ClearInnerJoin() { $this->innerjoins = array(); }
		public function ClearConditions() { $this->conditions = array(); }
		public function ClearOrderBy() { $this->orderby = null; }
	
		public function __tostring()
		{
			switch ($this->type)
			{
				case DBQT::Select:
					$this->query = 'SELECT ';
	
					if (!empty($this->fieldnames))
					{
						$numberOfFields = sizeof($this->fieldnames) - 1;
						for ($i = 0; $i <= $numberOfFields; $i++)
						{
							$name = $this->fieldnames[$i];
							
							$this->query .= $name[0].' as '.$name[1];
							
							if ($i != $numberOfFields) { $this->query .= COMMASPACE; }
							else { $this->query .= SINGLESPACE; }
						}
					}
					else { $this->query .= '* '; }
	
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
								$this->query .= SINGLECOMMA;
							}
							$this->query .= SINGLESPACE;
						}
					}
	
					$this->_AddToQuery_InnerJoins();
	
					$this->_AddToQuery_Conditions();
					/*if (!empty($this->conditions))
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
								$this->query .= $name.$operator.$value.SINGLESPACE;
							}
							
							if ($i != $numberOfConditions)
							{
								$this->query .= $this->conditiondelimiter.SINGLESPACE;
							}
						}
					}*/

					$this->_AddToQuery_GroupBy();
					$this->_AddToQuery_OrderBy();
					$this->_AddToQuery_Limit();

					/*if (!empty($this->groupby))
					{
						$this->query .= $this->groupby.SINGLESPACE;
					}
					if (!empty($this->orderby))
					{
						$this->query .= $this->orderby.SINGLESPACE;
					}
					if (!empty($this->limit))
					{
						$this->query .= $this->limit.SINGLESPACE;
					}*/
					break;
				case DBQT::Insert:
					$this->query = DBQT::Insert.' INTO '.$this->tables[0].' (';
	
					if (!empty($this->fieldnames))
					{
						$numberOfFields = sizeof($this->fieldnames) - 1;
						for ($i = 0; $i <= $numberOfFields; $i++)
						{
							$name = $this->fieldnames[$i][0];//.' as '.$this->fieldnames[$i][1];
							
							$this->query .= $name;
							if ($i != $numberOfFields)
							{
								$this->query .= COMMASPACE;
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
								$this->query .= COMMASPACE;
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
				case DBQT::Update:
					$this->query = DBQT::Update.SINGLESPACE.$this->tables[0].SINGLESPACE;

					$this->_AddToQuery_InnerJoins();

					$this->query .= 'SET ';
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
								$this->query .= $name.'='.$value.SINGLESPACE;
							}
							
							if ($i != $numberOfFields)
							{
								$this->query .= COMMASPACE;
							}
						}
					}
					else
					{
						$this->error = true;
						break;
					}

					$this->_AddToQuery_Conditions();
					/*if (!empty($this->conditions))
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
								$this->query .= $name.$operator.$value.SINGLESPACE;
							}
							
							if ($i != $numberOfConditions)
							{
								$this->query .= $conditiondelimiter.SINGLESPACE;
							}
						}
					}*/
	
					$this->_AddToQuery_GroupBy();
					$this->_AddToQuery_OrderBy();
					$this->_AddToQuery_Limit();
					
					/*if (!empty($this->orderby))
					{
						$this->query .= $this->orderby.SINGLESPACE;
					}
					if (!empty($this->limit))
					{
						$this->query .= $this->limit.SINGLESPACE;
					}*/
					break;
				case DBQT::Delete:
					$this->query = 'DELETE FROM '.$this->tables[0].SINGLESPACE;

					$this->_AddToQuery_Conditions();
					//$this->_AddToQuery_GroupBy();
					$this->_AddToQuery_OrderBy();
					$this->_AddToQuery_Limit();

					/*if (!empty($this->orderby))
					{
						$this->query .= $this->orderby.SINGLESPACE;
					}
	
					if (!empty($this->limit))
					{
						$this->query .= $this->limit.SINGLESPACE;
					}*/
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
				$this->query = trim($this->query).';';
				if ($this->_print) { var_dump($this->query); }
				return $this->query;
			}
		}
		
		private function _AddToQuery_InnerJoins()
		{
			if (!empty($this->innerjoins))
			{
				$numberOfInnerjoins = sizeof($this->innerjoins) - 1;
				for ($i = 0; $i <= $numberOfInnerjoins; $i++)
				{
					$table1 = $this->innerjoins[$i][0];
					$table2 = $this->innerjoins[$i][1];
					$field1 = $this->innerjoins[$i][2];
					$field2 = $this->innerjoins[$i][3];

					$this->query .= 'INNER JOIN '.$table2.' ON '.$table1.'.'.$field1.'='.$table2.'.'.$field2.SINGLESPACE;
				}
			}
		}
		
		private function _AddToQuery_Conditions()
		{
			if (!empty($this->conditions))
			{
				$this->query .= 'WHERE ';

				$numberOfConditions = sizeof($this->conditions) - 1;
				for ($i = 0; $i <= $numberOfConditions; $i++)
				{
					$name = $this->conditions[$i][0];
					$operator = $this->conditions[$i][1];
					$value = $this->conditions[$i][2];

					if (is_string($value)) { $this->query .= $name.$operator.'"'.$value.'" '; }
					else { $this->query .= $name.$operator.$value.SINGLESPACE; }

					if ($i != $numberOfConditions) { $this->query .= $this->conditiondelimiter.SINGLESPACE; }
				}
			}
		}
		
		private function _AddToQuery_GroupBy()
		{
			if (!empty($this->groupby))
			{
				$this->query .= 'GROUP BY '.$this->groupby.SINGLESPACE;
			}
		}
		
		private function _AddToQuery_OrderBy()
		{
			if (!empty($this->orderby))
			{
				$this->query .= 'ORDER BY '.$this->orderby[0].SINGLESPACE.$this->orderby[1].SINGLESPACE;
			}
		}
		
		private function _AddToQuery_Limit()
		{
			if (!empty($this->limit))
			{
				$this->query .= 'LIMIT '.$this->limit[0].COMMASPACE.$this->limit[1].SINGLESPACE;
			}
		}

		private function PrefixTableName($name)
		{
			if (!reiZ::string_beginswith($name, DBPREFIX)) $name = DBPREFIX.$name;
			return $name;
		}
		
		private function PrefixFieldName($name)
		{
			if (reiZ::string_contains($name, '.') && !reiZ::string_contains($name, DBPREFIX))
				if (($pos = strrpos($name, '(') + 1) > 1) { $name = substr($name, 0, $pos).DBPREFIX.substr($name, $pos); }
				else { $name = DBPREFIX.$name; }
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
			if ($pos !== false) { return substr($name, $pos + 1); }
			else { return $name; }
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
	
	abstract class DB_ConditionDelimiter
	{
		const _AND = 'AND';
		const _OR = 'OR';
	}
	abstract class DBCD extends DB_ConditionDelimiter {}
	
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
