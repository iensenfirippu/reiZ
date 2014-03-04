<?php
/*
 * home module test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (WasAccessedDirectly()) { BackToDisneyland(); }
else
{
	foreach (glob(FOLDERADMIN.'/'.FOLDERCLASSES.'/*.php') as $class) { include_once($class); }
	include_once(FOLDERADMIN.'/'.FOLDERMASTER.'/default.php');
	
	$argscount = sizeof($ARGS);
	$file = FOLDERADMIN.'/default.php';
	
	if ($argscount > 0)
	{
		$modulefile = FOLDERMODULES.'/'.$ARGS[0].'/admin.php';
		if ($ARGS[0] == 'themes') { $file = FOLDERADMIN.'/themes.php'; }
		elseif ($ARGS[0] == 'modules') { $file = FOLDERADMIN.'/modules.php'; }
		elseif (file_exists($modulefile))
		{
			include_once($modulefile);
		}
	}
	else
	{
		include_once($file);
	}
}
?>
