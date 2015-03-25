<?php
/*
 * GalleryFolder class, for containing folder objects for the gallery module
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
if (defined('reiZ') or exit(1))
{
	class FilesFolder
	{
		protected $_hideid = EMPTYSTRING;
		protected $_name = EMPTYSTRING;
		protected $_url = EMPTYSTRING;
		protected $_link = EMPTYSTRING;
		protected $_title = EMPTYSTRING;
		protected $_text = EMPTYSTRING;
		protected $_subfolders = array();
		protected $_files = array();
		
		public function GetHideId()		{ return $this->_hideid;		}
		public function GetName()		{ return $this->_name;			}
		public function GetUrl()		{ return $this->_url;			}
		public function GetLink()		{ return $this->_link;			}
		public function GetTitle()		{ return $this->_title;			}
		public function GetText()		{ return $this->_text;			}
		public function GetSubfolders()	{ return $this->_subfolders;	}
		public function GetFiles()		{ return $this->_files;			}
		
		public function HasInfo()		{ return ($this->_hideid != EMPTYSTRING);	}
		
		public function __construct($url, $firstlevel = true)
		{
			$pathinfo = pathinfo($url);
			$this->_url = reiZ::url_append(FILESDIR, $url);
			$this->_link = $url;
			$this->_name = $pathinfo['filename'];
			
			$infofile = null;
			$directoryinfofile = reiZ::url_append($this->_url, 'directory.info');
			if (file_exists($directoryinfofile))
			{
				$infofile = file($directoryinfofile);
				$this->_title = trim(preg_replace('/\s\s+/', ' ', $infofile[0]));
				$this->_text = trim(preg_replace('/\s\s+/', ' ', $infofile[1]));
			}
			else { $this->_title = $this->_name; }
			
			if ($this->_title == EMPTYSTRING ) { $this->_title = FILESDEFAULTTITLE; }
			if ($this->_text == EMPTYSTRING ) { $this->_text = FILESDEFAULTTEXT; }
			
			if ($firstlevel)
			{
				//$precedingslash = EMPTYSTRING; if ($this->_url != EMPTYSTRING) { $precedingslash = '/'; }
				foreach (glob(reiZ::url_append($this->_url, '*')) as $file)
				{
					$fileinfo = pathinfo($file);
					
					if (is_dir($file)) // if file is directory...
					{
						if ($fileinfo['filename'] != 'thumbs') // ...and not the thumbs dir...
						{
							array_push($this->_subfolders, new FilesFolder(reiZ::url_append($this->_link, $fileinfo['filename']), false));
						}
					}
					else // if file is file...
					{
						if ($fileinfo['extension'] != 'info') // ...and not a .info file...
						{
							array_push($this->_files, new FilesFile(reiZ::url_append($this->_link, $fileinfo['basename'])));
						}
					}
				}
			}
			elseif ($infofile != null) { $this->_hideid = reiZ::MakeHideId(); }
		}
	}
}
?>
