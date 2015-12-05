<?php
/*
 * FilesFile class, for containing file objects for the files module
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	class FilesFile
	{
		protected $_hideid = EMPTYSTRING;
		protected $_name = EMPTYSTRING;
		protected $_url = EMPTYSTRING;
		protected $_link = EMPTYSTRING;
		protected $_preview = EMPTYSTRING;
		protected $_type = EMPTYSTRING;
		protected $_title = EMPTYSTRING;
		protected $_text = EMPTYSTRING;
		
		public function GetHideId()		{ return $this->_hideid;	}
		public function GetName()		{ return $this->_name;		}
		public function GetUrl()		{ return $this->_url;		}
		public function GetLink()		{ return $this->_link;		}
		public function GetPreview()	{ return $this->_preview;	}
		public function GetType()		{ return $this->_type;		}
		public function GetTitle()		{ return $this->_title;		}
		public function GetText()		{ return $this->_text;		}
		
		public function HasInfo()		{ return ($this->_hideid != EMPTYSTRING);	}
		public function HasPreview()	{ return ($this->_preview != EMPTYSTRING);	}
		
		public function __construct($url)
		{
			$pathinfo = pathinfo($url);
			
			$this->_name = $pathinfo['basename'];
			$this->_url = $url;
			$this->_link = reiZ::url_append(FILESDIR, $url);
			$this->_type = strtolower($pathinfo['extension']);
			$infofile = reiZ::url_append(FILESDIR, array($pathinfo['dirname'], $pathinfo['filename'].'.'.$pathinfo['extension'].'.info'));
			if (file_exists($infofile))
			{
				$infofile = file($infofile);
				$this->_hideid = reiZ::MakeHideId();
				$this->_title = trim(preg_replace('/\s\s+/', ' ', $infofile[0]));
				$this->_text = trim(preg_replace('/\s\s+/', ' ', $infofile[1]));
			}
			else
			{
				$this->_title = $pathinfo['basename'];
			}
			
			$previewimage = reiZ::url_append(FILESDIR, array($pathinfo['dirname'], 'thumbs', $pathinfo['filename'].'.'.$pathinfo['extension'].'.jpg'));
			if (file_exists($previewimage)) { $this->_preview = $previewimage; }
		}
	}
}
?>