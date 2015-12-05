<?php
class HtmlElement_Menu extends HtmlElement
{
	public function __construct($module, $pages, $selected)
	{
		if (is_array($pages))
		{
			parent::__construct('ul', 'class="menu"');
			
			foreach ($pages as $page)
			{
				$this->AddChild(new HtmlElement_MenuItem($module, $page, $selected));
			}
		}
		else { parent::__construct(); }
	}
}
?>