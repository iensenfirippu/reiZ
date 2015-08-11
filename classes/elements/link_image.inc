<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		HTML link/image definition
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains the definition of an image with the properties of a link (a/anchor) in HTML
	 **/
	class HtmlLink_Image extends HtmlLink
	{
		public function __construct($link=EMPTYSTRING, $image=EMPTYSTRING, $title=EMPTYSTRING, $linkargs=null, $imageargs=null)
		{
			parent::__construct($link, EMPTYSTRING, $linkargs);
			$this->AddChild(new HtmlImage($image, $title, $imageargs));
			$this->SetOneLine(true);
		}
	}
}
?>