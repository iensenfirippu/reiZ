<?php
class HtmlElement_Blog_PostTags extends HtmlElement
{
	public function __construct($module, $tags)
	{
		if (is_array($tags))
		{
			parent::__construct('div', 'class="blogposttags"', EMPTYSTRING);
			foreach ($tags as $tag)
			{
				$this->AddChild(new HtmlElement_Blog_PostTag($module, $tag));
			}
		}
		else { parent::__construct(); }
	}
}
?>