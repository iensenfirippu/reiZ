<?php
class HtmlElement_BlogPostTags extends HtmlElement
{
	public function __construct($module, $tags)
	{
		if (is_array($tags))
		{
			parent::__construct();//'ul', 'class="posttags"', EMPTYSTRING);
			foreach ($tags as $tag)
			{
				$this->AddChild(new HtmlElement_BlogPostTag($module, $tag));
			}
		}
		else { parent::__construct(); }
	}
}
?>
