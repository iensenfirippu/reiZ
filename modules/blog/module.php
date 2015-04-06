<?php
if (defined('reiZ') or exit(1))
{
	/*define("BLOGPOSTPRPAGE", 10);
	define("BLOGDEFAULTCATEGORY", 'All');
	define("BLOGPAGINATIONINTOP", true);
	define("BLOGPAGINATIONINBOTTOM", true);
	define("BLOGPRELOADALLCATEGORIES", true);	// Only True case implemented yet
	define("BLOGPRELOADALLTAGS", true);			// Only True case implemented yet
	define("BLOGCATEGORYONFRONTPAGE", 'english,nihongo');
	define("BLOGPOSTONFRONTPAGE", 5);*/

	class BlogModule extends Module
	{
		private $_baseurl = EMPTYSTRING;
		private $_url = EMPTYSTRING;
		
		public function GetBaseUrl() { return $this->_baseurl; }
		public function GetFullUrl() { return $this->_baseurl.$this->_url; }
		
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
			//foreach (glob(FOLDERMODULES.'/'.$this->_name.'/'.FOLDERCLASSES.'/*.php') as $classfile) { include_once($classfile); }
			//$this->_stylesheets = array(FOLDERMODULES.'/'.$this->_name.'/'.FOLDERSTYLES.'/style.css');
		}
		
		public function TranslateBreadcrumb($string)
		{
			return false;
		}
		
		public function GetHtml($section=null)
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
			//if (isset($this->_htmlextra['categories']) && $this->_htmlextra['categories'] != null)
			if (reiZ::SetAndNotNull($this->_htmlextra, 'categories'))
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
			//if (isset($this->_htmlextra['tags']) && $this->_htmlextra['tags'] != null)
			if (reiZ::SetAndNotNull($this->_htmlextra, 'tags'))
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
				$p = reiZ::GetSafeArgument(GETPAGE);
				$url = reiZ::GetSafeArgument(GETARGS);
				$dir = explode('/', $url);
				$cat = null;
				$tag = null;
				$page = 0;
				$post = EMPTYSTRING;
				$this->_baseurl = URLPAGE.$p.URLARGS;
				$this->_url = EMPTYSTRING;
				
				// Load Categories and Tags
				$categories = BlogCategory::LoadAll();
				$tags = null;
				if (BLOGPRELOADALLTAGS) {
					BlogTag::LoadAll();
					$tags = BlogTag::MostPopular(10);
				}
				
				$count = 0;
				$posts = null;
				$pagination = null;
				
				//if (reiz::SetAndNotEmpty($dir, 3)) { reiZ::Redirect($this->_baseurl); }
				// Get the blog directory value into the local variables
				if (reiz::SetAndNotEmpty($dir, 0))
				{
					// integer value = Post
					if (intval($dir[0]) != 0)
					{
						$post = $dir[0];
					}
					// First letter uppercase = Category
					elseif (reiZ::StartsWithUpper($dir[0]))
					{
						// TODO: Consider making this a for loop to make it escapable
						foreach($categories as $c) { if ($c->GetName() == strtolower($dir[0])) { $cat = $c; } }
						if ($cat == null) { $cat = BlogCategory::LoadFromName($dir[0]); }
						if ($cat != null) { $this->_url = ucfirst($cat->GetName()).SINGLESLASH; }
					}
					// First letter lowercase = Tag
					else
					{
						foreach($tags as $t) { if ($t->GetName() == $dir[0]) { $tag = $t; } }
						if ($tag == null) { $tag = BlogTag::LoadFromName($dir[0]); }
						if ($tag != null) { $this->_url = $tag->GetName().SINGLESLASH; }
					}
				}
				if (reiz::SetAndNotEmpty($dir, 1)) { /*var_dump($dir); die(1);*/ $page = $dir[1]; } else { $page = 1; }
				if (reiz::SetAndNotEmpty($dir, 2)) { $post = $dir[2]; }
				
				// Fetch the relevant blog posts (newest X posts of the selected category or tag)
				if ($post != EMPTYSTRING) { $posts = array(BlogPost::LoadFromID($post, $count)); }
				elseif ($cat != null) { $posts = BlogPost::LoadNewestByCategory($cat, (BLOGPOSTPRPAGE * ($page -1)), BLOGPOSTPRPAGE, $count); }
				elseif ($tag != null) { $posts = BlogPost::LoadNewestByTag($tag, (BLOGPOSTPRPAGE * ($page -1)), BLOGPOSTPRPAGE, $count); }
				
				if ($count == 0)
				{
					$cat = BLOGDEFAULTCATEGORY;
					$this->_url = $cat.SINGLESLASH;
					$posts = BlogPost::LoadNewest((BLOGPOSTPRPAGE * ($page -1)), BLOGPOSTPRPAGE, $count);
				}
				
				$this->_html = new HtmlElement_BlogPosts($this, $posts, $count, $page);
				$this->_htmlextra['categories'] = new HtmlElement_BlogCategories($this, $categories);
				$this->_htmlextra['tags'] = new HtmlElement_BlogTags($this, $tags);
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
				array_push($categories, BlogPost::LoadNewestByCategory($category, 0, BLOGPOSTONFRONTPAGE, $count));
			}
			
			return new HtmlElement_BlogFrontpage($this, $categories);
		}
		
		public function GetSettings()
		{
			$this->LoadClasses();
			$categories = BlogCategory::LoadAll();
			$options1 = array('All');
			$options2 = array();
			foreach ($categories as $category) { $options1[] = $options2[] = $category->GetName(); }
			
			$settings = new Settings();
			$settings->Add('BLOGPOSTPRPAGE',			'Posts per page',					ST::Integer,	10				);
			$settings->Add('BLOGDEFAULTCATEGORY',		'Default category',					ST::String,		'All',			$options1);
			$settings->Add('BLOGPAGINATIONINTOP',		'Pagination in top',				ST::Bool,		true			);
			$settings->Add('BLOGPAGINATIONINBOTTOM',	'Pagination in bottom',				ST::Bool,		true			);
			$settings->Add('BLOGPRELOADALLCATEGORIES',	'Preload All Categories',			ST::Bool,		true			);
			$settings->Add('BLOGPRELOADALLTAGS',		'Preload All Categories',			ST::Bool,		true			);
			$settings->Add('BLOGCATEGORYONFRONTPAGE',	'Categories on frontpage',			ST::Strings,	EMPTYSTRING,	$options2);
			$settings->Add('BLOGPOSTONFRONTPAGE',		'Posts per category on frontpage',	ST::Integer,	5				);
			
			return $settings;
		}
	}

	$GLOBALS['MODULES'][] = new BlogModule(false);
}
?>
