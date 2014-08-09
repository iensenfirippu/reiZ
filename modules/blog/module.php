<?php
// TODO: Add support for direct blogpost linking (no category og page, just like: /blog/$ID )

define("BLOGPOSTPRPAGE", 10);
define("BLOGDEFAULTCATEGORY", 'All');
define("BLOGPAGINATIONLINKS", 3);
define("BLOGPAGINATIONFIRST", '&Lt;');
define("BLOGPAGINATIONLAST", '&Gt;');
define("BLOGPAGINATIONPREV", '&lt;');
define("BLOGPAGINATIONNEXT", '&gt;');
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
		if ($this->_htmlextra['categories'] != null)
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
		if ($this->_htmlextra['tags'] != null)
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
			$catortag = '';
			$cat = '';
			$page = 0;
			$post = 0;
			
			$this->_html = new HtmlElement('div', 'class="blog"');
			$this->_htmlextra['categories'] = new HtmlElement('ul', 'class="blogcategories"');
			$this->_htmlextra['tags'] = new HtmlElement('ul', 'class="blogtags"');
			
			$categories = BlogCategory::LoadAll();
			$tags = BlogTag::LoadByPopularity();
			
			$countandposts = null;
			$pagination = null;
			
			// Get the blog directory value into the local variables
			if (isset($dir[0]) && !empty($dir[0]))
			{
				// First letter uppercase = Category
				if (reiZ::StartsWithUpper($dir[0])) { $cat = strtolower($dir[0]); }
				$catortag = $dir[0];
			} else { $cat = BLOGDEFAULTCATEGORY; }
			if (isset($dir[1]) && !empty($dir[1])) { $page = $dir[1]; } else { $page = 1; }
			if (isset($dir[2]) && !empty($dir[2])) { $post = $dir[2]; }
			
			// Fetch the relevant blog post (newest X posts of the selected category or tag)
			if ($post != 0)	{ $countandposts = BlogPost::LoadById($post); }
			elseif ($cat != EMPTYSTRING) { $countandposts = BlogPost::LoadNewestByCategory($cat, (BLOGPOSTPRPAGE * ($page -1)), BLOGPOSTPRPAGE); }
			elseif ($catortag != EMPTYSTRING) { $countandposts = BlogPost::LoadNewestByTag($catortag, (BLOGPOSTPRPAGE * ($page -1)), BLOGPOSTPRPAGE); }
			//else { $countandposts = BlogPost::LoadNewest($page); $catortag = BLOGDEFAULTCATEGORY; }
			if ($countandposts[0] == 0) { $countandposts = BlogPost::LoadNewest((BLOGPOSTPRPAGE * ($page -1)), BLOGPOSTPRPAGE); $catortag = BLOGDEFAULTCATEGORY; }
			$pagination = new BlogPagination($p, $countandposts[0], $catortag, $page);
			
			if ($post == 0) { $this->_html->AddChild($pagination->GetHtml()); }
			
			// Print the fetched posts
			foreach ($countandposts[1] as $blogpost)
			{
				$this->_html->AddChild($this->GenerateHtmlForPost($blogpost, $post, $countandposts, $p, $catortag, $page));
			}
			
			if ($post == 0) { $this->_html->AddChild($pagination->GetHtml()); }
	
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
	
	private function GenerateHtmlForPost($blogpost, $post = 0, $countandposts = null, $p = null, $catortag = null, $page = null)
	{
		$posthtml = new HtmlElement('div', 'class="blogpost"', '',
			array(
				new HtmlElement('div', 'class="top"', '',
					array(
						new HtmlElement('h3', 'class="title"', $blogpost->GetTitle()),
						new HtmlElement('span', 'class="timestamp"', reiZ::TimestampToHumanTime($blogpost->GetTimestamp()))
					)
				)
			)
		);
		$postcontent = new HtmlElement('div', 'class="center"');
		if ($post != 0 || $countandposts[0] == 1)
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
						new HtmlElement('a', 'href="'.URLPAGE.$p.URLARGS.$catortag.'/'.$page.'/'.$blogpost->GetId().'/"', '...')
					)
				);
			}
		}
		$posthtml->AddChild($postcontent);
		$posttags = new HtmlElement('div', 'class="bottom"');
		foreach ($blogpost->GetTags() as $tag)
		{
			$posttags->AddChild(
				new HtmlElement('a', 'href="'.URLPAGE.$p.URLARGS.$tag->GetName().'/"', $tag->GetName())
			);
		}
		$posthtml->AddChild($posttags);
		
		return $posthtml;
	}
	
	public function GenerateHtml_Frontpage()
	{
		$html = new HtmlElement('div', 'class="blog_frontpage"');
		foreach (explode(",", BLOGCATEGORYONFRONTPAGE) as $name)
		{
			$category = BlogCategory::LoadFromName($name);
			$countandposts = BlogPost::LoadNewestByCategory($name, 0, BLOGPOSTONFRONTPAGE);
			$html_cat = new HtmlElement('div', 'class="blog_category"');
			
			foreach ($countandposts[1] as $blogpost)
			{
				$html_cat->AddChild($this->GenerateHtmlForPost($blogpost, 0, $countandposts));
			}
			
			$html->AddChild($html_cat);
		}
		return $html;
	}
}

$MODULE = new BlogModule(false);
?>
