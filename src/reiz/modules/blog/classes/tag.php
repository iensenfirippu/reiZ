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
		private static $_dbtable_rel = "blogposttag";
		private static $_cache = array();
		
		private $_id = EMPTYSTRING;
		private $_name = EMPTYSTRING;
		private $_popularity = EMPTYSTRING;
		private $_indb = false;
		
		public function GetID()					{ return $this->_id; }
		public function GetName()				{ return $this->_name; }
		public function GetPopularity()			{ return $this->_popularity; }
		
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
		
		public static function CompareName($a, $b, $reverse=false)
		{
			$var1 = EMPTYSTRING; if (is_a($a, 'BlogTag')) { $var1 = $a->GetName(); }
			$var2 = EMPTYSTRING; if (is_a($b, 'BlogTag')) { $var2 = $b->GetName(); }
			$result = strcmp($var1, $var2);
			if ($reverse) { Boolean_Flip($result); }
			return $result;
		}
		
		public static function ComparePopularity($a, $b)
		{
			$var1 = EMPTYSTRING; if (is_a($a, 'BlogTag')) { $var1 = $a->GetPopularity(); }
			$var2 = EMPTYSTRING; if (is_a($b, 'BlogTag')) { $var2 = $b->GetPopularity(); }
			$result = $var2 - $var1;
			if ($result == 0) { $result = BlogTag::CompareName($a, $b); }
			return $result;
		}
		
		public function Save()
		{
			$saved = false;
			if ($this->_indb == true) { $saved = $this->Update(); }
			else { $saved = $this->Insert(); }
			return $saved;
		}
		
		private function Update()
		{
			$saved = false;
			
			$query = new Query();
			$query->SetType(DBQT::Update);
			$query->SetTable(static::$_dbtable);
			$query->AddField('name', $this->_name);
			$query->AddCondition('t_id', DBOP::Is, $this->_id);
			$result = $GLOBALS['DB']->RunNonQuery($query);
			$saved = ($result != false);
			
			return $saved;
		}
		
		private function Insert()
		{
			$saved = false;
			
			if ($this->_name != EMPTYSTRING)
			{
				$query = new Query();
				$query->SetType(DBQT::Insert);
				$query->SetTable(static::$_dbtable);
				$query->AddField('name', $this->_name);
				$results = $GLOBALS['DB']->RunNonQuery($query);
				$result = SetAndNotNull($results, 0) ? $results[0] : null;
				
				if ($result != null)
				{
					$tag = BlogTag::LoadFromName($this->_name);
					
					if ($tag != null)
					{
						$this->_indb = true;
						$this->_id = $tag->GetID();
						$saved = true;
					}
				}
			}
			
			return $saved;
		}
		
		public static function LoadAll()
		{
			$query = new Query();
			$query->SetType(DBQT::Select);
			$query->AddTable(static::$_dbtable);
			$query->AddFields(array(static::$_dbtable.'.t_id', 'name', array('COUNT('.static::$_dbtable_rel.'.t_id)', 'popularity')));
			$query->AddInnerJoin('', static::$_dbtable_rel, 't_id', 't_id');
			$query->SetGroupBy(DBPREFIX.static::$_dbtable_rel.'.t_id');
			$query->SetOrderby('t_id', DBOD::Asc);
			$results = $GLOBALS['DB']->RunQuery($query);
			
			foreach ($results as $row)
			{
				$tag = new BlogTag($row['t_id'], $row['name'], $row['popularity']);
				$tag->_indb = true;
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
				$query->AddFields(array(static::$_dbtable.'.t_id', 'name', array('COUNT('.static::$_dbtable_rel.'.t_id)', 'popularity')));
				$query->AddInnerJoin('', static::$_dbtable_rel, 't_id', 't_id');
				$query->SetGroupBy(DBPREFIX.static::$_dbtable_rel.'.t_id');
				$query->AddCondition(static::$_dbtable.'.t_id', DBOP::Is, $id);
				$results = $GLOBALS['DB']->RunQuery($query);
				$row = SetAndNotNull($results, 0) ? $results[0] : null;
				
				if ($row != null)
				{
					$tag = new BlogTag($row['t_id'], $row['name'], $row['popularity']);
					$tag->_indb = true;
				}
			}
			
			return $tag;
		}
		
		public static function LoadFromName($name, $orcreate=false)
		{
			$tag = null;
			
			if (SetAndNotNull($name != null)) {
				foreach (static::$_cache as $object) { if ($object->GetName() == $name) { $tag = $object; } }
				
				if ($tag == null)
				{
					$query = new Query();
					$query->SetType(DBQT::Select);
					$query->AddTable(static::$_dbtable);
					$query->AddFields(array(static::$_dbtable.'.t_id', static::$_dbtable.'.name', array('COUNT('.static::$_dbtable_rel.'.t_id)', 'popularity')));
					$query->AddInnerJoin(EMPTYSTRING, static::$_dbtable_rel, 't_id', 't_id');
					//$query->SetGroupBy(DBPREFIX.static::$_dbtable_rel.'.t_id');
					$query->AddCondition('name', DBOP::Is, $name);
					$results = $GLOBALS['DB']->RunQuery($query);
					$row = SetAndNotNull($results, 0) ? $results[0] : null;
					
					if ($row != null && $row['t_id'] != null && $row['name'] != null)
					{
						$tag = new BlogTag($row['t_id'], $row['name'], $row['popularity']);
						$tag->_indb = true;
					}
				}
				
				if ($orcreate && $tag == null) { $tag = new BlogTag(null, $name, 1); }
			}
			
			return $tag;
		}
		
		public static function LoadFromPost($post)
		{
			$tags =  array();
			
			if (is_a($post, 'BlogPost'))
			{
				$query = new Query();
				$query->SetType(DBQT::Select);
				$query->SetTable(static::$_dbtable);
				$query->AddField(static::$_dbtable.'.t_id');
				$query->AddInnerJoin('', static::$_dbtable_rel, 't_id', 't_id');
				$query->AddCondition('p_id', DBOP::Is, $post->GetPostID());
				$query->SetOrderby('t_id', DBOD::Asc);
				$results = $GLOBALS['DB']->RunQuery($query);
				
				foreach ($results as $row)
				{
					array_push($tags, BlogTag::LoadFromID($row['t_id']));
				}
			}
			
			return $tags;
		}
		
		protected function Delete()
		{
			$result = false;
			$query = new Query();
			
			if ($this->_indb)
			{
				// Make sure that the tag isn't referenced (popularity = 0), before deleting
				$query->SetType(DBQT::Select);
				$query->AddTable(static::$_dbtable);
				$query->AddFields(array('COUNT('.static::$_dbtable_rel.'.t_id)', 'popularity'));
				$query->AddInnerJoin('', static::$_dbtable_rel, 't_id', 't_id');
				$query->SetGroupBy(DBPREFIX.static::$_dbtable_rel.'.t_id');
				$query->AddCondition(static::$_dbtable.'.t_id', DBOP::Is, $this->_id);
				$result = $GLOBALS['DB']->RunQuery($query);
				$row = SetAndNotNull($results, 0) ? $results[0] : null;
				
				if (SetAndNotNull($row, 'popularity') && $row['popularity'] == 0)
				{
					parent::Delete();
				}
			}
			
			return $result;
		}
		
		public function AddReferenceTo(/*BlogPost*/ $post)
		{
			$result = false;
			$query = new Query();
			
			if (is_a($post, 'BlogPost') && $post->GetPostID() != null)
			{
				if (!$this->_indb) { $this->Save(); }
				if ($this->_indb && $this->_id > 0)
				{
					$query->SetType(DB_QueryType::Insert);
					$query->SetTable(DBPREFIX.static::$_dbtable_rel);
					$query->AddField('p_id', $post->GetPostID());
					$query->AddField('t_id', $this->GetID());
					//$query->AddCondition('p_id', DB_Operator::Is, $post->GetPostID());
					//$query->AddCondition('t_id', DB_Operator::Is, $this->GetID());
					$result = $GLOBALS['DB']->RunNonQuery($query);
					
					// On succes, increment popularity
					if ($result) { $this->_popularity++; }
				}
			}
			
			return $result;
		}
		
		public function DeleteReferenceTo(/*BlogPost*/ $post)
		{
			$result = false;
			$query = new Query();
			
			if (is_a($post, 'BlogPost'))
			{
				$query->SetType(DB_QueryType::Delete);
				$query->SetTable(DBPREFIX.static::$_dbtable_rel);
				$query->AddCondition('p_id', DB_Operator::Is, $post->GetPostID());
				$query->AddCondition('t_id', DB_Operator::Is, $this->GetID());
				$result = $GLOBALS['DB']->RunNonQuery($query);
				
				if ($result)
				{
					// On succes, decrement popularity
					$this->_popularity--;
					// If popularity is 0, there is no other posts using this tag, so delete the tag aswell.
					if ($this->GetPopularity() == 0) { $this->Delete(); }
				}
			}
			
			return $result;
		}
		
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
	
	class BlogTagCollection implements ArrayAccess
	{
		// Temporary solution (redundant data, please revise)
		// TODO: Find a way to share dbtable with BlogTag class
		//private static $_dbtable = "blogtag";
		//private static $_dbtable_rel = "blogtagpost";
		
		// TODO: research possibility of mapping BlogTagCollection->GetTag[$i] > BlogTagCollection[$i]
		// Fixed?: ArrayAccess implented, but untested.
		private $_post = null;
		private $_tags = array();
		private $_added = array();
		private $_removed = array();
		
		public function GetTags() { return $this->_tags; }
		public function GetTag($index) { return $this->_tags[$index]; }
		
		public function SetTags(/*string*/ $value) { $this->ImportFromString($value); }
		
		public function __construct(/*BlogPost*/ $post, /*bool*/ $load=true)
		{
			$this->_post = $post;
			if ($load)
			{
				$this->_tags = BlogTag::LoadFromPost($post);
				// Sort Alphabetically
				usort($this->_tags, array("BlogTag", "CompareName"));
			}
		}
		
		public function __tostring()
		{
			//var_dump($this->_tags);
			
			$value = EMPTYSTRING;
			$lastindex = sizeof($this->_tags) -1;
			for ($i = 0; $i <= $lastindex; $i++)
			{
				$tag = $this->_tags[$i];
				if ($tag != null)
				{
					$value .= $tag->GetName();
					if ($i < $lastindex) { $value .= ', '; }
				}
			}
			return $value;
		}
		
		public function Save()
		{
			//var_dump($this->_post);
			for ($i = sizeof($this->_removed) -1; $i >= 0; $i--)
			{
				$tag = $this->_removed[$i];
				if ($tag != null)
				{
					if ($tag->DeleteReferenceTo($this->_post)) { Array_Remove($this->_removed, $i); }
					// TODO: Add error handling for failed removals
				}
			}
			
			for ($i = sizeof($this->_added) -1; $i >= 0; $i--)
			{
				$tag = $this->_added[$i];
				if ($tag != null)
				{
					if ($tag->AddReferenceTo($this->_post)) { Array_Remove($this->_removed, $i); }
					// TODO: Add error handling for failed additions
				}
			}
			$this->_added = array();
			
			return true;
		}
		
		public function ImportFromString(/*string*/ $value)
		{
			//echo "Import string: \"".$value."\"\n";
			
			if (!empty($value))
			{
				$newtags = array();
				if (is_string($value))
				{
					$i = -1;
					$separator = array(SINGLECOMMA, ';', SINGLESPACE);
					
					if (String_Contains($value, $separator, 1, $i))
					{
						$names = explode($separator[$i], $value);
						foreach ($names as $name)
						{
							$name = strtolower(trim($name));
							if ($name != EMPTYSTRING) { array_push($newtags, $name); }
						}
					}
					else { array_push($newtags, $value); }
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
					// if the tag also exists in the new collection, remove it from new tags so it doesn't get read.
					if (Array_Contains($newtags, $tag->GetName(), $z)) { Array_Remove($newtags, $z); }
					// if the tag doesn't exist in the new collection, remove it from the old collection.
					else { $this->RemoveAt($i); }
				}
				
				// Add all the new tags to the collection
				foreach ($newtags as $tag) { $this->Add(BlogTag::LoadFromName($tag, true)); }
				
				// Sort Alphabetically
				usort($this->_tags, array("BlogTag", "CompareName"));
			}
		}
		
		public function Add(/*BlogTag*/ $value)
		{
			if (is_string($value)) { $value = BlogTag::LoadFromName($value, true); }
			
			if (is_a($value, 'BlogTag'))
			{
				if (!$this->InCollection($value))
				{
					array_push($this->_tags, $value);
					array_push($this->_added, $value);
				}
			}
		}
		
		public function Remove(/*BlogTag*/ $tag)
		{
			$i = -1;
			if ($this->InCollection($value, $i)) { $this->RemoveAt($i); }
		}
		
		public function RemoveAt(/*integer*/ $index)
		{
			if ($index < sizeof($this->_tags))
			{
				array_push($this->_removed, $this->_tags[$index]);
				Array_Remove($this->_tags, $index);
			}
		}
		
		public function InCollection(/*string*/ $value, /*integer*/& $out=null) {
			$incollection = false;
			if (is_a($value, 'BlogTag')) { $value = $value->GetName(); }
			if (is_string($value))
			{
				//foreach ($this->_tags as $tag)
				for ($i = 0; $i < sizeof($this->_tags); $i++)
				{
					$tag = $this->_tags[$i];
					if ($tag != null && $tag->GetName() == $value)
					{
						$incollection = true;
						if ($out != null) { $out = $i; }
						$i = sizeof($this->_tags);
					}
				}
			}
			return $incollection;
		}
		
		/* ArrayAccess methods */
		public function offsetSet($offset, $value) { if (is_null($offset)) { $this->_tags[] = $value; } else { $this->_tags[$offset] = $value; } }
		public function offsetExists($offset) { return isset($this->_tags[$offset]); }
		public function offsetUnset($offset) { unset($this->_tags[$offset]); }
		public function offsetGet($offset) { return isset($this->_tags[$offset]) ? $this->_tags[$offset] : null; }
	}
}
?>
