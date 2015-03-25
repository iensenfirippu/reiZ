<?php
/*
 * GalleryImage class, for containing image objects for the gallery module
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
class GalleryImage
{
	protected $_hideid = '';
	protected $_name = '';
	protected $_url = '';
	protected $_link = '';
	protected $_thumbnail = '';
	protected $_title = '';
	protected $_text = '';
	
	public function __construct($url)
	{
		$pathinfo = pathinfo($url);
		
		$this->_name = $pathinfo['basename'];
		$this->_url = $url;
		$this->_link = GALLERYDIR.'/'.$url;
		$this->_thumbnail = GALLERYDIR.'/'.$pathinfo['dirname'].'/thumbs/'.$pathinfo['filename'].'.'.$pathinfo['extension'];
		$infofile = GALLERYDIR.'/'.$pathinfo['dirname'].'/'.$pathinfo['filename'].'.info';
		if (file_exists($infofile))
		{
			$infofile = file($infofile);
			$this->_hideid = reiZ::MakeHideId();
			$this->_title = trim(preg_replace('/\s\s+/', ' ', $infofile[0]));
			$this->_text = trim(preg_replace('/\s\s+/', ' ', $infofile[1]));
		}
		else
		{
			$this->_title = '&nbsp;';
		}
		if (!file_exists($this->_thumbnail)) { $this->MakeThumbnail($pathinfo); }
	}
	
	public function GetHideId()		{ return $this->_hideid;	}
	public function GetName()		{ return $this->_name;		}
	public function GetUrl()		{ return $this->_url;		}
	public function GetLink()		{ return $this->_link;		}
	public function GetThumbnail()	{ return $this->_thumbnail;	}
	public function GetTitle()		{ return $this->_title;		}
	public function GetText()		{ return $this->_text;		}

	private function MakeThumbnail($pathinfo)
	{
		if (strtolower($pathinfo['extension']) == 'jpg' )
		{
			$img = imagecreatefromjpeg(GALLERYDIR.'/'.$this->_url);
			$width = imagesx($img);
			$height = imagesy($img);
			$new_width = THUMBNAILWIDTH;
			$new_height = floor($height * (THUMBNAILWIDTH / $width));
			$tmp_img = imagecreatetruecolor($new_width, $new_height);
			imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			imagejpeg($tmp_img, GALLERYDIR.'/'.$pathinfo['dirname'].'/thumbs/'.$pathinfo['filename'].'.'.$pathinfo['extension'], 85);
		}
	}
}
?>
