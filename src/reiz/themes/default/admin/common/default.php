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
	/*foreach ($PAGE->GetMasterPage()->GetModules() as $module)
	{
		include_once('modules/'.$module.'/module.php');
	}*/
	
	$HTML->AddStylesheet($THEME->GetDirectory().'/styles/default.css');
	
	$HTML->AddElement(
		new RTK_Box('fixedtop', null, null,
			new RTK_Box('menubar', null, null,
				array(
					(new RTK_Link('#'))->AddChild(new RTK_TextView('&nbsp;', 'menubar', true)),
					AdminMenu::GetHtml()
				)
			)
		)
	);
	
	// LAST TIME ON reiZ development:
	//	I was about to fix the admin site
	//	...and remove the need for the layout classes
	//	...by adding their "containers" directly to the htmlpage class
	
	$HTML_optionsbar = new RTK_Box('optionsbar', null, null, $PAGE->GetText());
	$HTML_options = new HtmlElement('ul', 'class="options"', $PAGE->GetText());
	$HTML->AddToBody(new RTK_Box('fixedtop', $PAGE->GetText()));
	$HTML->AddToRight(new RTK_Box('right-fixed', null, new RTK_Link('#top', 'To top', null, 'toplink-right')));
		
	foreach ($PAGE->GetModules() as $module)
	{
		include_once('modules/'.$module[0].'/module.php');
		$MODULE->Initialize();
		foreach ($MODULE->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
		foreach ($MODULE->GetJavascripts() as $js) { $HTML->AddJavascript($js); }
		
		$HTML->AddContent($MODULE->GetHtml($module[1]));
		$HTML->GetRight()->GetChildren()[0]->AddChild($MODULE->GetHtml_RightPane());
	}
}
?>
