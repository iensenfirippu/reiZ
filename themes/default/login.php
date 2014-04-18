<?php
/*
 * home module test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	if (isset($_POST['username']) && !empty($_POST['username']))
	{
		reiZ::Login($_POST['username'], $_POST['password']);
	}
	
	include_once(FOLDERTHEMES.'/'.DEFAULTTHEME.'/'.FOLDERMASTER.'/default.php');
	$HTML->AddContent(
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
