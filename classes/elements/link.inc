<?php
class HtmlLink extends HtmlElement
{
	public function __construct($url=EMPTYSTRING, $name=EMPTYSTRING, $args=EMPTYSTRING)
	{
		if ($args != EMPTYSTRING) { $args = SINGLESPACE.$args; }
		parent::__construct('a', 'href="'.$url.'"'.$args, $name);
	}
}
?>