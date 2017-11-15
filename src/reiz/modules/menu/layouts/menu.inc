<?php
class HtmlElement_Menu extends HtmlElement
{
	// New format
	/*public function __construct($menu)
	{
		if (is_a($menu, 'Reiz_Menu'))
		{
			// do layout
		}
	}*/
	
	public function __construct($module, $pages, $selected)
	{
		if (is_array($pages))
		{
			parent::__construct('ul', array('class' => 'menu'));
			
			foreach ($pages as $page)
			{
				$this->AddChild(new HtmlElement_MenuItem($module, $page, $selected));
			}
		}
		else { parent::__construct(); }
	}
}
?>