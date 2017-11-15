<?php
/*
 * Include file, for including all files containing classes
 * Copyright 2015 Philip Jensen <me@iensenfirippu.dk>
 */

 
 // TODO: Consider adding require_once statements to the top of all files instead.
 //	That way unused classes are not included.
 

if (false) { // change to some debug flag eventually

	// DataTypes files should be included first, as they may be used in the other classes.
	foreach (glob("reiz/types/*.inc") as $classfile) { require_once($classfile); }
	
	// Class files should be included second.
	foreach (glob("reiz/classes/db/*.inc") as $classfile) { require_once($classfile); }
	foreach (glob("reiz/classes/*.inc") as $classfile) { require_once($classfile); }
	foreach (glob("reiz/classes/widget/*.inc") as $classfile) { require_once($classfile); }
	
} else { // the .inc and .php files in the classes folder aren't always used, so they should not be automatically included.
	
	// DataTypes files should be included first, as they may be used in the other classes.
	require_once("reiz/classes/types/site.inc");
	require_once("reiz/classes/types/string.inc");
	require_once("reiz/classes/types/array.inc");
	require_once("reiz/classes/types/boolean.inc");
	require_once("reiz/classes/types/color.inc");
	require_once("reiz/classes/types/file.inc");
	require_once("reiz/classes/types/log.inc");
	require_once("reiz/classes/types/time.inc");
	require_once("reiz/classes/types/url.inc");
	require_once("reiz/classes/types/list.inc");
	require_once("reiz/classes/types/uuid.inc");
	
	// Class files should be included second.
	require_once("reiz/classes/db/condition.inc");
	require_once("reiz/classes/db/database.inc");
	require_once("reiz/classes/db/field.inc");
	require_once("reiz/classes/db/param.inc");
	require_once("reiz/classes/db/query.inc");
	require_once("reiz/classes/databaseobject.inc");
	require_once("reiz/classes/masterpage.inc");
	require_once("reiz/classes/module.inc");
	require_once("reiz/classes/page.gdo.inc");
	//require_once("reiz/classes/page.inc");
	require_once("reiz/classes/request.inc");
	require_once("reiz/classes/settings.inc");
	require_once("reiz/classes/theme.inc");
	require_once("reiz/classes/folder.php");
	require_once("reiz/classes/file.php");
	
	//require_once("reiz/classes/rtk.php");
	require_once("reiz/classes/htmlattributes.php");
	require_once("reiz/classes/htmlelement.php");
	require_once("reiz/classes/htmldocument.php");
	
	require_once("reiz/classes/widget/box.php");
	require_once("reiz/classes/widget/button.php");
	require_once("reiz/classes/widget/commentview.php");
	require_once("reiz/classes/widget/dropdown.php");
	require_once("reiz/classes/widget/form.php");
	require_once("reiz/classes/widget/formline.php");
	require_once("reiz/classes/widget/header.php");
	require_once("reiz/classes/widget/image.php");
	require_once("reiz/classes/widget/link.php");
	require_once("reiz/classes/widget/bulletlist.php");
	require_once("reiz/classes/widget/listview.php");
	//require_once("reiz/classes/widget/menu.php");
	require_once("reiz/classes/widget/pagination.php");
	require_once("reiz/classes/widget/textview.php");
}
?>
