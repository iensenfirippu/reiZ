<?php
class HtmlElement_Blog_Categories extends HtmlElement
{
	public function __construct($module, $categories)
	{
		if (is_array($categories))
		{
			parent::__construct('ul', array('class' => 'blogcategories'));
			foreach ($categories as $category)
			{
				$this->AddChild(new HtmlElement_Blog_Category($module, $category));
			}
		}
		else { parent::__construct(); }
	}
}
?>
