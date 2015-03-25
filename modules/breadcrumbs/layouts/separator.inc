<?php
class HtmlElement_Separator extends HtmlElement
{
	public function __construct($module)
	{
		parent::__construct('li', EMPTYSTRING, EMPTYSTRING,
			new HtmlElement('span', 'class="separator"', BREADCRUMBSEPARATOR)
		);
	}
}
?>
