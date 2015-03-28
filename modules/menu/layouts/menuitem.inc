<?php
class HtmlElement_MenuItem extends HtmlElement
{
	public function __construct($module, $page, $selected)
	{
		if ($page != EMPTYSTRING)
		{
			$putextra = EMPTYSTRING;
			if ($page == $selected) { $putextra = 'class="selected" '; }
			
			parent::__construct('li', EMPTYSTRING, EMPTYSTRING,
				new HtmlElement('a', $putextra.'href="'.URLROOT.INDEXFILE.URLPAGE.$page.'/"', $page)
			);
		}
		else { parent::__construct(); }
	}
}
?>
