<?php
class BlogTag
{
	protected $_indb = false;
	protected $_id = 0;
	protected $_name = '';
	
	public function __construct($id, $name)
	{
		$this->_id = $id;
		$this->_name = $name;
	}
	
	public function GetId()		{ return $this->_id; }
	public function GetName()	{ return $this->_name; }
	
	public function Save()
	{
		if ($this->_indb == true) { $this->Update(); }
		else { $this->Insert(); }
	}
	
	private function Update()
	{
		$saved = false;
		
		//$query = new Query();
		//$query->SetType('update');
		//$query->AddTable('blogtag');
		//$query->AddField('name', $this->_name);
		//$query->AddCondition('t_id', '=', $this->_id);
		//$saved = $GLOBALS['DB']->RunNonQuery($query);
		$saved = true;
		
		return $saved;
	}
	
	private function insert()
	{
		$saved = false;
		
		$query = new Query();
		$query->SetType('insert');
		$query->SetTable('blogtag');
		$query->AddField('t_id', $this->_id);
		$query->AddField('name', $this->_name);
		$saved = $GLOBALS['DB']->RunNonQuery($query);
		
		$this->_indb = true;
			
		$query->SetType('select');
		$query->ClearFields();
		$query->AddField('t_id');
		$query->AddCondition('name', '=', $this->_name);
		$query->SetLimit(0, 1);
		$query->SetOrderBy('t_id', 'desc');
		$result = $GLOBALS['DB']->RunQuery($query);
		
		if ($result != false)
		{
			$row = $GLOBALS['DB']->GetArray($result);
			$this->_id = $row['t_id'];
		}
		
		return $saved;
	}
	
	public static function LoadAll()
	{
		$tags =  array();
		
		$query = new Query();
		$query->SetType('select');
		$query->AddTable('blogtag');
		$query->SetOrderby('t_id', 'asc');
		$result = $GLOBALS['DB']->RunQuery($query);
		while ($row = $GLOBALS['DB']->GetArray($result))
		{
			array_push($tags, new BlogTag($row['t_id'], $row['name']));
		}
		
		return $tags;
	}
	
	public static function LoadByPost($post)
	{
		$tags =  array();
		
		$query = new Query();
		$query->SetType('select');
		$query->AddTable('blogtag');
		$query->AddInnerJoin('', 'blogposttag', 't_id', 't_id');
		$query->AddCondition('p_id', '=', $post);
		$query->SetOrderby('p_id', 'asc');
		$result = $GLOBALS['DB']->RunQuery($query);
		while ($row = $GLOBALS['DB']->GetArray($result))
		{
			array_push($tags, new BlogTag($row['t_id'], $row['name']));
		}
		
		return $tags;
	}
	
	public static function LoadByPopularity()
	{
		$tags =  array();

		$query = new Query();
		$query->SetType('select');
		$query->AddField('blogtag.*');
		$query->AddField('COUNT(blogposttag.t_id) AS popularity');
		$query->AddTable('blogtag');
		$query->AddInnerJoin('', 'blogposttag', 't_id', 't_id');
		$query->SetGroupBy('t_id');
		$query->SetOrderBy('popularity', 'desc');
		$result = $GLOBALS['DB']->RunQuery($query);
		while ($row = $GLOBALS['DB']->GetArray($result))
		{
			array_push($tags, new BlogTag($row['t_id'], $row['name']));
		}
		
		return $tags;
	}
}
?>
