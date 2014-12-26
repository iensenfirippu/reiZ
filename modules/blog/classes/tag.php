<?php
/*
 * Page class, for containing display theme
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
// TODO: Remove hardcoded strings

if (defined('reiZ') or exit(1))
{
	class BlogTag
	{
		private static $_dbtable = "blogtag";
		private static $_cache = array();
		
		private $_id = EMPTYSTRING;
		private $_name = EMPTYSTRING;
		private $_popularity = EMPTYSTRING;
		private $_indb = false;
		
		public function GetID()					{ return $this->_id; }
		public function GetName()				{ return $this->_name; }
		public function GetPolarity()			{ return $this->_popularity; }
		
		public function SetName($value)			{ $this->_name = $value; }
		
		public function __construct($id=null, $name=EMPTYSTRING, $popularity=0)
		{
			$this->_id = $id;
			$this->_name = $name;
			$this->_popularity = $popularity;
			
			static::CacheObject($this);
		}
		
		private static function CacheObject($object)
		{
			if ($object->GetID() != null)
			{
				if (!isset(static::$_cache[$object->GetID()]))
				{
					static::$_cache[$object->GetID()] = $object;
				}
			}
		}
		
		public static function ComparePopularity($a, $b)
		{
			$result = $b->GetPolarity() - $a->GetPolarity();
			if ($result == 0) { $result = strcmp($a->GetName(), $b->GetName()); }
			return $result;
		}
		
		public function Save()
		{
			if ($this->_indb == true) { $this->Update(); }
			else { $this->Insert(); }
		}
		
		private function Update()
		{
			$saved = false;
			
			$query = new Query();
			$query->SetType(DBQT::Update);
			$query->SetTable(static::$_dbtable);
			$query->AddField('name', $this->_name);
			$query->AddCondition('t_id', DBOP::Is, $this->_id);
			$saved = $GLOBALS['DB']->RunNonQuery($query);
			$saved = true;
			
			return $saved;
		}
		
		private function Insert()
		{
			$saved = false;
			
			$query = new Query();
			$query->SetType(DBQT::Insert);
			$query->SetTable(static::$_dbtable);
			$query->AddField('t_id', $this->_id);
			$query->AddField('name', $this->_name);
			$result = $GLOBALS['DB']->RunNonQuery($query);
			
			if ($result != false)
			{
				$this->_indb = true;
				$this->_id = mysql_insert_id();
				$saved = true;
			}
			
			return $saved;
		}
		
		public static function LoadAll()
		{
			$query = new Query();
			$query->SetType(DBQT::Select);
			$query->AddTable(static::$_dbtable);
			$query->AddFields(array('blogtag.t_id', 'name', array('COUNT(blogposttag.t_id)', 'popularity')));
			$query->AddInnerJoin('', 'blogposttag', 't_id', 't_id');
			$query->SetGroupBy(DBPREFIX.'blogposttag.t_id');
			$query->SetOrderby('t_id', DBOD::Asc);
			$result = $GLOBALS['DB']->RunQuery($query);
			while ($row = $GLOBALS['DB']->GetArray($result))
			{
				new BlogTag($row['t_id'], $row['name'], $row['popularity']);
			}
			
			return static::$_cache;
		}
		
		public static function LoadFromID($id)
		{
			$tag = null;
			
			if (isset(static::$_cache[$id]) && static::$_cache[$id] != null)
			{
				$tag = static::$_cache[$id];
			}
			else
			{
				$query = new Query();
				$query->SetType(DBQT::Select);
				$query->AddTable(static::$_dbtable);
				$query->AddFields(array(static::$_dbtable.'.t_id', 'name', array('COUNT(blogposttag.t_id)', 'popularity')));
				$query->AddInnerJoin('', 'blogposttag', 't_id', 't_id');
				$query->SetGroupBy(DBPREFIX.'blogposttag.t_id');
				$query->AddCondition(static::$_dbtable.'.t_id', DBOP::Is, $id);
				$result = $GLOBALS['DB']->RunQuery($query);
				$row = $GLOBALS['DB']->GetArray($result);
				if ($row != null)
				{
					$tag = new BlogTag($row['t_id'], $row['name'], $row['popularity']);
				}
			}
			
			return $tag;
		}
		
		public static function LoadFromName($name, $orcreate=false)
		{
			$tag = null;
			
			foreach (static::$_cache as $object) { if ($object->GetName() == $name) { $tag = $object; } }
			
			if ($tag == null)
			{
				$query = new Query();
				$query->SetType(DBQT::Select);
				$query->AddTable(static::$_dbtable);
				$query->AddFields(array('t_id', 'name', array('COUNT(blogposttag.t_id)', 'popularity')));
				$query->AddInnerJoin('', 'blogposttag', 't_id', 't_id');
				$query->SetGroupBy(DBPREFIX.'blogposttag.t_id');
				$query->AddCondition('name', '=', $name);
				$result = $GLOBALS['DB']->RunQuery($query);
				$row = $GLOBALS['DB']->GetArray($result);
				if ($row != null)
				{
					$tag = new BlogTag($row['t_id'], $row['name'], $row['popularity']);
				}
			}
			
			if ($orcreate && $tag == null) { $tag = new BlogTag(null, $name, 1); }
			
			return $tag;
		}
		
		// Moved to BlogTagCollection
		/*public static function LoadByPost($post)
		{
			$tags =  array();
			
			$query = new Query();
			$query->SetType(DBQT::Select);
			$query->SetTable(static::$_dbtable);
			$query->AddField(static::$_dbtable.'.t_id');
			$query->AddInnerJoin('', 'blogposttag', 't_id', 't_id');
			$query->AddCondition('p_id', DBOP::Is, $post->GetID());
			$query->SetOrderby('t_id', DBOD::Asc);
			$result = $GLOBALS['DB']->RunQuery($query);
			while ($row = $GLOBALS['DB']->GetArray($result))
			{
				array_push($tags, BlogTag::LoadFromID($row['t_id']));
			}
			
			return $tags;
		}*/
		
		public static function MostPopular($amount)
		{
			$tags = array();
			
			if (sizeof(static::$_cache) > 0)
			{
				$tags = static::$_cache;
				usort($tags, array("BlogTag", "ComparePopularity"));
				$tags = array_slice($tags, 0, $amount);
			}
			
			return $tags;
		}
		
		/* if we ever decide to go back to DBO for blog tags */
		
		//extends reiZ_DatabaseObject
		
		//protected static $_fields = array("name");
		//protected static $_keyindex = 0;
		//protected static $_orderindex = 0;
		//protected static $_orderdirection = "ASC";
		//protected static $_itemsperpage = 10;
		//protected static $_type = "dbo_blogtag";
		
		//public function SetName($value)		{ $this->_values[self::$_fields[0]] = $this->_name = $value; }
		
		/*protected function __construct($values = null)
		{
			parent::__construct($values);
			
			if (is_array($values))
			{
				if (isset($values[self::$_fields[0]]))	{ self::SetName($values[self::$_fields[0]]); }
			}
		}*/
		
		/*public static function Create($name=EMPTYSTRING)
		{
			return new BlogTag(array(
				self::$_fields[0] => $name
			));
		}*/
		
		/*public static function CreateNew()
		{
			return new BlogTag();
		}*/
		
		/*public static function Load($conditions)
		{
			$row = parent::Load($conditions);
			$object = null;
			if ($row != null)
			{
				$object = new BlogTag($row);
				$object->_indb = true;
			}
			return $object;
		}*/
		
		/*public static function LoadAll($conditions=null, $limit_start=null, $limit_amount=null, $order_by=null, $order_direction=null, &$count=null)
		{
			$tags = array();
			$rows = parent::LoadAll($conditions, $limit_start, $limit_amount, $order_by, $order_direction, $count);
			foreach ($rows as $row) { array_push($tags, new BlogTag($row)); }
			return $tags;
		}*/
		
		/*public static function LoadFromID($id)
		{
			return self::Load(array(array(self::$_key, DB_Operator::Is, $id)));
		}*/
			
		/*public static function LoadFromName($name)
		{
			return self::Load(array(array(self::$_fields[0], DBOP::Is, $name)));
		}*/
		
		/*public static function LoadMostPopular($amount)
		{
			$query = parent::PrepareLoadAllQuery();
			self::InnerJoinPopularity($query);
			$query->SetOrderBy('popularity', DBOD::Asc);
			$rows = parent::RunLoadAllQuery($query);
			
			$tags = array();
			foreach ($rows as $row)
			{
				array_push($tags, new BlogTag($row));
			}
			return $tags;
		}*/
		
		
		/*public static function LoadByPost($post)
		{
			$query = parent::PrepareLoadAllQuery(array(array('blogposttag.p_id', DB_Operator::Is, $post->GetID())));
			self::InnerJoinBlogPostTag($query);
			$rows = parent::RunLoadAllQuery($query);
			
			//echo $query;
			
			$tags = array();
			foreach ($rows as $row)
			{
				array_push($tags, new BlogTag($row));
			}
			return $tags;
		}*/
		
		// Inner-joins
		
		/*public static function InnerJoinPopularity($query)
		{
			$query->AddFields(array(array('COUNT(blogposttag.t_id)', 'popularity')));
			$query->AddInnerJoin('', 'blogposttag', self::$_key, 't_id');
			$query->SetGroupBy(DBPREFIX.'blogposttag.t_id');
		}
		
		public static function InnerJoinBlogPostTag($query)
		{
			$query->AddFields(array(array('blogposttag.p_id', 'post_id')));
			$query->AddInnerJoin('', 'blogposttag', self::$_key, 't_id');
		}
		
		public static function InnerJoinBlogPost($query)
		{
			$query->AddFields(array(array('blogpost.name', 'post_name')));
			$query->AddInnerJoin('blogposttag', 'blogpost', 'p_id', 'uuid');
		}*/
	}
	
	class BlogTagCollection
	{
		// TODO: research possibility of mapping BlogTagCollection->GetTags[$i] > BlogTagCollection[$i]
		private $_post = null;
		private $_tags = array();
		
		public function GetTags() { return $this->_tags; }
		
		public function SetTags(/*string*/ $value) { $this->ImportFromString($value); }
			
		public function __construct(/*BlogPost*/ $post, /*bool*/ $load=true)
		{
			if (is_a($post, 'BlogPost'))
			{
				$this->_post = $post;
				
				$query = new Query();
				$query->SetType(DBQT::Select);
				$query->SetTable(static::$_dbtable);
				$query->AddField(static::$_dbtable.'.t_id');
				$query->AddInnerJoin('', 'blogposttag', 't_id', 't_id');
				$query->AddCondition('p_id', DBOP::Is, $post->GetID());
				$query->SetOrderby('t_id', DBOD::Asc);
				$result = $GLOBALS['DB']->RunQuery($query);
				while ($row = $GLOBALS['DB']->GetArray($result))
				{
					array_push($this->_tags, BlogTag::LoadFromID($row['t_id']));
				}
			}
			else
			{
				var_dump($post);
			}
		}
		
		public function __tostring()
		{
			$value = EMPTYSTRING;
			for ($i = sizeof($this->_tags) -1; $i >= 0; $i--)
			{
				$value .= $this->_tags[$i]->GetName();
				if ($i != 0) { $value .= ', '; }
			}
			return $value;
		}
		
		public function ImportFromString(/*string*/ $value)
		{
			if (!empty($value))
			{
				$newtags = array();
				if (is_string($value))
				{
					$i = -1;
					$separator = array(SINGLECOMMA, ';', SINGLESPACE);
					
					if (reiZ::string_contains($string, $separator, 1, $i))
					{
						$names = explode($separator[$i], $string);
						foreach ($names as $name)
						{
							if ($name = strtolower(trim($name)) != EMPTYSTRING) { array_push($newtags, $name); }
						}
					}
				}
				elseif (is_array($value))
				{
					foreach ($value as $var)
					{
						if (is_string($var)) { array_push($newtags, $var); }
						elseif (is_a($var, 'BlogTag')) { array_push($newtags, $var->GetName()); }
					}
				}
				elseif (is_a($value, 'BlogTag')) { array_push($newtags, $var->GetName()); }
				
				// Run through the old tags...
				$z = null;
				for ($i = 0; $i < sizeof($this->_tags); $i++)
				{
					$tag = $this->_tags[$i];
					// if the tag also exists in the new collection, remove it from new tags so it doesn't get readded.
					if (reiZ::string_is_one_of($newtags, $tag->GetName(), $z)) { $newtags[$z] = null; }
					// if the tag doesn't exist in the new collection, remove it from the old collection.
					else { $this->Remove($tag); }
				}
				
				// Add all the new tags to the collection
				foreach ($newtags as $tag) { $this->Add($tag); }
			}
		}
		
		public function Add(/*BlogTag*/ $value)
		{
			// TODO: make work
			
			if (is_string($value)) { $value = BlogTag::LoadFromName($value, true); }
			
			if (is_a($value, 'BlogTag'))
			{
				$incollection = false;
				foreach ($this->_tags as $tag) { if ($tag->GetName() == $value->GetName()) { $incollection = true; } }
				
				if (!$incollection)
				{
					array_push($this->_tags, $value);
				}
			}
		}
		
		public function Remove(/*BlogTag*/ $value)
		{
			// TODO: make work
			
			if (is_a($value, 'BlogTag')) { $value = $value->GetName(); }
			if (is_string($value)) { foreach ($this->_tags as $tag) { if ($tag->GetName() == $value->GetName()) { $incollection = true; } }
			
			if (is_integer($value))
			{
				$incollection = false;
				
				if (!$incollection)
				{
					array_push($this->_tags, $value);
				}
			}
		}
	}
}
?>
