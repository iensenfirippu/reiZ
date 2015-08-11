<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		HTML button definition
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains definitions of a button in HTML
	 **/
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
}
?>