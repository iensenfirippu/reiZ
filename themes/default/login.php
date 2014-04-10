<?php
/*
 * home module test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	if (isset($_POST['username']) && !empty($_POST['username']))
	{
		$success = false;
		
		$query = new Query();
		$query->SetType('select');
		$query->AddField('u_id');
		$query->AddTable(DBPREFIX.'user');
		$query->AddCondition('username', '=', reiZ::Sanitize($_POST['username']));
		$query->AddCondition('password', '=', reiZ::Sanitize($_POST['password']));
		$result = $GLOBALS['DB']->RunQuery($query);
		//var_dump($query);
		$row = $GLOBALS['DB']->GetArray($result);
		
		if ($row['u_id'] != 0)
		{
			$success = true;
			$_SESSION["verysecureuserid"] = $row['u_id'];
		}
		
		$query = new Query();
		$query->SetType('insert');
		$query->AddTable(DBPREFIX.'login');
		$query->AddField('occured', time());
		$query->AddField('ip', reiZ::Sanitize($_SERVER['REMOTE_ADDR']));
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{ $query->AddField('altip', reiZ::Sanitize($_SERVER['HTTP_X_FORWARDED_FOR'])); }
		$query->AddField('username', reiZ::Sanitize($_POST['username']));
		if (!$success)
		{ $query->AddField('password', reiZ::Sanitize($_POST['password'])); }
		$query->AddField('success', $success);
		//var_dump($query);
		$GLOBALS['DB']->RunNonQuery($query);
		
		if ($success) { reiZ::BackToDisneyland(); }
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
