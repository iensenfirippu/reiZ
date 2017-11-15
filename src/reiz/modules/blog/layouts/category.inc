<?php
class HtmlElement_Blog_Category extends HtmlElement
{
	public function __construct($module, $category)
	{
		if (is_a($category, 'BlogCategory'))
		{
			parent::__construct('li');
			$args = new HtmlAttributes();
			$link = new URL($module->GetBaseUrl());
			if ($category->GetName() == $module->GetCategoryName()) {
				$args->AddClass('selected');
				$link->Append(BLOGDEFAULTCATEGORY);
			}
			else { $link->Append($category->GetName()); }
			$link->Append($module->GetTagName());
			$this->AddChild(new RTK_Link($link, $category->GetTitle(), false, $args));
		}
		else { parent::__construct(); }
	}
}
?>
