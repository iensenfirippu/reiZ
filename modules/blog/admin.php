<?php
/*
 * home module test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

class CustomAdministrationPage
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
			/*if ($GLOBALS['ARGS'][1] == 'post')
			{
				if ($argcount > 2)
				{
					if ($GLOBALS['ARGS'][2] == 'new') { $this->MakeHtml_EditPost($admin, $args, BlogPost::CreateNew()); }*/
					if ($GLOBALS['ARGS'][1] == 'new') { $this->MakeHtml_EditPost($admin, $args, BlogPost::CreateNew()); }
					//elseif ($argcount > 3)
					elseif ($argcount > 2)
					{
						//$post = BlogPost::LoadFromID($args[3]);
						$post = BlogPost::LoadFromID($args[2]);
						
						//if ($GLOBALS['ARGS'][2] == 'edit') { $this->MakeHtml_EditPost($admin, $args, $post); }
						//elseif ($GLOBALS['ARGS'][2] == 'delete') { $this->MakeHtml_DeletePost($admin, $args, $post); }
						if ($GLOBALS['ARGS'][1] == 'edit') { $this->MakeHtml_EditPost($admin, $args, $post); }
						elseif ($GLOBALS['ARGS'][1] == 'delete') { $this->MakeHtml_DeletePost($admin, $args, $post); }
						else { $showlist = true; }
					}
					else { $showlist = true; }
				/*}
				else { $showlist = true; }
				
				if ($showlist) { $this->MakeHtml_ListPosts($admin, $args); }
			}
			elseif ($GLOBALS['ARGS'][1] == 'tag')
			{
				if ($argcount > 2)
				{*/
					/*if ($GLOBALS['ARGS'][2] == 'new') { $this->MakeHtml_NewTag($admin, $args); }
					elseif ($GLOBALS['ARGS'][2] == 'edit') { $this->MakeHtml_EditTag($admin, $args); }
					elseif ($GLOBALS['ARGS'][2] == 'delete') { $this->MakeHtml_DeleteTag($admin, $args); }
					else { $showhome = true; }*/
				/*}
				else { $this->MakeHtml_ListTags($admin, $args); }
			}
			elseif ($GLOBALS['ARGS'][1] == 'category')
			{
				if ($argcount > 2)
				{*/
					/*if ($GLOBALS['ARGS'][2] == 'new') { $this->MakeHtml_NewCategory($admin, $args); }
					elseif ($GLOBALS['ARGS'][2] == 'edit') { $this->MakeHtml_EditCategory($admin, $args); }
					elseif ($GLOBALS['ARGS'][2] == 'delete') { $this->MakeHtml_DeleteCategory($admin, $args); }
					else { $showhome = true; }*/
				/*}
				else { $this->MakeHtml_ListCategories($admin, $args); }
			}
			else { $showhome = true; }*/
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
		$this->MakeHtml_ListPosts($admin, $args);
		/*$options = $admin->GetOptions();
		
		$options->AddOption('posts', '/'.FOLDERADMIN.'/blog/post/', 'Posts');
		$options->AddOption('tags', '/'.FOLDERADMIN.'/blog/tag/', 'Tags');
		$options->AddOption('categories', '/'.FOLDERADMIN.'/blog/category/', 'Categories');
		
		$html = $admin->GetContent();
		
		$box = new AdminBox('Edit Blog...');
		$box->AddContent(new HtmlElement('span', '', $this->_text_main));
		
		$html->AddChild($box);*/
	}
	
	private function MakeHtml_ListPosts($admin, $args)
	{
		$options = $admin->GetOptions();
		//$options->AddOption('home', '/'.FOLDERADMIN.'/blog/', 'Back');
		//$options->AddOption('write', '/'.FOLDERADMIN.'/blog/post/new', 'Write', '/'.FOLDERCOMMON.'/images/oxygen/16/list-add.png');
		$options->AddOption('home', '/'.FOLDERADMIN.'/', 'Back');
		$options->AddOption('write', '/'.FOLDERADMIN.'/blog/new', 'Write', '/'.FOLDERCOMMON.'/images/oxygen/16/list-add.png');
		
		$html = $admin->GetContent();
		$posts = Blogpost::LoadAll(null, 0, 50, 'added', DBOD::Desc);
		
		$box = new AdminBox('List');
		$list = new HtmlList(array('Title', 'Posted', 'Edited', EMPTYSTRING));
		foreach ($posts as $post)
		{
			$links = new HtmlElement();
			$links->AddChild(new HtmlElement('a', 'href="/blog/'.$post->GetPostID().'/"', 'V'));
			//$links->AddChild(new HtmlElement('a', 'href="/'.ADMINPAGE.'/blog/post/edit/'.$post->GetPostID().'/"', 'E'));
			//$links->AddChild(new HtmlElement('a', 'href="/'.ADMINPAGE.'/blog/post/delete/'.$post->GetPostID().'/"', 'D'));
			$links->AddChild(new HtmlElement('a', 'href="/'.ADMINPAGE.'/blog/edit/'.$post->GetPostID().'/"', 'E'));
			$links->AddChild(new HtmlElement('a', 'href="/'.ADMINPAGE.'/blog/delete/'.$post->GetPostID().'/"', 'D'));
			$list->AddRow(array($post->GetTitle(), reiZ::TimestampToHumanTime($post->GetPosted(), TF::DateTime),
				reiZ::TimestampToHumanTime($post->GetEdited(), TF::DateTime), $links));
		}
		$box->AddContent($list);
		
		$html->AddChild($box);
	}
	
	/*private function MakeHtml_ListTags($admin, $args)
	{
		$options = $admin->GetOptions();
		$options->AddOption('home', '/'.FOLDERADMIN.'/blog/', 'Back');
		//$options->AddOption('write', '/'.FOLDERADMIN.'/blog/tag/new', 'New', '/'.FOLDERCOMMON.'/images/oxygen/16/list-add.png');
		
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
	}*/
	
	/*private function MakeHtml_ListCategories($admin, $args)
	{
		$options = $admin->GetOptions();
		$options->AddOption('home', '/'.FOLDERADMIN.'/blog/', 'Back');
		$options->AddOption('write', '/'.FOLDERADMIN.'/blog/category/new', 'New', '/'.FOLDERCOMMON.'/images/oxygen/16/list-add.png');
		
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
	}*/
	
	private function MakeHtml_EditPost($admin, $args, $post)
	{
		$categories = BlogCategory::LoadAll();
		
		if (isset($_POST['title']) && !empty($_POST['title']))
		{
			$new = ($post->GetPostID() == null);
			
			$post->SetTitle($_POST['title']);
			$post->SetCategory($_POST['category']);
			$post->SetText($_POST['content']);
			$post->GetTagCollection()->ImportFromString($_POST['tags']);
			$saved = $post->Save();
			
			if ($new && $saved) { reiZ::Redirect('/'.ADMINPAGE.'/blog/edit/'.$post->GetPostID()); }
		}
		
		$options = $admin->GetOptions();
		//$options->AddOption('home', '/'.FOLDERADMIN.'/blog/post/', 'Back');
		$options->AddOption('home', '/'.FOLDERADMIN.'/blog/', 'Back');
		
		$html = $admin->GetContent();
		
		$category_options = array();
		foreach ($categories as $c) { array_push($category_options, array($c->GetId(), $c->GetTitle())); }
		$selected = 0;
		if ($post->GetCategory() != null) { $selected = $post->GetCategory()->GetID(); }
		
		$form = new HtmlForm('blogpost_form');
		
		$form->AddContainer(new AdminBox('Blogpost options'), 'box1');
		$form->AddTextField('title', 'Title: ', $post->GetTitle());
		$form->AddDropDown('category', 'Category: ', $category_options, $selected);
		$form->AddTextField('tags', 'Tags: ', $post->GetTagCollection());
		
		$form->AddContainer(new AdminBox('What&apos;s on your mind?'), 'box2');
		$form->AddTextField('content', 'Content: ', $post->GetFullText(), 5);
		$form->AddButton('submit', 'Post it!');
		
		$html->AddChild($form);
	}
	
	private function MakeHtml_DeletePost($admin, $args, $post)
	{
		if (isset($_POST['submit']))
		{
			$post->Delete();
			reiZ::Redirect('/'.ADMINPAGE.'/blog/');
		}
		
		$admin->AddJavascript(FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/hider.js');
		
		$options = $admin->GetOptions();
		//$options->AddOption('home', '/'.FOLDERADMIN.'/blog/post/', 'Back');
		$options->AddOption('home', '/'.FOLDERADMIN.'/blog/', 'Back');
		$hideid = reiZ::MakeHideId();
		
		$html = $admin->GetContent();
		
		$form = new HtmlForm('blogpost_form');
		
		$form->AddContainer(new AdminBox('Blogpost options'), 'box1');
		$form->AddText('Are you sure you want to delete the post "'.$post->GetTitle().'"?');
		$form->AddElement(new HtmlElement('button', 'type="button" onclick="showdiv(\''.$hideid.'\')"', 'Delete'));
		
		$form->AddContainer(
			new HtmlElement(
				'div',
				'id="'.$hideid.'" class="hidden"',
				null,
				new HtmlElement(
					'div',
					'class="popupbackground"',
					null,
					new HtmlElement(
						'div',
						'class="popup"',
						'Are you really sure you want to delete this post?'.NEWLINE.
						'This action cannot be undone.'.NEWLINE.NEWLINE
					)
				)
			),
			'popup'
		);
		$form->AddButton('submit', 'Yes, delete it!', 'popup');
		$form->AddElement(new HtmlElement('button', 'type="button" onclick="hidediv(\''.$hideid.'\')"', 'Cancel'));
		
		$html->AddChild($form);
	}
	
	/*private function HandleFormInput_Post($admin, $post)
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
			}*/
			
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
		/*}
		else
		{
			$html->AddChild(new HtmlElement('span', '', 'No form input'));
		}
		
		return $value;
	}*/
}
?>
