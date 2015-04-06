<?php
class HtmlElement_BlogCategories extends HtmlElement
{
	public function __construct($module, $categories)
	{
		if (is_array($categories))
		{
			parent::__construct('ul', 'class="blogcategories"');
			foreach ($categories as $category)
			{
				$this->AddChild(new HtmlElement_BlogCategory($module, $category));
			}
		}
		else { parent::__construct(); }
	}
}
?>
