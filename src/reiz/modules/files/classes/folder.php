<?php
/*
 * GalleryFolder class, for containing folder objects for the files module
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
if (defined('reiZ') or exit(1))
{
	class FilesFolder extends Folder
	{
		protected $_BASE_LOCAL = FILESLOCALPATH;
		protected $_BASE_LINK = FILESLINKPATH;
		
		protected $_hideid = EMPTYSTRING;
		
		public function GetHideId()		{ return $this->_hideid; }
		public function HasInfo()		{ return ($this->_hideid != EMPTYSTRING);	}
		
		public function __construct($url, $loadcontents = true)
		{
			parent::__construct($url);
			
			if ($this->_exists == true)
			{
				$infofile = null;
				$directoryfile = new URL(array($this->_path, 'directory.info'));
				$contentfile = new URL(array($this->_path, 'content.info'));
				if (file_exists($directoryfile->GetAbsolutePath(FILESLOCALPATH)))
				{
					$infofile = file($directoryfile->GetAbsolutePath(FILESLOCALPATH));
				}
				elseif (file_exists($contentfile->GetAbsolutePath(FILESLOCALPATH)))
				{
					$infofile = file($contentfile);
				}
				
				if ($infofile != null && is_array($infofile) && sizeof($infofile > 2))
				{
					$this->_title = trim(preg_replace('/\s\s+/', ' ', $infofile[0]));
					$this->_text = trim(preg_replace('/\s\s+/', ' ', $infofile[1]));
				}
				else { $this->_title = $this->_name; }
				
				if ($this->_title == EMPTYSTRING ) { $this->_title = FILESDEFAULTTITLE; }
				if ($this->_text == EMPTYSTRING ) { $this->_text = FILESDEFAULTTEXT; }
				
				if ($loadcontents) { $this->LoadContents(); }
				elseif ($infofile != null) { $this->_hideid = Site::MakeHideId(); }
			}
		}
		
		protected function LoadSubFolder($file)
		{
			$fileinfo = pathinfo($file);
			
			if ($fileinfo['filename'] != 'thumbs') // ...and not the thumbs dir...
			{
				$this->_subfolders[] = new FilesFolder(new URL(array($this->_path, $file->GetLastFolder())), false);
				//array_push($this->_subfolders, new FilesFolder(url_append($this->_path, $fileinfo['filename']), false));
			}
		}
		
		protected function LoadFile($file)
		{
			$fileinfo = pathinfo($file);
			
			if ($fileinfo['extension'] != 'info') // ...and not a .info file...
			{
				array_push($this->_files, new FilesFile(url_append($this->_path, $fileinfo['basename'])));
			}
		}
		
		public static function Find($name)
		{
			$base = FILESLOCALPATH.SINGLESLASH;
			$url = substr(parent::Recurse($base, $name), strlen($base));
			return new FilesFolder($url, true);
		}
	}
}
?>
