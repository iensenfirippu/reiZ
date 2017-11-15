<?php
/*
 * FilesFile class, for containing file objects for the files module
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	class FilesFile extends FolderFile
	{
		protected $_hideid = EMPTYSTRING;
		protected $_preview = EMPTYSTRING;
		protected $_type = EMPTYSTRING;
		
		public function GetHideId()		{ return $this->_hideid;	}
		public function GetPreview()	{ return $this->_preview;	}
		public function GetType()		{ return $this->_type;		}
		public function HasInfo()		{ return ($this->_hideid != EMPTYSTRING);	}
		public function HasPreview()	{ return ($this->_preview != EMPTYSTRING);	}
		
		public function __construct($url)
		{
			parent::__construct($url);
			
			//$this->_type = strtolower($this->_path->GetFileExtension());
			$pathinfo = pathinfo($url);
			
			$this->_type = strtolower($pathinfo['extension']);
			$infofile = url_append(FILESLINKPATH, array($pathinfo['dirname'], $pathinfo['filename'].'.'.$pathinfo['extension'].'.info'));
			if (file_exists($infofile))
			{
				$infofile = file($infofile);
				$this->_hideid = Site::MakeHideId();
				$this->_title = trim(preg_replace('/\s\s+/', ' ', $infofile[0]));
				$this->_text = trim(preg_replace('/\s\s+/', ' ', $infofile[1]));
			}
			else
			{
				$this->_title = $pathinfo['basename'];
			}
			
			$previewimage = url_append(FILESLINKPATH, array($pathinfo['dirname'], 'thumbs', $pathinfo['filename'].'.'.$pathinfo['extension'].'.jpg'));
			if (file_exists($previewimage)) { $this->_preview = $previewimage; }
		}
	}
}
?>