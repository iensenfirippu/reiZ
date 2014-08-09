<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	reiZ::CheckLogin();
	
	$HTML = new HtmlPage('Iensenfirippu.dk');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/default.css');
	include_once($THEME->GetDirectory().'/'.FOLDERCOMMON.'/bigbox.php');
	
	$HTML->SetPointer('content');
	$HTML->AddElement(
		new HtmlElement('form', 'name="login" action="" method="post"', '',
			array(
				new HtmlElement('div', 'class="formline"', '',
					array(
						new HtmlElement('label', 'for="username"', 'Username:'),
						new HtmlElement('input', 'type="text" name="username"')
					)
				),
				new HtmlElement('div', 'class="formline"', '',
					array(
						new HtmlElement('label', 'for="password"', 'Password:'),
						new HtmlElement('input', 'type="text" name="password"')
					)
				),
				new HtmlElement('div', 'class="formline"', '',
					new HtmlElement('input', 'type="submit" name="submit" value="Post it"')
				)
			)
		)
	);
	
}
?>
