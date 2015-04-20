<?php
class HtmlElement_Blog_Category extends HtmlElement
{
	public function __construct($module, $category)
	{
		if (is_a($category, 'BlogCategory'))
		{
			parent::__construct('li');
			$this->AddChild(new HtmlElement('a', 'href="'.$module->GetBaseUrl().ucfirst($category->GetName()).'/"', $category->GetTitle()));
		}
		else { parent::__construct(); }
	}
}
?>
