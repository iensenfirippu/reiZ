<?php
if (defined('reiZ') or exit(1))
{
	/*define("BLOGPOSTPRPAGE", 10);
	define("BLOGDEFAULTCATEGORY", 'all');
	define("BLOGDEFAULTTAG", 'all');
	define("BLOGPAGINATIONINTOP", true);
	define("BLOGPAGINATIONINBOTTOM", true);
	define("BLOGPRELOADALLCATEGORIES", true);	// Only True case implemented yet
	define("BLOGPRELOADALLTAGS", true);			// Only True case implemented yet
	define("BLOGCATEGORYONFRONTPAGE", 'english,nihongo');
	define("BLOGPOSTONFRONTPAGE", 5);*/

	class BlogModule extends Module
	{
		private $_baseurl = null;
		private $_url = null;
		private $_category = null;
		private $_tag = null;
		private $_page = 1;
		private $_posts = null;
		
		public function GetBaseUrl() { return $this->_baseurl; }
		//public function GetFullUrl() { return new URL(array($this->_baseurl, $this->GetCategoryName(), $this->GetTagName(), $this->_page)); }
		public function GetCategory() { return $this->_category; }
		public function GetTag() { return $this->_tag; }
		public function GetCategoryName() { return ($this->_category instanceof BlogCategory) ? $this->_category->GetName() : BLOGDEFAULTCATEGORY; }
		public function GetTagName() { return ($this->_tag instanceof BlogTag) ? $this->_tag->GetName() : BLOGDEFAULTTAG; }
		public function GetPage() { return $this->_page; }
		public function GetPosts() { return $this->_posts; }
		
		public function __construct($initialize = true)
		{
			$name = 'blog';
			$title = 'Blog';
			$author = 'Philip Jensen';
			$version = 0.1;
			$description = 'Module for all your blogging needs, connects to the database.';
			parent::__construct($name, $title, $author, $version, $description);
			
			if ($initialize) { $this->Initialize(); }
		}
		
		public function Initialize()
		{
			parent::Initialize();
		}
		
		public function GetTitleFromUrl($url)
		{
			$result = false;
			$url = explode(SINGLESLASH, trim($url, SINGLESLASH));
			
			if (sizeof($url) == 1 && $url[0] != EMPTYSTRING)
			{
				if (is_numeric($url[0]))
				{
					$post = BlogPost::LoadFromID($url[0]);
					$result = $post->GetTitle();
				}
			}
			elseif (sizeof($url) == 2 && $url[1] != EMPTYSTRING)
			{
				if (is_numeric($url[1]))
				{
					$result = "Page ".$url[1];
				}
			}
			elseif (sizeof($url) == 3 && $url[2] != EMPTYSTRING)
			{
				if (is_numeric($url[2]))
				{
					$post = BlogPost::LoadFromID($url[2]);
					$result = $post->GetTitle();
				}
			}
			
			return $result;
		}
		
		public function GetHtml($section=null, $args=null)
		{
			$return;
			if ($section == null)
			{
				if ($this->_html == null)
				{
					$this->GenerateHtml();
				}
				$return = $this->_html;
			}
			else
			{
				switch ($section)
				{
					case "rightpane":
						$return = $this->GetHtml_RightPane();
						break;
					case "categories":
						$return = $this->GetHtml_Categories();
						break;
					case "tags":
						$return = $this->GetHtml_Tags();
						break;
					case "frontpage":
						$return = $this->GenerateHtml_Frontpage();
						break;
				}
			}
			return $return;
		}
		
		public function GetHtml_RightPane()
		{
			return new HtmlElement('div', EMPTYSTRING, EMPTYSTRING, array($this->GetHtml_Categories(), $this->GetHtml_Tags()));
		}
		
		public function GetHtml_Categories()
		{
			$return = false;
			if (SetAndNotNull($this->_htmlextra, 'categories'))
			{
				$return = $this->_htmlextra['categories'];
			}
			else
			{
				$return = new HtmlElement("comment", "This block is generated with the main method, and therefore cannot run individually (and even so, only after the main method).");
			}
			return $return;
		}
		
		public function GetHtml_Tags()
		{
			$return = false;
			if (SetAndNotNull($this->_htmlextra, 'tags'))
			{
				$return = $this->_htmlextra['tags'];
			}
			else
			{
				$return = new HtmlElement("comment", "This block is generated with the main method, and therefore cannot run individually (and even so, only after the main method).");
			}
			return $return;
		}
		
		private function GenerateHtml()
		{
			if ($this->_html == null)
			{
				$p = GetSafeArgument(GETPAGE);
				$url = GetSafeArgument(GETARGS);
				$dir = ($url != null) ? explode(SINGLESLASH, $url) : null;
				$this->_baseurl = new URL(URLPAGE.$p.URLARGS);
				$this->_url = EMPTYSTRING;
				
				// Load Categories and Tags
				$categories = BlogCategory::LoadAll();
				 // TODO: CONSIDER REWRITE OF BLOGPRELOADALLTAGS
				$tags = null;
				if (BLOGPRELOADALLTAGS) {
					BlogTag::LoadAll();
					$tags = BlogTag::MostPopular(10);
				}
				
				$count = 0;
				$posts = null;
				//$pagination = null;
				
				if (Array_LongerThan($dir, 0))
				{
					$post = 0;
					if (SetAndNumerical($dir, 3)) { $post = intval($dir[3]); }
					elseif (SetAndNumerical($dir, 0) && intval($dir[0]) > 0) { $post = intval($dir[0]); }
					
					if ($post > 0) {
						$this->_posts = array(BlogPost::LoadFromID($post, $count));
						if ($this->_posts[0] instanceof BlogPost) {
							$this->_category = $this->_posts[0]->GetCategory();
						}
						//else { vdd($this->_posts); /*Redirect($this->_baseurl);*/ }
					}
					else
					{
						$this->_category = (SetAndNotEmpty($dir, 0) && $dir[0] != 'all') ? BlogCategory::LoadFromName($dir[0]) : null;
						$this->_tag = (SetAndNotEmpty($dir, 1) && $dir[1] != 'all') ? BlogTag::LoadFromName($dir[1]) : null;
						$this->_page = (SetAndNumerical($dir, 2)) ? intval($dir[2]) : 1;
						$this->_posts = BlogPost::LoadNewest($this->_category, $this->_tag, (BLOGPOSTPRPAGE * ($this->_page -1)), BLOGPOSTPRPAGE, $count);
					}
				
					//if ($count == 0) { vdd($this->_posts); /*Redirect($this->_baseurl);*/ }
				}
				else
				{
					$this->_category = BLOGDEFAULTCATEGORY;
					$this->_tag = 'all';
					//$this->_url = new URL($cat);
					$this->_posts = BlogPost::LoadNewest($this->_category, $this->_tag, (BLOGPOSTPRPAGE * ($this->_page -1)), BLOGPOSTPRPAGE, $count);
				}
				
				$this->_html = new HtmlElement_Blog_Posts($this, $count);
				$this->_htmlextra['categories'] = new HtmlElement_Blog_Categories($this, $categories);
				$this->_htmlextra['tags'] = new HtmlElement_Blog_Tags($this, $tags);
			}
			return $this->_html;
		}
		
		private function GenerateHtmlForPost($blogpost, $post=EMPTYSTRING, $count=null, $baseurl=null, $url=null)
		{
			/*$timestamp = reiZ::TimestampToHumanTime($blogpost->GetPosted());
			
			$posthtml = new HtmlElement('div', 'class="blogpost"', EMPTYSTRING,
				array(
					new HtmlElement('div', 'class="top"', EMPTYSTRING,
						array(
							new HtmlElement('h3', 'class="title"', $blogpost->GetTitle()),
							new HtmlElement('span', 'class="timestamp"', $timestamp)
						)
					)
				)
			);
			$postcontent = new HtmlElement('div', 'class="center"');
			if ($post != EMPTYSTRING || $count == 1)
			{
				$postcontent->AddChild(new HtmlElement('span', 'class="text"', $blogpost->GetFullText()));
			}
			else
			{
				$postcontent->AddChild(new HtmlElement('span', 'class="text"', $blogpost->GetText()));
				if ($blogpost->HasFullText())
				{
					$postcontent->AddChild(
						new HtmlElement('span', 'class="readmore"', '',
							new HtmlElement('a', 'href="'.$baseurl.$url.$blogpost->GetPostID().'/"', '...')
						)
					);
				}
			}
			$posthtml->AddChild($postcontent);
			$posttags = new HtmlElement('div', 'class="bottom"');
			foreach ($blogpost->GetTags() as $tag)
			{
				if (is_a($tag, "BlogTag"))
				{
					$posttags->AddChild(
						new HtmlElement('a', 'href="'.$baseurl.$tag->GetName().'/"', $tag->GetName())
					);
				}
			}
			if ($blogpost->GetEdited() > 0)
			{
				$posttags->AddChild(
					new HtmlElement('span', 'class="timestamp"', '(edited: '.reiZ::TimestampToHumanTime($blogpost->GetEdited()).')')
				);
			}
			$posthtml->AddChild($posttags);
			
			return $posthtml;*/
		}
		
		public function GenerateHtml_Frontpage()
		{
			$count = 0;
			// TODO: Fetch real blogpage name from database
			$blogpage = "blog";//Page::LoadByModule("blog")->GetName();
			$this->_baseurl = URLPAGE.$blogpage.URLARGS;
			BlogTag::LoadAll();
			$categories = array();
			
			foreach (explode(SINGLECOMMA, BLOGCATEGORYONFRONTPAGE) as $name)
			{
				$category = BlogCategory::LoadFromName($name);
				array_push($categories, BlogPost::LoadNewest($category, null, 0, BLOGPOSTONFRONTPAGE, $count));
			}
			
			return new HtmlElement_Blog_Frontpage($this, $categories);
		}
		
		public function GetSettings()
		{
			$this->LoadClasses();
			$categories = BlogCategory::LoadAll();
			$options1 = array('All');
			$options2 = array();
			foreach ($categories as $category) { $options1[] = $options2[] = $category->GetName(); }
			
			$settings = new Settings($this->GetConfigFile());
			$settings->Add('BLOGPOSTPRPAGE',					'Posts per page',							ST::IntegerValue,		10					);
			$settings->Add('BLOGDEFAULTCATEGORY',			'Default category',						ST::StringValue,		'All',			$options1);
			$settings->Add('BLOGPAGINATIONINTOP',			'Pagination in top',						ST::BooleanValue,		true				);
			$settings->Add('BLOGPAGINATIONINBOTTOM',		'Pagination in bottom',					ST::BooleanValue,		true				);
			$settings->Add('BLOGPRELOADALLCATEGORIES',	'Preload All Categories',				ST::BooleanValue,		true				);
			$settings->Add('BLOGPRELOADALLTAGS',			'Preload All Categories',				ST::BooleanValue,		true				);
			$settings->Add('BLOGCATEGORYONFRONTPAGE',		'Categories on frontpage',				ST::StringList,		EMPTYSTRING,	$options2);
			$settings->Add('BLOGPOSTONFRONTPAGE',			'Posts per category on frontpage',	ST::IntegerValue,		5					);
			$settings->Load();
			return $settings;
		}
	}

	Module::Register(new BlogModule());
	//$GLOBALS['MODULES'][] = new BlogModule(false);
}
?>
