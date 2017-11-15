<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		first deafult theme of administration of reiZ CMS
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') && class_exists('Administration') or exit(1))
{
	//include_once($THEME->GetDirectory().'/common/default.php');
	$HTML->AddStylesheet($THEME->GetDirectory().'/styles/default.css');



	//$HTML = new HtmlDocument('Iensenfirippu.dk');
	
	// HEAD
	//$HTML->SetPointer('HEAD');
	//$HTML->AddElement(new HtmlElement('meta', 'charset="UTF-8"'));
	//$HTML->AddElement(new HtmlElement('link', 'rel="icon" type="image/png" href="'.GetBaseUrl().'/common/images/favicon.png"'));
	//$HTML->AddStylesheet($THEME->GetDirectory().'/styles/default.css');
	//foreach ($PAGE->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
	//foreach ($PAGE->GetJavascripts() as $js) { $HTML->AddJavascript($js); }
	
	// BODY
	$HTML->AddElement(new RTK_Box('fixedtop'), 'BODY', 'fixedtop');
	$HTML->AddElement(new RTK_Box('menubar'), 'fixedtop', 'menubar');
	$HTML->AddElement(new RTK_Link(new URL(ADMINPAGE), '&nbsp;', false, array('id' => 'logo')), 'menubar', 'logo');
	$HTML->AddElement($PAGE->GetMenu(), 'menubar', 'menu');
	
	$HTML->AddElement(new RTK_Box('wrapper'), 'BODY', 'wrapper');
	$HTML->AddElement(new RTK_Box('optionsbar', null, null, $PAGE->GetOptions()), 'wrapper', 'optionsbar');
	//$HTML->AddElement(new HtmlElement('ul', 'class="options"', EMPTYSTRING, Administration::GetOptions()), 'optionsbar', 'options');
	
	// content
	$HTML->AddElement($PAGE->GetContent(), 'wrapper');
}
?>
