<?php
class HtmlButton extends HtmlElement
{
	public function __construct($name='submit', $title='Submit', $args=null)
	{
		if ($args == null || !is_array($args)) { $args = array(); }
		$args['name'] = $name;
		$args['value'] = $title;
		$args['type'] = 'submit';
		$args['class'] = 'submit';
		
		parent::__construct('input', HtmlElement::ArgsToString($args));
	}
}
?>