<?php
class HtmlImage extends HtmlElement
{
	public function __construct($url=EMPTYSTRING, $title=EMPTYSTRING, $args=EMPTYSTRING)
	{
		if ($args != EMPTYSTRING) { $args = SINGLESPACE.$args; }
		parent::__construct('img', 'src="'.$url.'" title="'.$title.'"'.$args);
	}
}
?>