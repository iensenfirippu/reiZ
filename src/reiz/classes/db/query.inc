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
		private $_query = EMPTYSTRING;
		private $_params = array();
		private $_print = false;
		private $_error = false;

		private $_type;
		private $_tables = array();
		private $_fields = array();
		private $_innerjoins = array();
		private $_conditions = array();
		private $_conditiondelimiter;
		private $_limit = array();
		private $_groupby;
		private $_orderby = array();
		
		public function GetSQL()						{ return $this->_query; }
		public function GetParams()						{ return $this->_params; }

		public function SetPrint($print)				{ $this->_print = $print === true; }
		public function SetType($string)				{ $this->_type = strtoupper($string); }
		public function SetTable($name)					{ $this->_tables = array($this->PrefixTableName($name)); }
		public function SetConditionlimiter($string)	{ $this->_conditions = ($string == DBCD::_OR) ? $string : DBCD::_AND; }
		public function SetGroupBy($name)				{ $this->_groupby = $name; }
		public function SetOrderBy($name, $direction)	{ $this->_orderby = array($name, ($direction == DBOD::Desc) ? $direction : DBOD::Asc); }
		public function SetLimit($int1, $int2)			{ $this->_limit = array($int1, $int2); }
	
		public function __construct()
		{
			$this->_conditiondelimiter = DBCD::_AND;
		}
		
		public function AddTable($name) { $this->_tables[] = $this->PrefixTableName($name); }
		
		public function AddField($name, $value=null, $alias=null, $type=PDO::PARAM_STR, $size=255)
		{
			$name = $this->PrefixFieldName($name);
			if ($alias == null) $alias = $this->TrimFieldName($name);
			
			$this->_fields[$name] = new Field($name, $value, $alias, $type, $size);
		}
		
		public function AddFields($names)
		{
			foreach ($names as $name)
			{
				if (is_array($name) && sizeof($name) > 1) { $this->AddField($name[0], null, $name[1]); }
				else { $this->AddField($name); }
			}
		}
		
		public function AddInnerJoin($table1, $table2, $field1, $field2) {
			if ($table1 == EMPTYSTRING) { $table1 = $this->_tables[0]; }
			$table1 = $this->PrefixTableName($table1);
			$table2 = $this->PrefixTableName($table2);
			$this->_innerjoins[] = array($table1, $table2, $field1, $field2);
		}
		
		public function AddCondition($fieldname, $operator, $value=null, $type=PDO::PARAM_STR, $size=255) {
			$this->_conditions[$fieldname] = new Condition($this->PrefixFieldName($fieldname), $operator, $value, $type, $size);
		}
		
		public function RemoveField($fieldname) { Array_Remove($this->_fields, $fieldname); }
		
		public function RemoveCondition($fieldname) { Array_Remove($this->_conditions, $fieldname); }
		
		public function ClearFields() { $this->_fields = array(); }
		
		public function ClearInnerJoin() { $this->_innerjoins = array(); }
		
		public function ClearConditions() { $this->_conditions = array(); }
		
		public function ClearOrderBy() { $this->_orderby = null; }
	
		public function PrepareSQL()
		{
			//$this->_query = EMPTYSTRING;
			$this->_params = array();
			
			if ($this->Validate()) {
				switch ($this->_type) {
					case DBQT::Select:
						$this->_query = DBQT::Select.SINGLESPACE;
		
						if (!empty($this->_fields)) {
							foreach ($this->_fields as $field) {
								$this->_query .= $field->GetName().' as `'.$field->GetAlias().'`';
								if ($field !== end($this->_fields)) { $this->_query .= COMMASPACE; }
								else { $this->_query .= SINGLESPACE; }
							}
						} else { $this->_query .= '* '; }
		
						if (!empty($this->_tables)) {
							$this->_query .= 'FROM ';
							foreach ($this->_tables as $table) {
								$this->_query .= $table;
								
								if ($table !== end($this->_tables)) { $this->_query .= COMMASPACE; }
								else { $this->_query .= SINGLESPACE; }
							}
						}
		
						$this->_AddToQuery_InnerJoins();
						$this->_AddToQuery_Conditions();
						$this->_AddToQuery_GroupBy();
						$this->_AddToQuery_OrderBy();
						$this->_AddToQuery_Limit();
						break;
					case DBQT::Insert:
						$this->_query = DBQT::Insert.' INTO '.$this->_tables[0].' (';
		
						if (!empty($this->_fields)) {
							$fields = $values = EMPTYSTRING;
							foreach ($this->_fields as $field) {
								$fields .= $field->GetName();
								$values .= $field->GetParamName();
								if ($field !== end($this->_fields)) {
									$fields .= COMMASPACE;
									$values .= COMMASPACE;
								}
								$this->_params[] = $field->GetParam();
							}
						} else {
							$this->_error = true;
							break;
						}
		
						$this->_query .= $fields.') VALUES ('.$values.')';
						break;
					case DBQT::Update:
						$this->_query = DBQT::Update.SINGLESPACE.$this->_tables[0].SINGLESPACE;

						$this->_AddToQuery_InnerJoins();

						$this->_query .= 'SET ';
						if (!empty($this->_fields)) {
							foreach ($this->_fields as $field) {
								$this->_query .= $field->GetName().' = '.$field->GetParamName();
								$this->_params[] = $field->GetParam();
								if ($field !== end($this->_fields)) { $this->_query .= COMMASPACE; }
								else { $this->_query .= SINGLESPACE; }
							}
						} else {
							$this->_error = true;
							break;
						}

						$this->_AddToQuery_Conditions();
						$this->_AddToQuery_GroupBy();
						$this->_AddToQuery_OrderBy();
						$this->_AddToQuery_Limit();
						break;
					case DBQT::Delete:
						$this->_query = 'DELETE FROM '.$this->_tables[0].SINGLESPACE;

						$this->_AddToQuery_Conditions();
						$this->_AddToQuery_OrderBy();
						$this->_AddToQuery_Limit();
						break;
					default:
						$this->_error = true;
						break;
				}
			} else {
				$this->_error = true;
			}
	
			if ($this->_error == true) {
				return 'ERROR';
			} else {
				$this->_query = trim($this->_query).';';
				if ($this->_print) { var_dump(array('Query' => $this->_query, 'Params' => $this->_params)); }
				return $this->_query;
			}
		}
		
		private function Validate()
		{
			$result = true;
			if (empty($this->_tables)) { $result = false; }
			return $result;
		}
		
		private function _AddToQuery_InnerJoins()
		{
			if (!empty($this->_innerjoins)) {
				foreach ($this->_innerjoins as $innerjoin) {
					$table1 = $innerjoin[0];
					$table2 = $innerjoin[1];
					$field1 = $innerjoin[2];
					$field2 = $innerjoin[3];

					$this->_query .= 'INNER JOIN '.$table2.' ON '.$table1.'.'.$field1.'='.$table2.'.'.$field2.SINGLESPACE;
				}
			}
		}
		
		private function _AddToQuery_Conditions()
		{
			if (!empty($this->_conditions)) {
				$this->_query .= 'WHERE ';
				foreach ($this->_conditions as $condition) {
					$this->_query .= $condition->GetField().SINGLESPACE.$condition->GetOperator().SINGLESPACE.$condition->GetParamName().SINGLESPACE;
					if ($condition !== end($this->_conditions)) { $this->_query .= $this->_conditiondelimiter.SINGLESPACE; }
					$this->_params[] = $condition->GetParam();
				}
			}
		}
		
		private function _AddToQuery_GroupBy()
		{
			if (!empty($this->_groupby)) {
				$this->_query .= 'GROUP BY '.$this->_groupby.SINGLESPACE;
			}
		}
		
		private function _AddToQuery_OrderBy()
		{
			if (!empty($this->_orderby)) {
				$this->_query .= 'ORDER BY '.$this->_orderby[0].SINGLESPACE.$this->_orderby[1].SINGLESPACE;
			}
		}
		
		private function _AddToQuery_Limit()
		{
			if (!empty($this->_limit)) {
				$this->_query .= 'LIMIT '.$this->_limit[0].COMMASPACE.$this->_limit[1].SINGLESPACE;
			}
		}

		private function PrefixTableName($name)
		{
			if (!String_StartsWith($name, DBPREFIX)) $name = DBPREFIX.$name;
			return $name;
		}
		
		private function PrefixFieldName($name)
		{
			if (String_Contains($name, '.') && !String_Contains($name, DBPREFIX))
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
			$name = $this->StripAggregateFunctions($name);
			
			$pos = strrpos($name, '.');
			if ($pos !== false) { return substr($name, $pos + 1); }
			else { return $name; }
		}
		
		private function StripAggregateFunctions($name)
		{
			/*if (strpos($name, '(') != false) {
				$matches = null;
				preg_match('/^(?:([A-Za-z]+)\()+([A-Za-z0-9]+)(?:\)+)$/', $name, $matches);
				if ($matches != null) { $name = end($matches); }
			}*/
			
			if (strpos($name, '(') !== false) { return strtolower(str_replace(array('(',')'), array('-',EMPTYSTRING), $name)); }
			else { return $name; }
		}
		
		public function Debug()
		{
			if ($this->_query != EMPTYSTRING) {
				$debug_info = array('QUERY' => NEWLINE.$this->_query.NEWLINE);
				$final = $this->_query;
				foreach ($this->_params as $param) {
					if ($param instanceof Param) {
						$debug_info[$param->GetName()] = $param->GetValue();
						$final = str_replace($param->GetName(), '"'.$param->GetValue().'"', $final);
					} else {
						vd($param);
					}
				}
				$debug_info['FINAL'] = NEWLINE.$final.NEWLINE;
				vdd($debug_info);
			} else {
				vdd($this);
			}
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
		const Like = 'LIKE';
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
