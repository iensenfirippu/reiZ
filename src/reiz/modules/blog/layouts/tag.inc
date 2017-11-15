<?php
class HtmlElement_Blog_Tag extends HtmlElement
{
	public function __construct($module, $tag)
	{
		if (is_a($tag, 'BlogTag'))
		{
			parent::__construct('li');
			$args = new HtmlAttributes();
			$link = new URL(array($module->GetBaseUrl(), $module->GetCategoryName()));
			if ($tag->GetName() == $module->GetTagName()) { $args->AddClass('selected'); }
			else { $link->Append($tag->GetName()); }
			$this->AddChild(new RTK_Link($link, $tag->GetName(), false, $args));
		}
		else { parent::__construct(); }
	}
}
?>
