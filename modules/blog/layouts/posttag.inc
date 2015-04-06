<?php
class HtmlElement_BlogPostTag extends HtmlElement
{
	public function __construct($module, $tag)
	{
		if (is_a($tag, 'BlogTag'))
		{
			parent::__construct('a', 'href="'.$module->GetBaseUrl().$tag->GetName().'/"', $tag->GetName());
		}
		else { parent::__construct(); }
	}
}
?>
