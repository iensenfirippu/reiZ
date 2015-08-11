<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		HTML text definition
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains the definition of a text in HTML
	 **/
	class HtmlText extends HtmlElement
	{
		public function __construct($text=EMPTYSTRING, $args=null, $inline=false)
		{
			if ($args == null || !is_array($args)) { $args = array(); }
			
			$tag = $inline ? 'span' : 'div';
			parent::__construct($tag, HtmlElement::ArgsToString($args), $text);
		}
	}
}
?>