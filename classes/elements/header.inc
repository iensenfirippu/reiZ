<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		HTML Header definition
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains the definition of a header in HTML
	 **/
	class HtmlHeader extends HtmlElement
	{
		public function __construct($level=1, $text=EMPTYSTRING, $args=null)
		{
			if ($args == null || !is_array($args)) { $args = array(); }
			
			$tag = is_numeric($level) && $level > 0 && $level < 9 ? 'h'.$level : 'h1';
			parent::__construct($tag, HtmlElement::ArgsToString($args), $text);
		}
	}
}
?>