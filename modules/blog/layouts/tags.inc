<?php
class HtmlElement_BlogTags extends HtmlElement
{
	public function __construct($module, $tags)
	{
		if (is_array($tags))
		{
			parent::__construct('ul', 'class="blogtags"', EMPTYSTRING);
			foreach ($tags as $tag)
			{
				$this->AddChild(new HtmlElement_BlogTag($module, $tag));
			}
		}
		else { parent::__construct(); }
	}
}
?>
