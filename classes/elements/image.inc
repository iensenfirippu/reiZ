<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		HTML image definition
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains the definition of an image in HTML
	 **/
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
}
?>