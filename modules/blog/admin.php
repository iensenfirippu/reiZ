<?php
/*
 * home module test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (WasAccessedDirectly()) { BackToDisneyland(); }
else
{
	foreach (glob(FOLDERMODULES.'/blog/'.FOLDERCLASSES.'/*.php') as $classfile) { include_once($classfile); }
	
	// Handle form input
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
		$HTML->AddContent(new HtmlElement('span', '', 'Blogpost added with id: '));
	}
	
	$argcount = sizeof($ARGS);
	if ($argcount == 1)
	{
		$HTML->AddContent(new HtmlElement('h1', '', 'Edit blog...'));
		$HTML->AddContent(new HtmlElement('span', '', 'Are you almost ready to edit your blog?'));
		$HTML->AddContent(new HtmlElement('a', 'href="/'.ADMINPAGE.'/'.$ARGS[0].'/write/"', 'write'));
	}
	elseif ($ARGS[1] == 'write')
	{
		$categories = BlogCategory::LoadAll();
		$selectcat = new HtmlElement('select', 'name="category"', '');
		foreach ($categories as $c)
		{ $selectcat->AddChild(new HtmlElement('option', 'value="'.$c->GetId().'"', $c->GetTitle())); }
		
		$HTML->AddContent(new HtmlElement('h1', '', 'Write a blog post'));
		$HTML->AddContent(new HtmlElement('span', '', 'Whats&apos;s on your mind?'));
		//	new HtmlElement('form', 'name="add" action="/'.ADMINPAGE.'/'.$ARGS[0].'/" method="post"', '',
		$HTML->AddContent(
			new HtmlElement('form', 'name="add" action="" method="post"', '',
				array(
					new HtmlElement('div', 'class="formline"', '',
						array(
							new HtmlElement('label', 'for="category"', 'Category:'),
							$selectcat
						)
					),
					new HtmlElement('div', 'class="formline"', '',
						array(
							new HtmlElement('label', 'for="title"', 'Title:'),
							new HtmlElement('input', 'type="text" name="title"')
						)
					),
					new HtmlElement('div', 'class="formline"', '',
						array(
							new HtmlElement('label', 'for="text"', 'Text:'),
							new HtmlElement('textarea', 'name="text" rows="5"')
						)
					),
					new HtmlElement('div', 'class="formline"', '',
						array(
							new HtmlElement('label', 'for="tags"', 'Tags:'),
							new HtmlElement('input', 'type="text" name="tags"')
						)
					),
					new HtmlElement('div', 'class="formline"', '',
						new HtmlElement('input', 'type="submit" name="submit" value="Post it"')
					)
				)
			)
		);
	}
}
?>
