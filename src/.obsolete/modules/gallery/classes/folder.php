<?php
/*
 * GalleryFolder class, for containing folder objects for the gallery module
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
class GalleryFolder
{
	protected $_hideid = '';
	protected $_name = '';
	protected $_url = '';
	protected $_link = '';
	protected $_title = '';
	protected $_text = '';
	protected $_subfolders = array();
	protected $_images = array();
	
	public function __construct($url, $firstlevel = true)
	{
		$pathinfo = pathinfo($url);
		$this->_url = GALLERYDIR.'/'.$url;
		$this->_link = $url;
		$this->_name = $pathinfo['filename'];
		
		$infofile = null;
		if (file_exists($this->_url.'/directory.info'))
		{
			$infofile = file($this->_url.'/directory.info');
		}
		elseif (file_exists($this->_url.'/gallery.info'))
		{
			if (!is_dir($this->_url.'/thumbs/')) { mkdir($this->_url.'/thumbs'); }
			$infofile = file($this->_url.'/gallery.info');
		}
		
		if ($infofile != null)
		{
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
					array_push($this->_subfolders, new GalleryFolder($this->_link.$precedingslash.$fileinfo['filename'].'/', false));
				}
				else if (strtolower($fileinfo['extension']) == 'jpg') // if file is a .jpg file...
				{
					array_push($this->_images, new GalleryImage($this->_link.'/'.$fileinfo['basename']));
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
	public function GetImages()		{ return $this->_images;		}
}
?>
