<?php
class BlogCategory
{
	protected $_id = 0;
	protected $_name = '';
	protected $_title = '';
	//protected $_posts = array();
	
	public function __construct($id, $name, $title)
	{
		$this->_id = $id;
		$this->_name = $name;
		$this->_title = $title;
	}
	
	public function GetId()		{ return $this->_id; }
	public function GetName()	{ return $this->_name; }
	public function GetTitle()	{ return $this->_title; }
	//public function GetPosts()	{ return $this->_posts; }
	
	public static function Load($id)
	{
		$category = null;
		
		$query = new Query();
		$query->SetType('select');
		$query->AddTable('blogcategory');
		$query->AddCondition('id', '=', $id);
		$result = $GLOBALS['DB']->RunQuery($query);
		$row = $GLOBALS['DB']->GetArray($result);
		if ($row != null)
		{
			$category = new BlogCategory($row['c_id'], $row['name'], $row['title']);
		}
		
		return $category;
	}
	
	public static function LoadFromName($name)
	{
		$category = null;
		
		$query = new Query();
		$query->SetType('select');
		$query->AddTable('blogcategory');
		$query->AddCondition('name', '=', $name);
		$result = $GLOBALS['DB']->RunQuery($query);
		$row = $GLOBALS['DB']->GetArray($result);
		if ($row != null)
		{
			$category = new BlogCategory($row['c_id'], $row['name'], $row['title']);
		}
		
		return $category;
	}
	
	public static function LoadAll()
	{
		$categories =  array();
		
		$query = new Query();
		$query->SetType('select');
		$query->AddTable('blogcategory');
		$query->SetOrderby('c_id', 'asc');
		$result = $GLOBALS['DB']->RunQuery($query);
		while ($row = $GLOBALS['DB']->GetArray($result))
		{
			array_push($categories, new BlogCategory($row['c_id'], $row['name'], $row['title']));
		}
		
		return $categories;
	}
}
?>
