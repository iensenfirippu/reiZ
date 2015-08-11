<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		first deafult theme for reiZ CMS
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	include_once($THEME->GetDirectory().'/'.FOLDERCOMMON.'/default.php');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/default.css');
	
	// - right
	$HTML->AddElement(new HtmlElement('div', 'id="right"'), 'main', 'right', 3);
	$HTML->AddElement(new HtmlElement('a', 'class="hidden" id="kb_rightpane"'));
	$HTML->AddElement(new HtmlElement('div', 'id="right-fixed"', EMPTYSTRING,
		new HtmlElement('a', 'class="toplink-right" href="#top"', 'To top')), 'right', 'right-fixed');
	
	$HTML->SetPointer('contentmain');
	$HTML->AddElement(new HtmlElement('div', EMPTYSTRING, $PAGE->GetContent()));
	
	$rightpane = null;
	if ($PAGE->GetModule() != null) { $rightpane = $PAGE->GetModule()->GetHtml('rightpane'); }
	$HTML->AddElement($rightpane, 'right-fixed');
}
?>
