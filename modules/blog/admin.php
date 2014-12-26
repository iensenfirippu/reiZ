<?php
/*
 * home module test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

class CustomAdmininistrationPage
{
	private $_text_main = 'Welcome to your blog administration page.';
	
	public function __construct()
	{
		// include all blog related classes
		foreach (glob(FOLDERMODULES.'/blog/'.FOLDERCLASSES.'/*.php') as $classfile) { include_once($classfile); }
	}
	
	public function MakeHtml($admin, $args)
	{	
		$showhome = false;
		$showlist = false;
		
		$argcount = sizeof($GLOBALS['ARGS']);
		if ($argcount > 1)
		{
			if ($GLOBALS['ARGS'][1] == 'post')
			{
				if ($argcount > 2)
				{
					if ($GLOBALS['ARGS'][2] == 'new') { $this->MakeHtml_EditPost($admin, $args, BlogPost::CreateNew()); }
					elseif ($argcount > 3)
					{
						$post = BlogPost::LoadFromID($args[3]);
						
						if ($GLOBALS['ARGS'][2] == 'edit') { $this->MakeHtml_EditPost($admin, $args, $post); }
						elseif ($GLOBALS['ARGS'][2] == 'delete') { $this->MakeHtml_DeletePost($admin, $args, $post); }
						else { $showlist = true; }
					}
					else { $showlist = true; }
				}
				else { $showlist = true; }
				
				if ($showlist) { $this->MakeHtml_ListPosts($admin, $args); }
			}
			elseif ($GLOBALS['ARGS'][1] == 'tag')
			{
				if ($argcount > 2)
				{
					/*if ($GLOBALS['ARGS'][2] == 'new') { $this->MakeHtml_NewTag($admin, $args); }
					elseif ($GLOBALS['ARGS'][2] == 'edit') { $this->MakeHtml_EditTag($admin, $args); }
					elseif ($GLOBALS['ARGS'][2] == 'delete') { $this->MakeHtml_DeleteTag($admin, $args); }
					else { $showhome = true; }*/
				}
				else { $this->MakeHtml_ListTags($admin, $args); }
			}
			elseif ($GLOBALS['ARGS'][1] == 'category')
			{
				if ($argcount > 2)
				{
					/*if ($GLOBALS['ARGS'][2] == 'new') { $this->MakeHtml_NewCategory($admin, $args); }
					elseif ($GLOBALS['ARGS'][2] == 'edit') { $this->MakeHtml_EditCategory($admin, $args); }
					elseif ($GLOBALS['ARGS'][2] == 'delete') { $this->MakeHtml_DeleteCategory($admin, $args); }
					else { $showhome = true; }*/
				}
				else { $this->MakeHtml_ListCategories($admin, $args); }
			}
			else { $showhome = true; }
		}
		else { $showhome = true; }
		
		
		if ($showhome) { $this->MakeHtml_Home($admin, $args); }
	}
	
	/*public function MakeHtml_Options($admin, $args)
	{
		$options = $admin->GetOptions();
		
	}*/
	
	private function MakeHtml_Home($admin, $args)
	{
		$options = $admin->GetOptions();
		
		$options->AddOption('posts', '/'.FOLDERADMIN.'/blog/post/', 'Posts');
		$options->AddOption('tags', '/'.FOLDERADMIN.'/blog/tag/', 'Tags');
		$options->AddOption('categories', '/'.FOLDERADMIN.'/blog/category/', 'Categories');
		
		$html = $admin->GetContent();
		
		$box = new AdminBox('Edit Blog...');
		$box->AddContent(new HtmlElement('span', '', $this->_text_main));
		
		$html->AddChild($box);
	}
	
	private function MakeHtml_ListPosts($admin, $args)
	{
		$options = $admin->GetOptions();
		$options->AddOption('home', '/'.FOLDERADMIN.'/blog/', 'Back');
		$options->AddOption('write', '/'.FOLDERADMIN.'/blog/post/new', 'Write', '/'.FOLDERCOMMON.'/images/add.png');
		
		$html = $admin->GetContent();
		$posts = Blogpost::LoadAll(null, 0, 50, 'added', DBOD::Desc);
		
		$box = new AdminBox('List');
		$list = new HtmlList(array('Title', 'Posted', 'Edited', EMPTYSTRING));
		foreach ($posts as $post)
		{
			$links = new HtmlElement();
			$links->AddChild(new HtmlElement('a', 'href="/blog/'.$post->GetPostID().'/"', 'V'));
			$links->AddChild(new HtmlElement('a', 'href="/'.ADMINPAGE.'/blog/post/edit/'.$post->GetPostID().'/"', 'E'));
			$links->AddChild(new HtmlElement('a', 'href="/'.ADMINPAGE.'/blog/post/delete/'.$post->GetPostID().'/"', 'D'));
			$list->AddRow(array($post->GetTitle(), reiZ::TimestampToHumanTime($post->GetPosted(), TF::DateTime),
				reiZ::TimestampToHumanTime($post->GetEdited(), TF::DateTime), $links));
		}
		$box->AddContent($list);
		
		$html->AddChild($box);
	}
	
	private function MakeHtml_ListTags($admin, $args)
	{
		$options = $admin->GetOptions();
		$options->AddOption('home', '/'.FOLDERADMIN.'/blog/', 'Back');
		//$options->AddOption('write', '/'.FOLDERADMIN.'/blog/tag/new', 'New', '/'.FOLDERCOMMON.'/images/add.png');
		
		$html = $admin->GetContent();
		$tags = BlogTag::LoadAll();
		
		$box = new AdminBox('List');
		$list = new HtmlList(array('Name', EMPTYSTRING));
		foreach ($tags as $tag)
		{
			$links = new HtmlElement();
			$links->AddChild(new HtmlElement('a', 'href="/blog/'.strtolower($tag->GetName()).'/"', 'V'));
			$links->AddChild(new HtmlElement('a', 'href="/'.ADMINPAGE.'/blog/tag/edit/'.$tag->GetName().'/"', 'E'));
			$links->AddChild(new HtmlElement('a', 'href="/'.ADMINPAGE.'/blog/tag/delete/'.$tag->GetName().'/"', 'D'));
			$list->AddRow(array($tag->GetName(), $links));
		}
		$box->AddContent($list);
		
		$html->AddChild($box);
	}
	
	private function MakeHtml_ListCategories($admin, $args)
	{
		$options = $admin->GetOptions();
		$options->AddOption('home', '/'.FOLDERADMIN.'/blog/', 'Back');
		$options->AddOption('write', '/'.FOLDERADMIN.'/blog/category/new', 'New', '/'.FOLDERCOMMON.'/images/add.png');
		
		$html = $admin->GetContent();
		$categories = BlogCategory::LoadAll();
		
		$box = new AdminBox('List');
		$list = new HtmlList(array('Name', EMPTYSTRING));
		foreach ($categories as $category)
		{
			$links = new HtmlElement();
			$links->AddChild(new HtmlElement('a', 'href="/blog/'.ucfirst($category->GetName()).'/"', 'V'));
			$links->AddChild(new HtmlElement('a', 'href="/'.ADMINPAGE.'/blog/category/edit/'.$category->GetName().'/"', 'E'));
			$links->AddChild(new HtmlElement('a', 'href="/'.ADMINPAGE.'/blog/category/delete/'.$category->GetName().'/"', 'D'));
			$list->AddRow(array($category->GetName(), $links));
		}
		$box->AddContent($list);
		
		$html->AddChild($box);
	}
	
	private function MakeHtml_EditPost($admin, $args, $post)
	{
		//$post = (0, time(), EMPTYSTRING, EMPTYSTRING, EMPTYSTRING);
		//$this->HandleFormInput_Post($admin, $post);
		
		if (isset($_POST['title']) && !empty($_POST['title']))
		{
			$post->SetTitle($_POST['title']);
			$post->SetCategory($_POST['category']);
			$post->SetText($_POST['content']);
			
			// Set tags
		}
		
		$options = $admin->GetOptions();
		$options->AddOption('home', '/'.FOLDERADMIN.'/blog/post/', 'Back');
		
		$html = $admin->GetContent();
		
		$category_options = array();
		$categories = BlogCategory::LoadAll();
		foreach ($categories as $c) { array_push($category_options, array($c->GetId(), $c->GetTitle())); }
		
		//	new HtmlElement('form', 'name="add" action="/'.ADMINPAGE.'/'.$ARGS[0].'/" method="post"', '',
		$form = new HtmlForm('blogpost_form');
		
		$form->AddContainer(new AdminBox('Blogpost options'), 'box1');
		$form->AddTextField('title', 'Title: ', $post->GetTitle());
		$form->AddDropDown('category', 'Cotegory: ', $category_options, $post->GetCategory());
		$form->AddTextField('tags', 'Tags: ', $post->GetTagsAsString());
		
		$form->AddContainer(new AdminBox('What&apos;s on your mind?'), 'box2');
		$form->AddTextField('text', 'Text: ', $post->GetFullText(), 5);
		$form->AddButton('submit', 'Post it!');
		
		$html->AddChild($form);
	}
	
	private function HandleFormInput_Post($admin, $post)
	{
		$value = false;
		$html = $admin->GetContent();
		
		// Handle input for: blog posts
		if (isset($_POST['title']) && !empty($_POST['title']))
		{
			$post->SetTitle($_POST['title']);
			$post->SetCategory($_POST['category']);
			$post->SetText($_POST['content']);
			
			$tags = explode(',', $_POST['tags']);
			foreach ($tags as $tag)
			{
				if ($tag != '')
				{
					$tag = new BlogTag(0, strtolower(trim($tag)));
					$post->AddTag($tag);
				}
			}
			
			/*$wasindb = $post->IsInDB();
			$value = $post->Save();
			
			//$html = $admin->GetContent();
			if ($value)
			{
				if ($wasindb)
				{
					$html->AddChild(new HtmlElement('span', '', 'Blogpost updated'));
				}
				else
				{
					$html->AddChild(new HtmlElement('span', '', 'Blogpost added with id: '.$post->GetId()));
				}
			}
			else
			{
				$html->AddChild(new HtmlElement('span', '', 'Problem saving post'));
			}*/
		}
		else
		{
			$html->AddChild(new HtmlElement('span', '', 'No form input'));
		}
		
		return $value;
	}
}
?>
