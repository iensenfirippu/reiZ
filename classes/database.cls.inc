<?php
if (file_exists(FOLDERCLASSES."/dbvendor/".DBVENDOR))
{
	foreach (glob(FOLDERCLASSES."/dbvendor/".DBVENDOR."/*.inc") as $classfile) { include_once($classfile); }
}
?>