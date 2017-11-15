<?php
class HtmlElement_Blog_PostTag extends RTK_Link
{
	public function __construct($module, $tag)
	{
		if (is_a($tag, 'BlogTag'))
		{
			parent::__construct(new URL(array($module->GetBaseUrl(), $module->GetCategoryName(), $tag->GetName())), $tag->GetName());
		}
		else { parent::__construct(); }
	}
}
?>
