<?php
class HtmlElement_Breadcrumb extends HtmlElement
{
	public function __construct($module, $breadcrumb, $flag)
	{
		if (is_a($breadcrumb, "Breadcrumb"))
		{
			$class = EMPTYSTRING;
			if ($flag < 0) { $class = 'class="first"'; }
			if ($flag > 0) { $class = 'class="last"'; }
			
			parent::__construct('li', $class, EMPTYSTRING,
				new HtmlElement('a', 'href="'.$breadcrumb->GetUrl().'"', $breadcrumb->GetName())
			);
		}
		else { parent::__construct(); }
	}
}
?>
