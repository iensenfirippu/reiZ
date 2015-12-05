<?php
class HtmlElement_Blog_Frontpage extends HtmlElement
{
	public function __construct($module, $categories)
	{
		if (is_array($categories))
		{
			parent::__construct('div', 'class="blog_frontpage"');
			
			foreach ($categories as $posts)
			{
				foreach ($posts as $post)
				{
					$this->AddChild(new HtmlElement_Blog_Post($module, $post, false));
				}
			}
		}
		else { parent::__construct(); }
	}
}
?>
