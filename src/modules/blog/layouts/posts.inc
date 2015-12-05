<?php
class HtmlElement_Blog_Posts extends HtmlElement
{
	public function __construct($module, $posts, $count, $page)
	{
		if (is_array($posts))
		{
			parent::__construct('div', 'class="blog"');
			
			$pagination = null;
			if ($count > BLOGPOSTPRPAGE)
			{
				if (BLOGPAGINATIONINTOP || BLOGPAGINATIONINBOTTOM)
				{
					$pagination = new Pagination($module->GetFullUrl(), $count, BLOGPOSTPRPAGE, $page);
				}
				if (BLOGPAGINATIONINTOP) { $GLOBALS['HTML']->AddElement($pagination, 'contenttop'); }
			}
			
			//reiZ::vd($GLOBALS['HTML']);
			
			$full = (sizeof($posts) == 1);
			// Print the fetched posts
			foreach ($posts as $post)
			{
				$this->AddToContainer(new HtmlElement_Blog_Post($module, $post, $full, $page));
			}
			
			if ($pagination != null && BLOGPAGINATIONINBOTTOM) { $GLOBALS['HTML']->AddElement($pagination, 'contentbottom'); }
		}
		else { parent::__construct(); }
	}
}
?>
