<?php
/*
 * Include file, for including all files containing classes
 * Copyright 2015 Philip Jensen <me@iensenfirippu.dk>
 */

// DataTypes (.typ.inc) files should be included first, as they may be used in the other classes. 
foreach (glob(FOLDERCLASSES."/*.typ.inc") as $classfile) { include_once($classfile); }

// Class (.cls.inc) files should be included second.
foreach (glob(FOLDERCLASSES."/*.cls.inc") as $classfile) { include_once($classfile); }

// the .inc and .php files in the classes folder aren't always used, so they should not be automatically included.
?>