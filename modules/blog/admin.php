<?php
/*
 * home module test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

class CustomAdmininistrationPage
{
	public function __construct()
	{
		// include all blog related classes
		foreach (glob(FOLDERMODULES.'/blog/'.FOLDERCLASSES.'/*.php') as $classfile) { include_once($classfile); }
	}
	
	private function HandleFormInput()
	{
		// Handle input for: blog posts
		if (isset($_POST['title']) && !empty($_POST['title']))
		{
			$post = new BlogPost(0, time(), $_POST['category'], $_POST['title'], $_POST['text']);
			
			$tags = explode(',', $_POST['tags']);
			foreach ($tags as $tag)
			{
				if ($tag != '')
				{
					$tag = new BlogTag(0, strtolower(trim($tag))); $post->AddTag($tag);
				}
			}
			
			$post->Save();
			$GLOBALS['HTML']->AddContent(new HtmlElement('span', '', 'Blogpost added with id: '));
		}
	}
	
	public function MakeHtml($admin, $args)
	{
		$this->HandleFormInput();
		
		$argcount = sizeof($GLOBALS['ARGS']);
		if ($argcount == 1) { $this->MakeHtml_HOME($admin, $args); }
		elseif ($GLOBALS['ARGS'][1] == 'write') { $this->MakeHtml_NewPost($admin, $args); }
	}
	
	public function MakeHtml_Options($admin, $args)
	{
		$options = $admin->GetOptions();
		
	}
	
	private function MakeHtml_Home($admin, $args)
	{
		$html = $admin->GetContent();
		
		$box = new AdminBox('Edit Blog...');
		$box->AddContent(new HtmlElement('span', '', 'Are you almost ready to edit your blog?'));
		$box->AddContent(new HtmlElement('a', 'href="/'.ADMINPAGE.'/'.$args[0].'/write/"', 'write'));
		
		$html->AddChild($box);
	}
	
	private function MakeHtml_NewPost($admin, $args)
	{
		$html = $admin->GetContent();
		
		$options = array();
		$categories = BlogCategory::LoadAll();
		foreach ($categories as $c) { array_push($options, array($c->GetId(), $c->GetTitle())); }
		
		//	new HtmlElement('form', 'name="add" action="/'.ADMINPAGE.'/'.$ARGS[0].'/" method="post"', '',
		$form = new HtmlForm('blogpost_form');
		
		$form->AddContainer(new AdminBox('Blogpost options'), 'box1');
		$form->AddTextField('title', 'Title: ');
		$form->AddDropDown('category', 'Cotegory: ', $options);
		$form->AddTextField('tags', 'Tags: ');
		
		$form->AddContainer(new AdminBox('What&apos;s on your mind?'), 'box2');
		$form->AddTextField('text', 'Text: ', 5);
		$form->AddButton('submit', 'Post it!');
		
		$html->AddChild($form);
	}
}
?>
