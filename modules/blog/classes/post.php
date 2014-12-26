<?php
/*
 * Page class, for containing display theme
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	class BlogPost extends reiZ_DatabaseObject
	{
		protected static $_fields = array("p_id", "category", "title", "shorttext", "content");
		protected static $_orderindex = 3;
		protected static $_orderdirection = "ASC";
		protected static $_itemsperpage = 10;
		protected static $_type = "dbo_blogpost";
		protected static $_dbtable = "blogpost";
		
		protected $_postid = 0;
		protected $_category = null;
		protected $_title = EMPTYSTRING;
		protected $_text = EMPTYSTRING;
		protected $_fulltext = EMPTYSTRING;
		protected $_tags = null;
		protected $_images = array(); // TODO: make into generic module calls instead of statically linking gallery module
		
		public function GetPostID()		{ return $this->_postid; }
		public function GetCategory()	{ return $this->_category; }
		public function GetTitle()		{ return $this->_title; }
		public function GetText()		{ return $this->_text; }
		public function GetFullText()	{ $return = $this->_fulltext; if ($return == EMPTYSTRING) { $return = $this->_text; } return $return; }
		public function GetPosted()		{ return $this->_added; }
		public function GetEdited()		{ return $this->_updated; }
		public function GetTags()		{ return $this->_tags; }
		public function GetImages()		{ return $this->_images; }
		
		private function SetPostID($value)		{ $this->_values[self::$_fields[0]] = $this->_postid = $value; }
		public function SetCategory($value)		{ $this->_values[self::$_fields[1]] = $this->_category = $value; }
		public function SetTitle($value)		{ $this->_values[self::$_fields[2]] = $this->_title = $value; }
		public function SetShortText($value)	{ $this->_values[self::$_fields[3]] = $this->_text = $value; }
		public function SetFullText($value)		{ $this->_values[self::$_fields[4]] = $this->_fulltext = $value; }
		
		protected function __construct($values = null)
		{
			parent::__construct($values);
			
			if (is_array($values))
			{
				if (isset($values[self::$_fields[0]]))	{ self::SetPostID		($values[self::$_fields[0]]); }
				if (isset($values[self::$_fields[1]]))	{ self::SetCategory		(BlogCategory::LoadFromID($values[self::$_fields[1]])); }
				if (isset($values[self::$_fields[2]]))	{ self::SetTitle		($values[self::$_fields[2]]); }
				if (isset($values[self::$_fields[3]]))	{ self::SetShortText	($values[self::$_fields[3]]); }
				if (isset($values[self::$_fields[4]]))	{ self::SetFullText		($values[self::$_fields[4]]); }
				//if (isset($values[self::$_fields[5]]))	{ self::SetPosted		($values[self::$_fields[5]]); }
				//if (isset($values[self::$_fields[6]]))	{ self::SetEdited		($values[self::$_fields[6]]); }
				
				$this->_tags = new BlogTagCollection($this);
			}
		}
		
		public static function Create($category=0, $title=EMPTYSTRING)
		{
			// TODO: Test category exists
			//if		(is_a($masterpiece, "Masterpage"))	{ $masterpage = $masterpage->GetId(); }
			//elseif	(!is_integer($masterpage))			{ $masterpage = 0; }
			
			return new BlogPost(array(
				self::$_fields[0] => 0,
				self::$_fields[1] => $category,
				self::$_fields[2] => $title,
				self::$_fields[3] => EMPTYSTRING,
				self::$_fields[4] => EMPTYSTRING
			));
			//	,
			//	self::$_fields[5] => 0,
			//	self::$_fields[6] => 0
			//));
		}
		
		public static function CreateNew()
		{
			return new BlogPost();
		}
		
		public function SetText($value)
		{
			// TODO: Make generic ellipsize function
			if (strlen($value) > 255)
			{
				$tmp = substr($value, 0, 252);
				$this->SetText(substr($tmp, 0, strrpos($tmp, ' ')).'...');
				$this->SetFullText($value);
			}
			else
			{
				$this->SetText($value);
				$this->SetFullText($value);
			}
		}
		
		public function HasFullText()	{ $return = true; if ($this->_fulltext == '') { $return = false; } return $return; }
		
		public function AddTag($tag)
		{
			$value = false;
			if (!in_array($tag, $this->_tags))
			{
				$value = array_push($this->_tags, $tag);
			}
			return $value;
		}
		
		public static function Load($conditions)
		{
			$row = parent::Load($conditions);
			$object = null;
			if ($row != null)
			{
				$object = new BlogPost($row);
				$object->_indb = true;
			}
			return $object;
		}
		
		public static function LoadAll($conditions=null, $limit_start=null, $limit_amount=null, $order_by=null, $order_direction=null, &$count=null)
		{
			$posts = array();
			$rows = parent::LoadAll($conditions, $limit_start, $limit_amount, $order_by, $order_direction, $count);
			foreach ($rows as $row) { array_push($posts, new BlogPost($row)); }
			return $posts;
		}
		
		public static function LoadFromID($id, &$count=null)
		{
			$result = self::Load(array(array(self::$_dbtable.'.'.self::$_fields[0], DB_Operator::Is, $id)));
			if (is_int($count) && $result != null) { $count = 1; }
			return $result;
		}
		
		public static function LoadNewest($limit_start, $limit_amount, &$count=null)
		{
			return self::LoadAll(null, $limit_start, $limit_amount, null, null, $count);
		}
		
		public static function LoadNewestByCategory($category, $limit_start, $limit_amount, &$count=null)
		{
			return self::LoadAll(array(array(self::$_fields[1], DBOP::Is, $category->GetID())), $limit_start, $limit_amount, null, null, $count);
		}
		
		public static function LoadNewestByTag($tag, $limit_start, $limit_amount, &$count=null)
		{
			$query = parent::PrepareLoadAllQuery(array(array('blogposttag.t_id', DBOP::Is, $tag->GetID())));
			self::InnerJoinBlogTag($query);
			$rows = parent::RunLoadAllQuery($query, $limit_start, $limit_amount, null, null, $count);
			
			$posts = array();
			foreach ($rows as $row) { array_push($posts, new BlogPost($row)); }
			return $posts;
		}
		
		// Inner-joins
		
		/*public static function InnerJoinBlogCategory($query)
		{
			$query->AddFields(array(array('blogcategory.name', 'c_name'), array('blogcategory.title', 'c_title')));
			$query->AddInnerJoin('', 'blogcategory', 'category', 'uuid');
		}*/
		
		public static function InnerJoinBlogTag($query, $capture=false)
		{
			if ($capture) { $query->AddFields(array(array('blogtag.uuid', 'tag_id'), array('blogtag.name', 'tag_name'))); }
			$query->AddInnerJoin('', 'blogposttag', self::$_key, 'p_id');
			if ($capture) { $query->AddInnerJoin('blogposttag', 'blogtag', 't_id', 'uuid'); }
		}
		
		// OLD code
		
		//protected static $_keyindex = 0;
		
		//protected $_posted = 0;
		//protected $_edited = 0;
		
		//public function SetPosted($value)		{ $this->_values[self::$_fields[4]] = $this->_posted = $value; }
		//public function SetEdited($value)		{ $this->_values[self::$_fields[5]] = $this->_edited = $value; }
		//public function SetTags($value)		{ $this->_tags = $value; }
		//public function SetImages($value)		{ $this->_images = $value; }
		
		/*public function Save()
		{
			return parent::Save();
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
		}*/
		
		/*public static function LoadById($id, &$count=null)
		{
			$query = new Query();
			$query->SetType('select');
			$query->AddFields(array('blogpost.p_id','blogpost.posted','blogpost.category','blogpost.title','blogpost.shorttext','blogpost.content'));
			$query->AddTable('blogpost');
			$query->AddCondition('p_id', '=', $postid);
			$result = $GLOBALS['DB']->RunQuery($query);
			$row = $GLOBALS['DB']->GetArray($result);
			array_push($posts, new BlogPost($row['p_id'], $row['posted'], $row['category'], $row['title'], $row['shorttext'], $row['content'], true));
			
			return array($amount, $posts);
		}*/
		
		/*public static function LoadNewest($limit_start, $limit_amount, &$count=null)
		{
			$posts = array();
			$amount = 0;
			
			$query = new Query();
			$query->SetType('select');
			$query->AddField('COUNT(blogpost.p_id)', null, 'id');
			$query->AddTable('blogpost');
			$amount = $GLOBALS['DB']->GetArray($GLOBALS['DB']->RunQuery($query))['id'];
			$query->ClearFields();
			
			$query->AddFields(array('blogpost.p_id','blogpost.posted','blogpost.category','blogpost.title','blogpost.shorttext','blogpost.content'));
			$query->SetLimit($limit_lo, $limit_hi);
			$query->SetOrderBy('posted', 'desc');
			$result = $GLOBALS['DB']->RunQuery($query);
			
			while ($row = $GLOBALS['DB']->GetArray($result))
			{
				array_push($posts, new BlogPost($row['p_id'], $row['posted'], $row['category'], $row['title'], $row['shorttext'], $row['content'], true));
			}
			
			return array($amount, $posts);
		}*/
		
		/*public static function LoadNewestByCategory($category, $limit_start, $limit_amount, &$count=null)
		{
			$posts = array();
			$amount = 0;
			
			$query = new Query();
			$query->SetType('select');
			$query->AddField('COUNT(blogpost.p_id)', null, 'id');
			$query->AddTable('blogpost');
			$query->AddInnerJoin('', 'blogcategory', 'category', 'c_id');
			$query->AddCondition('blogcategory.name', '=', $categoryname);
			$amount = $GLOBALS['DB']->GetArray($GLOBALS['DB']->RunQuery($query))['id'];
			$query->ClearFields();
			
			$query->AddFields(array('blogpost.p_id','blogpost.posted','blogpost.category','blogpost.title','blogpost.shorttext','blogpost.content'));
			$query->SetLimit($limit_lo, $limit_hi);
			$query->SetOrderBy('posted', 'desc');
			$result = $GLOBALS['DB']->RunQuery($query);
			while ($row = $GLOBALS['DB']->GetArray($result))
			{
				array_push($posts, new BlogPost($row['p_id'], $row['posted'], $row['category'], $row['title'], $row['shorttext'], $row['content'], true));
			}
			
			return array($amount, $posts);
		}*/
		
		/*public static function LoadNewestByTag($tag, $limit_start, $limit_amount, &$count=null)
		{
			//return self::LoadAll(array(array('t_id', DB_Operator::Is, $tag->GetID())), $limit_start, $limit_amount, $_values[4]);
			
			$posts = array();
			$amount = 0;
			
			$query = new Query();
			$query->SetType('select');
			$query->AddField('COUNT(blogpost.p_id)', null, 'id');
			$query->AddTable('blogpost');
			$query->AddInnerJoin('', 'blogposttag', 'p_id', 'p_id');
			$query->AddInnerJoin('blogposttag', 'blogtag', 't_id', 't_id');
			$query->AddCondition('blogtag.name', '=', $tag);
			$amount = $GLOBALS['DB']->GetArray($GLOBALS['DB']->RunQuery($query))['id'];
			$query->ClearFields();
			
			$query->AddFields(array('blogpost.p_id','blogpost.posted','blogpost.category','blogpost.title','blogpost.shorttext','blogpost.content'));
			$query->SetLimit($limit_lo, $limit_hi);
			$query->SetOrderBy('posted', 'desc');
			$result = $GLOBALS['DB']->RunQuery($query);
			while ($row = $GLOBALS['DB']->GetArray($result))
			{
				array_push($posts, new BlogPost($row['p_id'], $row['posted'], $row['category'], $row['title'], $row['shorttext'], $row['content'], true));
			}
			
			return array($amount, $posts);
		}*/
	}
}
?>
