<?php
/*
 * Page class, for containing display theme
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
class Page
{
	protected $_id = null;
	protected $_theme = null;
	protected $_masterpage = null;
	protected $_name = null;
	protected $_text = null;
	protected $_inmenu = null;
	protected $_weight = null;
	protected $_file = null;
	protected $_modules = null;
	
	// TODO: remove pages from filestructure and add modules, styles and scripts to the page object
	//protected $_modules = array();
	//protected $_stylesheets = array();
	//protected $_javascripts = array();
	
	public function __construct($id, $masterpage, $name, $text, $inmenu, $weight, $file = null)
	{
		$this->_id = $id;
		$this->_masterpage = $masterpage;
		$this->_name = $name;
		$this->_text = $text;
		$this->_inmenu = $inmenu;
		$this->_weight = $weight;
		$this->_file = $file;
		$this->_modules = array();
		$this->LoadModules();
	}
	
	public function GetId()				{ return $this->_id; }
	public function GetTheme()			{ return $this->_theme; }
	public function GetMasterPage()		{ return $this->_masterpage; }
	public function GetName()			{ return $this->_name; }
	public function GetText()			{ return $this->_text; }
	public function GetInMenu()			{ return $this->_inmenu; }
	public function GetWeight()			{ return $this->_weight; }
	public function GetFile()			{ return $this->_file; }
	public function GetFilename()		{ return $this->_file.".php"; }
	public function GetFilenameMaster()	{ return $this->_masterpage.'.php'; }
	public function GetModules()		{ return $this->_modules; }
	
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
			$method = null; if ($row['method'] != "") { $method = $row['method']; }
			array_push($this->_modules, array($row['name'], $method));
		}
	}
	
	public static function LoadByName($name)
	{
		$page = null;
		
		// Check if the page has a custom layout in the current themes folder
		//$file = $GLOBALS['THEME']->GetDirectory().'/'.$name.'.php';
		//if (!file_exists($file)) { $file = DEFAULTPAGE; }
		//else { $file = $name; }
		
		$query = new Query();
		$query->SetType('select');
		$query->AddFields(array('page.id', 'masterpage.id as mp_id', 'masterpage.name as mp_name', 'page.name', 'page.content', 'page.inmenu', 'page.weight'));
		$query->AddTable('page');
		$query->AddInnerJoin('', 'masterpage', 'masterpage', 'id');
		$query->AddCondition('page.name', '=', $name);
		$result = $GLOBALS['DB']->RunQuery($query);
		$row = $GLOBALS['DB']->GetArray($result);
		if ($row != null)
		{
			$masterpage = new Masterpage($row['mp_id'], $row['mp_name']);
			$page = new Page($row['id'], $masterpage, $row['name'], $row['content'], $row['inmenu'], $row['weight']);//, $file);
		}
		else
		{
			$page = new Page(0,DEFAULTMASTER,'404','404: not found',true,1);//,$file);
		}
		
		return $page;
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
}
?>
