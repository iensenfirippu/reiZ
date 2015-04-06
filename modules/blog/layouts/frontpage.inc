<?php
class HtmlElement_BlogFrontpage extends HtmlElement
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
					$this->AddChild(new HtmlElement_BlogPost($module, $post, false));
				}
			}
		}
		else { parent::__construct(); }
	}
}
?>
