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
				if (BLOGPAGINATIONINTOP) { $this->AddChild($pagination); }
			}
			
			$full = (sizeof($posts) == 1);
			// Print the fetched posts
			foreach ($posts as $post)
			{
				$this->AddChild(new HtmlElement_Blog_Post($module, $post, $full, $page));
			}
			
			if ($pagination != null && BLOGPAGINATIONINBOTTOM) { $this->AddChild($pagination); }
		}
		else { parent::__construct(); }
	}
}
?>
