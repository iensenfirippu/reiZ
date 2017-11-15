<?php
/*
 * Generic class, for containing files found in the filesystem
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
if (defined('reiZ') or exit(1))
{
	class FolderFile
	{
		protected $_BASE_LOCAL = EMPTYSTRING;
		protected $_BASE_LINK = EMPTYSTRING;
		
		protected $_name = EMPTYSTRING;
		protected $_path = EMPTYSTRING;
		protected $_title = EMPTYSTRING;
		protected $_text = EMPTYSTRING;
		
		public function GetName()		{ return $this->_name;		}
		public function GetPath()		{ return $this->_path;		}
		public function GetLocalPath()	{ return $this->_path->GetAbsolutePath($this->_BASE_LOCAL); }
		public function GetLinkPath()	{ return $this->_path->GetAbsolutePath($this->_BASE_LINK); }
		public function GetTitle()		{ return $this->_title;		}
		public function GetText()		{ return $this->_text;		}
		
		public function __construct($url)
		{
			$this->_path = new URL($url);
			$this->_name = $this->_path->GetBasename();
		}
	}
}
?>
