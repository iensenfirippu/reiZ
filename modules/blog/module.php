<?php
define("BLOGPOSTPRPAGE", 10);
define("BLOGDEFAULTCATEGORY", 'All');
define("BLOGPAGINATIONINTOP", true);
define("BLOGPAGINATIONINBOTTOM", true);
define("BLOGPRELOADALLCATEGORIES", true);	// Only True case implemented yet
define("BLOGPRELOADALLTAGS", true);			// Only True case implemented yet
define("BLOGCATEGORYONFRONTPAGE", 'english,nihongo');
define("BLOGPOSTONFRONTPAGE", 5);

class BlogModule extends Module
{
	public function __construct($initialize = true)
	{
		$name = 'blog';
		$title = 'Blog module';
		$author = 'Philip Jensen';
		$version = 0.1;
		$description = 'Module for all your blogging needs, connects to the database.';
		parent::__construct($name, $title, $author, $version, $description);
		
		if ($initialize) { $this->Initialize(); }
	}
	
	public function Initialize()
	{
		foreach (glob(FOLDERMODULES.'/'.$this->_name.'/'.FOLDERCLASSES.'/*.php') as $classfile) { include_once($classfile); }
		$this->_stylesheets = array(FOLDERMODULES.'/'.$this->_name.'/'.FOLDERSTYLES.'/style.css');
	}
	
	public function TranslateBreadcrumb($string)
	{
		return false;
	}
	
	public function GetHtml($section = null)
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
		return new HtmlElement('div', '', '', array($this->GetHtml_Categories(), $this->GetHtml_Tags()));
	}
	
	public function GetHtml_Categories()
	{
		$return = false;
		if (isset($this->_htmlextra['categories']) && $this->_htmlextra['categories'] != null)
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
		if (isset($this->_htmlextra['tags']) && $this->_htmlextra['tags'] != null)
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
			$baseurl = URLPAGE.$p.URLARGS;
			$url = EMPTYSTRING;
			
			$this->_html = new HtmlElement('div', 'class="blog"');
			$this->_htmlextra['categories'] = new HtmlElement('ul', 'class="blogcategories"');
			$this->_htmlextra['tags'] = new HtmlElement('ul', 'class="blogtags"');
			
			$categories = BlogCategory::LoadAll();
			$tags = null;
			if (BLOGPRELOADALLTAGS) {
				BlogTag::LoadAll();
				$tags = BlogTag::MostPopular(10);
			}
			
			$count = 0;
			$posts = null;
			$pagination = null;
			
			// Get the blog directory value into the local variables
			if (isset($dir[0]) && !empty($dir[0]))
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
					if ($cat != null) { $url = ucfirst($cat->GetName()).'/'; }
				}
				// First letter uppercase = Tag
				else
				{
					foreach($tags as $t) { if ($t->GetName() == $dir[0]) { $tag = $t; } }
					if ($tag == null) { $tag = BlogTag::LoadFromName($dir[0]); }
					if ($tag != null) { $url = $tag->GetName().'/'; }
				}
			}
			if (isset($dir[1]) && !empty($dir[1])) { $page = $dir[1]; } else { $page = 1; }
			if (isset($dir[2]) && !empty($dir[2])) { $post = $dir[2]; }
			
			// Fetch the relevant blog post (newest X posts of the selected category or tag)
			if ($post != EMPTYSTRING) { $posts = array(BlogPost::LoadFromID($post, $count)); }
			elseif ($cat != null) { $posts = BlogPost::LoadNewestByCategory($cat, (BLOGPOSTPRPAGE * ($page -1)), BLOGPOSTPRPAGE, $count); }
			elseif ($tag != null) { $posts = BlogPost::LoadNewestByTag($tag, (BLOGPOSTPRPAGE * ($page -1)), BLOGPOSTPRPAGE, $count); }
			
			if ($count == 0)
			{
				$cat = BLOGDEFAULTCATEGORY;
				$url = $cat.'/';
				$posts = BlogPost::LoadNewest((BLOGPOSTPRPAGE * ($page -1)), BLOGPOSTPRPAGE, $count);
			}
			$pagination = new Pagination($baseurl.$url, $count, BLOGPOSTPRPAGE, $page);
			
			if ($count > BLOGPOSTPRPAGE && BLOGPAGINATIONINTOP) { $this->_html->AddChild($pagination->GetHtml()); }
			
			// Print the fetched posts
			foreach ($posts as $blogpost)
			{
				$this->_html->AddChild($this->GenerateHtmlForPost($blogpost, $post, $count, $baseurl, $url.$page.'/'));
			}
			
			if ($count > BLOGPOSTPRPAGE && BLOGPAGINATIONINBOTTOM) { $this->_html->AddChild($pagination->GetHtml()); }
			
			// Get all the categories
			foreach ($categories as $category)
			{
				$this->_htmlextra['categories']->AddChild(
					new HtmlElement('li', '', '',
						new HtmlElement('a', 'href="'.URLPAGE.$p.URLARGS.ucfirst($category->GetName()).'/"', $category->GetTitle())
					)
				);
			}
			
			// Get all the tags
			foreach ($tags as $tag)
			{
				$this->_htmlextra['tags']->AddChild(
					new HtmlElement('li', '', '',
						new HtmlElement('a', 'href="'.URLPAGE.$p.URLARGS.$tag->GetName().'/"', $tag->GetName())
					)
				);
			}
		}
		return $this->_html;
	}
	
	private function GenerateHtmlForPost($blogpost, $post=EMPTYSTRING, $count=null, $baseurl=null, $url=null)
	{
		$timestamp = reiZ::TimestampToHumanTime($blogpost->GetPosted());
		
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
		
		return $posthtml;
	}
	
	public function GenerateHtml_Frontpage()
	{
		$count = 0;
		// TODO: Fetch real blogpage name from database
		$blogpage = "blog";//Page::LoadByModule("blog")->GetName();
		$baseurl = URLPAGE.$blogpage.URLARGS;
		BlogTag::LoadAll();
		
		$html = new HtmlElement('div', 'class="blog_frontpage"');
		foreach (explode(",", BLOGCATEGORYONFRONTPAGE) as $name)
		{
			$category = BlogCategory::LoadFromName($name);
			$posts = BlogPost::LoadNewestByCategory($category, 0, BLOGPOSTONFRONTPAGE, $count);
			$html_cat = new HtmlElement('div', 'class="blog_category"');
			
			foreach ($posts as $blogpost)
			{
				$html_cat->AddChild($this->GenerateHtmlForPost($blogpost, 0, $count, $baseurl, EMPTYSTRING));
			}
			
			$html->AddChild($html_cat);
		}
		return $html;
	}
}

$MODULE = new BlogModule(false);
?>
