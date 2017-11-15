<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		Page class
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains the definition of a page within the CMS.
	 * @extends reiZ_DatabaseObject
	 */
	class Page extends reiZ_DatabaseObject
	{
		protected static $_fields = array("masterpage", "name", "title", "content", "inmenu", "weight");
		//protected static $_keyindex = 0;
		protected static $_orderindex = 4;
		protected static $_orderdirection = "ASC";
		protected static $_itemsperpage = 10;
		protected static $_type = "dbo_page";
		protected static $_dbtable = "page";
		
		private $_theme = null;
		private $_masterpage = null;
		private $_name = null;
		private $_title = null;
		private $_content = null;
		private $_inmenu = null;
		private $_weight = null;
		//private $_file = null;
		private $_module = null;
		private $_modules = null;
		private $_notices = null;
		private $_errors = null;
		
		public function GetTheme()						{ return $this->_themeobject; }
		public function GetMasterpage()				{ return $this->_masterpage; }
		public function GetName()						{ return $this->_name; }
		public function GetTitle($force=false)		{ return ($force == false && $this->_title == null) ? $this->_name : $this->_title; }
		public function GetContent($final=true)	{ return ($final) ? ExecuteCommands($this->_content).$this->GetModuleContent() : $this->_content; }
		//public function GetFinalContent()			{ return reiZ::ExecuteCommands($this->_content); }
		//public function GetContentAsHtml()		{ return reiZ::ConvertTextToHtml($this->_content); }
		public function GetInMenu()					{ return $this->_inmenu; }
		public function GetWeight()					{ return $this->_weight; }
		//public function GetFile()					{ return $this->_file; }
		//public function GetFilename()				{ return $this->_file.".php"; }
		public function GetFilenameMaster()			{ return $this->_masterpage.'.php'; }
		public function GetModule()					{ return $this->_module; }
		public function GetModuleContent()			{ return ($this->_module instanceof Module) ? $this->_module->GetHtml() : EMPTYSTRING; }
		public function GetModules()					{ return $this->_modules; }
		public function GetNotices()					{ return $this->_notices; }
		public function GetErrors()					{ return $this->_errors; }
		
		public function SetTheme($value)				{ $this->_theme = $value; }
		public function SetMasterpage($value)			{ if ($this->ChangeValue(self::$_fields[0], $value)) { $this->_masterpage = $value; } }
		public function SetName($value)					{ if ($this->ChangeValue(self::$_fields[1], $value)) { $this->_name = $value ; } }
		public function SetTitle($value)				{ if ($this->ChangeValue(self::$_fields[2], $value)) { $this->_title = $value; } }
		public function SetContent($value)				{ $value = String_EnforceProperLineEndings($value); if ($this->ChangeValue(self::$_fields[3], String_EncodeHTML($value))) { $this->_content = $value; } }
		private function _SetContent($value)			{ $value = String_EnforceProperLineEndings($value); if ($this->ChangeValue(self::$_fields[3], $value)) { $this->_content = String_DecodeHTML($value); } }
		public function SetInMenu($value)				{ $value = boolval($value); if ($this->ChangeValue(self::$_fields[4], $value)) { $this->_inmenu = $value; } }
		public function SetWeight($value)				{ $value = intval($value); if ($this->ChangeValue(self::$_fields[5], $value)) { $this->_weight = $value; } }
		
// 		public function SetTheme($value)					{ $this->_theme = $value; /*$this->_values[self::$_fields[0]] = $this->_theme->GetUrl();*/ }
// 		public function SetMasterpage($value)			{ $this->_masterpage = $value; $this->_values[self::$_fields[0]] = $this->_masterpage->GetId(); }
// 		public function SetName($value)					{ $this->_values[self::$_fields[1]] = $this->_name = $value; }
// 		public function SetTitle($value)					{ $this->_values[self::$_fields[2]] = $this->_title = $value; }
// 		public function SetContent($value)				{ $value = String_EnforceProperLineEndings($value); $this->_values[self::$_fields[3]] = String_EncodeHTML($value); $this->_content = $value; }
// 		private function _SetContent($value)			{ $value = String_EnforceProperLineEndings($value); $this->_values[self::$_fields[3]] = $value; $this->_content = String_DecodeHTML($value); }
// 		//public function SetContent($value)			{ $this->_values[self::$_fields[3]] = $this->_content = $value; }
// 		//public function SetContentFromHtml($value)	{ $this->SetContent(reiZ::ConvertHtmlToText($value)); }
// 		public function SetInMenu($value)				{ $this->_values[self::$_fields[4]] = $this->_inmenu = boolval($value); }
// 		public function SetWeight($value)				{ $this->_values[self::$_fields[5]] = $this->_weight = intval($value); }
// 		//public function SetFile($value)				{ $this->_values[self::$_fields[6]] = $this->_file = $value; }
// 		//public function SetModules($value)			{ $this->_modules = $value; }
		
		protected function __construct($values=null, $Initialize=true)
		{
			parent::__construct($values);
			
			if (is_array($values))
			{
				//if (isset($values[self::$_fields[0]]))	{ self::SetTheme			($values[self::$_fields[0]]); }
				if (isset($values[self::$_fields[0]]))	{ self::SetMasterpage	(Masterpage::Load($values[self::$_fields[0]])); }
				if (isset($values[self::$_fields[1]]))	{ self::SetName			($values[self::$_fields[1]]); }
				if (isset($values[self::$_fields[2]]))	{ self::SetTitle			($values[self::$_fields[2]]); }
				if (isset($values[self::$_fields[3]]))	{ self::_SetContent		($values[self::$_fields[3]]); }
				if (isset($values[self::$_fields[4]]))	{ self::SetInMenu			($values[self::$_fields[4]]); }
				if (isset($values[self::$_fields[5]]))	{ self::SetWeight			($values[self::$_fields[5]]); }
				//if (isset($values[self::$_field[6]]))	{ self::SetFile			($values[self::$_fields[6]]); }
				//$this->_modules = array();
				//$this->LoadModules();
				if ($Initialize) { $this->LoadModule(); }
			}
		}
		
		public function __tostring()
		{
			return $this::$_fields[1]." = ".$this->_values[$this::$_fields[1]];
		}
		
		/* Convenience constructors -> */
		
			public static function Create($name=EMPTYSTRING, $title=EMPTYSTRING, $content=EMPTYSTRING, $masterpage=0, $inmenu=false, $weight=0, $file=EMPTYSTRING)
			{
				if		(is_a($masterpage, "Masterpage"))	{ $masterpage = $masterpage->GetId(); }
				elseif	(!is_integer($masterpage))			{ $masterpage = 0; }
				if		(!is_bool($inmenu))						{ $inmenu = false; }
				if		(!is_integer($weight))					{ $weight = 0; }
				
				return new Page(array(
					self::$_fields[0] => $masterpage,
					self::$_fields[1] => $name,
					self::$_fields[2] => $title,
					self::$_fields[3] => $content,
					self::$_fields[4] => $inmenu,
					self::$_fields[5] => $weight
					//self::$_fields[6] => $file
				));
			}
			
			public static function CreateNew()
			{
				return new Page();
			}
		
		/* <- Convenience constructors */
		/* Database manipulation -> */
		
			public function Save()
			{
				// Save the Page
				$result = parent::Save();
				
				// Find all pages with the same weight, and increment the weight of the others
				$pages = Page::LoadAll(array(array('weight', DBOP::Is, $this->GetWeight())));
				foreach ($pages as $page)
				{
					if ($page instanceof Page && $page->GetID() !== 0 && $page->GetID() !== $this->GetID())
					{
						$page->SetWeight($page->GetWeight() +1);
						$save = $page->Save();
						$result = $result && $save;
					}
				}
				
				return $result;
			}
			
			public static function Load($conditions)
			{
				$query = parent::PrepareLoadQuery($conditions);
				self::InnerJoinMasterpage($query);
				$row = parent::RunLoadQuery($query);
				//$query->Debug();
				$object = null;
				if ($row != null)
				{
					$object = new Page($row);
				}
				else
				{
					$values = array();
					$values[self::$_fields[0]] = 0;
					$values[self::$_fields[1]] = '404';
					$values[self::$_fields[2]] = '404';
					$values[self::$_fields[3]] = '404: not found';
					$values[self::$_fields[4]] = true;
					$values[self::$_fields[5]] = 1;
					$object = new Page($values);
				}
				$object->_indb = true;
				return $object;
			}
			
			public static function LoadAll($conditions=null, $limit_start=null, $limit_amount=null, $order_by=null, $order_direction=null, &$count=null)
			{
				$pages = array();
				$rows = parent::LoadAll($conditions, $limit_start, $limit_amount, $order_by, $order_direction, $count);
				foreach ($rows as $row) { array_push($pages, new Page($row, false)); }
				foreach ($pages as $page) { $page->_indb = true; }
				return $pages;
			}
			
			public function Delete($final=false)
			{
				$this->ReleaseModules();
				parent::Delete($final);
			}
			
			/* Convenience load methods -> */
			
				public static function LoadFromID($id)
				{
					$test = self::Load(array(array(self::$_dbtable.'.'.self::$_key, DBOP::Is, $id)));
					vdd($test);
					return $test;
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
					$test = self::Load(array(array(self::$_dbtable.'.'.self::$_fields[1], DBOP::Is, $name)));
					vdd($test);
					return $test;
					/*if ($row != null)
					{
						$object = new Page($row);
						$object->_indb = true;
					}
					return $object;*/
					//return parent::Load(array(array(self::$_fields[1],DB_Operator::Is,$name)));
				}
			
			/* <- Convenience load methods */
			/* Module load/unload Methods -> */
			
				private function LoadModule()
				{
					$query = new Query();
					$query->SetType('select');
					$query->AddField('name');
					$query->AddTable('module');
					$query->AddCondition('page', DBOP::Is, $this->_id);
					$result = $GLOBALS['DB']->RunQuery($query);
					
					if (Array_LongerThan($result, 0)) {
						$row = $result[0];
						if (SetAndNotNull($row, 'name'))
						{
							$this->_module = Module::Load($row['name']);
							$this->_module->SetPagename($this->GetName());
							$this->_module->Initialize();
						}
					}
				}
				
				private function LoadModules()
				{
					/*$query = new Query();
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
					}*/
					return null;
				}
				
				private function ReleaseModules()
				{
					$query = new Query();
					$query->SetType(DBQT::Update);
					$query->AddField('page', EMPTYSTRING);
					$query->AddTable('module');
					$query->AddCondition('page', DBOP::Is, $this->_id);
					$result = $GLOBALS['DB']->RunNonQuery($query);
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
					$results = $GLOBALS['DB']->RunQuery($query);
					foreach ($results as $result) { $pages[] = $result['name']; }
					
					return $pages;
				}
			
			/* Module load/unload Methods -> */
			/* inner joins -> */
			
				public static function InnerJoinMasterpage($query)
				{
					$query->AddFields(array(array('masterpage.id', 'mp_id'), array('masterpage.name', 'mp_name')));
					$query->AddInnerJoin('', 'masterpage', 'masterpage', 'id');
				}
			
			/* <- inner joins */
		
		/* <- Database manipulation */
	}
}
?>
