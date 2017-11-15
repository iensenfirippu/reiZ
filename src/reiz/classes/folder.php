<?php
/*
 * Folder class, for objects representing a specifc folder on disk
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
if (defined('reiZ') or exit(1))
{
	class Folder
	{
		protected $_BASE_LOCAL = EMPTYSTRING;
		protected $_BASE_LINK = EMPTYSTRING;
		
		protected $_name = EMPTYSTRING;
		protected $_path = EMPTYSTRING;
		protected $_title = EMPTYSTRING;
		protected $_text = EMPTYSTRING;
		protected $_subfolders = array();
		protected $_files = array();
		protected $_exists = false;
		
		public function GetName()		{ return $this->_name; }
		public function GetPath()		{ return $this->_path; }
		public function GetLocalPath()	{ return $this->_path->GetAbsolutePath($this->_BASE_LOCAL); }
		public function GetLinkPath()	{ return $this->_path->GetAbsolutePath($this->_BASE_LINK); }
		public function GetTitle()		{ return $this->_title; }
		public function GetText()		{ return $this->_text; }
		public function GetSubfolders()	{ return $this->_subfolders; }
		public function GetFiles()		{ return $this->_files; }
		public function Exists()		{ return $this->_exists; }
		
		public function __construct($url)
		{
			$this->_path = new URL($url);
			$this->_name = $this->_path->GetFilename();
			$this->_exists = file_exists($this->GetLocalPath());
		}
		
		protected function LoadContents()
		{
			if ($this->_exists) {
				foreach (glob(new URL(array($this->GetLocalPath(), '*'))) as $file)
				{
					$url = new URL($file);
					
					// if file is directory...
					if (is_dir($file)) { $this->LoadSubFolder($url); }
					
					// otherwise it's a file
					else { $this->LoadFile($url); }
				}
			}
		}
		
		protected function LoadSubFolder($file)
		{
			$this->_subfolders[] = new Folder(new URL(array($this->_path, $file->GetLastFolder())), false);
		}
		
		protected function LoadFile($file)
		{
			$this->_files[] = new FolderFile($file);
		}
		
		/**
		 * Finds the folder path of the first occurance of name in the base folder
		 * @param name, the directory name to search for.
		 */
		protected static function Find($name)
		{
			//$base = $this->_BASE_LOCAL.SINGLESLASH;
			$base = SINGLESLASH; // <- Momentary hack
			$url = substr(Folder::Recurse($base, $name), strlen($base));
			return new Folder($url, true);
		}
		
		/**
		 * Traverses the gallery folder and returns the path of the first occurance of $name
		 * @param dir, the directory to recurse.
		 * @param name, the directory name to search for.
		 */
		protected static function Recurse($dir, $name)
		{
			$value = null;
			
			$subdirs = glob($dir.'*/');
			
			for ($i = 0; $i < sizeof($subdirs); $i++)
			{
				$newdir = $subdirs[$i];
				$dirname = url_lastfolder($newdir);
				
				if ($dirname == $name) { $value = $newdir; }
				else { $value = Folder::Recurse($newdir, $name); }
				
				if ($value != null) { $i = sizeof($subdirs); }
			}
			
			return $value;
		}
	}
	
	//$folder = new Folder("/srv/http/content/gallery", true);
	//var_dump($folder);
	//die();
}
?>
