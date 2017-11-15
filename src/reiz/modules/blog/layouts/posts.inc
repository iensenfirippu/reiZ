<?php
class HtmlElement_Blog_Posts extends HtmlElement
{
	public function __construct($module, $count)
	{
		if (is_array($module->GetPosts()))
		{
			parent::__construct('div', 'class="blog"');
			
			$pagination = null;
			if ($count > BLOGPOSTPRPAGE)
			{
				if (BLOGPAGINATIONINTOP || BLOGPAGINATIONINBOTTOM)
				{
					$pagination = new RTK_Pagination(new URL($module->GetBaseUrl()), $count, BLOGPOSTPRPAGE, $module->GetPage());
				}
				if (BLOGPAGINATIONINTOP) { $GLOBALS['HTML']->AddElement($pagination, 'contenttop'); }
			}
			
			$full = (sizeof($module->GetPosts()) == 1);
			// Print the fetched posts
			foreach ($module->GetPosts() as $post)
			{
				$this->AddToContainer(new HtmlElement_Blog_Post($module, $post, $full));
			}
			
			if ($pagination != null && BLOGPAGINATIONINBOTTOM) { $GLOBALS['HTML']->AddElement($pagination, 'contentbottom'); }
		}
		else { parent::__construct(); }
	}
}
?>
