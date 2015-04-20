<?php
class HtmlImage extends HtmlElement
{
	public function __construct($url=EMPTYSTRING, $alt=EMPTYSTRING, $args=null)
	{
		if ($args == null || !is_array($args)) { $args = array(); }
		$args['src'] = $url;
		$args['alt'] = $alt;
		
		parent::__construct('img', HtmlElement::ArgsToString($args));
	}
}
?>