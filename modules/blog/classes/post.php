<?php
class BlogPost
{
	protected $_indb = false;
	protected $_id = 0;
	protected $_timestamp = 0;
	protected $_category = null;
	protected $_title = '';
	protected $_text = '';
	protected $_fulltext = '';
	protected $_tags = array();
	protected $_images = array();
	
	public function __construct($id, $timestamp, $category, $title, $text, $fulltext = '', $loadtags = false)
	{
		$this->_id = $id;
		$this->_timestamp = $timestamp;
		$this->_category = $category;
		$this->_title = $title;
		
		if ($fulltext == '' && strlen($text) > 255)
		{
			$tmp = substr($text, 0, 252);
			$this->_text = substr($tmp, 0, strrpos($tmp, ' ')).'...';
			$this->_fulltext = $text;
		}
		else
		{
			$this->_text = $text;
			$this->_fulltext = $fulltext;
		}
		
		if ($loadtags) { $this->_tags = BlogTag::LoadByPost($id); }
		//$this->_images = $this->LoadImages();
	}
	
	public function GetId()			{ return $this->_id; }
	public function GetTimestamp()	{ return $this->_timestamp; }
	public function GetCategory()	{ return $this->_category; }
	public function GetTitle()		{ return $this->_title; }
	public function GetText()		{ return $this->_text; }
	public function GetFullText()	{ $return = $this->_fulltext; if ($return == '') { $return = $this->_text; } return $return; }
	public function GetTags()		{ return $this->_tags; }
	
	public function HasFullText()	{ $return = true; if ($this->_fulltext == '') { $return = false; } return $return; }
	
	public function AddTag($tag)	{ return array_push($this->_tags, $tag); }
	
	public function Save()
	{
		if ($this->_indb == true) { $this->Update(); }
		else { $this->Insert(); }
	}
	
	private function Update()
	{
		return false;
	}
	
	private function Insert()
	{
		$saved = false;
		
		$query = new Query();
		$query->SetType('insert');
		$query->SetTable('blogpost');
		$query->AddField('posted', $this->_timestamp);
		$query->AddField('category', $this->_category);
		$query->AddField('title', $this->_title);
		$query->AddField('shorttext', $this->_text);
		$query->AddField('content', $this->_fulltext);
		$saved = $GLOBALS['DB']->RunNonQuery($query);
		
		if ($saved)
		{
			$this->_indb = true;
			
			$query->SetType('select');
			$query->ClearFields();
			$query->AddField('p_id');
			$query->AddCondition('title', '=', $this->_title);
			$query->SetLimit(0, 1);
			$query->SetOrderBy('p_id', 'desc');
			$result = $GLOBALS['DB']->RunQuery($query);
			
			if ($result != false)
			{
				$row = $GLOBALS['DB']->GetArray($result);
				$this->_id = $row['p_id'];
				
				foreach ($this->_tags as $tag)
				{
					$tag->Save();
			
					$query = new Query();
					$query->SetType('insert');
					$query->SetTable('blogposttag');
					$query->AddField('p_id', $this->_id);
					$query->AddField('t_id', $tag->GetId());
					$GLOBALS['DB']->RunNonQuery($query);
				}
			}
		}
		
		return $saved;
	}
	
	public static function LoadById($postid)
	{
		$posts = array();
		$amount = 1;
		
		$query = new Query();
		$query->SetType('select');
		$query->AddTable('blogpost');
		$query->AddCondition('p_id', '=', $postid);
		$result = $GLOBALS['DB']->RunQuery($query);
		$row = $GLOBALS['DB']->GetArray($result);
		array_push($posts, new BlogPost($row['p_id'], $row['posted'], $row['category'], $row['title'], $row['shorttext'], $row['content'], true));
		
		return array($amount, $posts);
	}
	
	public static function LoadNewest($limit_lo, $limit_hi)
	{
		$posts = array();
		$amount = 0;
		
		$query = new Query();
		$query->SetType('select');
		$query->AddField('count(p_id) AS id');
		$query->AddTable('blogpost');
		$amount = $GLOBALS['DB']->GetArray($GLOBALS['DB']->RunQuery($query))['id'];
		$query->ClearFields();
		
		$query->AddField('blogpost.*');
		$query->SetLimit($limit_lo, $limit_hi);
		$query->SetOrderBy('posted', 'desc');
		$result = $GLOBALS['DB']->RunQuery($query);
		while ($row = $GLOBALS['DB']->GetArray($result))
		{
			array_push($posts, new BlogPost($row['p_id'], $row['posted'], $row['category'], $row['title'], $row['shorttext'], $row['content'], true));
		}
		
		return array($amount, $posts);
	}
	
	public static function LoadNewestByCategory($categoryname, $limit_lo, $limit_hi)
	{
		$posts = array();
		$amount = 0;
		
		$query = new Query();
		$query->SetType('select');
		$query->AddField('count(blogpost.p_id) AS id');
		$query->AddTable('blogpost');
		$query->AddInnerJoin('', 'blogcategory', 'category', 'c_id');
		$query->AddCondition('blogcategory.name', '=', $categoryname);
		$amount = $GLOBALS['DB']->GetArray($GLOBALS['DB']->RunQuery($query))['id'];
		$query->ClearFields();
		
		$query->AddField('blogpost.*');
		$query->SetLimit($limit_lo, $limit_hi);
		$query->SetOrderBy('posted', 'desc');
		$result = $GLOBALS['DB']->RunQuery($query);
		while ($row = $GLOBALS['DB']->GetArray($result))
		{
			array_push($posts, new BlogPost($row['p_id'], $row['posted'], $row['category'], $row['title'], $row['shorttext'], $row['content'], true));
		}
		
		return array($amount, $posts);
	}
	
	public static function LoadNewestByTag($tag, $limit_lo, $limit_hi)
	{
		$posts = array();
		$amount = 0;
		
		$query = new Query();
		$query->SetType('select');
		$query->AddField('count(blogpost.p_id) AS id');
		$query->AddTable('blogpost');
		$query->AddInnerJoin('', 'blogposttag', 'p_id', 'p_id');
		$query->AddInnerJoin('blogposttag', 'blogtag', 't_id', 't_id');
		$query->AddCondition('blogtag.name', '=', $tag);
		$amount = $GLOBALS['DB']->GetArray($GLOBALS['DB']->RunQuery($query))['id'];
		$query->ClearFields();
		
		$query->AddField('blogpost.*');
		$query->SetLimit($limit_lo, $limit_hi);
		$query->SetOrderBy('posted', 'desc');
		$result = $GLOBALS['DB']->RunQuery($query);
		while ($row = $GLOBALS['DB']->GetArray($result))
		{
			array_push($posts, new BlogPost($row['p_id'], $row['posted'], $row['category'], $row['title'], $row['shorttext'], $row['content'], true));
		}
		
		return array($amount, $posts);
	}
}
?>
