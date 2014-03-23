<?php
/*
 * GalleryFolder class, for containing folder objects for the gallery module
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
if (defined('reiZ') or exit(1))
{
	class FilesFolder
	{
		protected $_hideid = '';
		protected $_name = '';
		protected $_url = '';
		protected $_link = '';
		protected $_title = '';
		protected $_text = '';
		protected $_subfolders = array();
		protected $_files = array();
		
		public function __construct($url, $firstlevel = true)
		{
			$this->_url = FILESDIR.'/'.$url;
			$this->_link = $url;
			$this->_name = pathinfo($url)['filename'];
			
			$infofile = null;
			if (file_exists($this->_url.'/directory.info'))
			{
				$infofile = file($this->_url.'/directory.info');
				$this->_title = trim(preg_replace('/\s\s+/', ' ', $infofile[0]));
				$this->_text = trim(preg_replace('/\s\s+/', ' ', $infofile[1]));
			}
			else { $this->_title = $this->_name; }
			
			if ($firstlevel)
			{
				$precedingslash = ''; if ($url != '') { $precedingslash = '/'; }
				foreach (glob($this->_url.$precedingslash.'*') as $file)
				{
					$fileinfo = pathinfo($file);
				
					if (is_dir($file)) // if file is directory...
					{
						array_push($this->_subfolders, new FilesFolder($this->_link.$precedingslash.$fileinfo['filename'].'/', false));
					}
					elseif ($fileinfo['extension'] == 'info') // if file is a .info file... (ignore)
					{
					}
					else // if file is file...
					{
						array_push($this->_files, new FilesFile($this->_link.'/'.$fileinfo['basename']));
					}
				}
			}
			elseif ($infofile != null) { $this->_hideid = reiZ::MakeHideId(); }
		}
		
		public function GetHideId()		{ return $this->_hideid;		}
		public function GetName()		{ return $this->_name;			}
		public function GetUrl()		{ return $this->_url;			}
		public function GetLink()		{ return $this->_link;			}
		public function GetTitle()		{ return $this->_title;			}
		public function GetText()		{ return $this->_text;			}
		public function GetSubfolders()	{ return $this->_subfolders;	}
		public function GetFiles()		{ return $this->_files;			}
	}
}
?>
