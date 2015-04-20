<?php
/*
 * GalleryImage class, for containing image objects for the gallery module
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
if (defined('reiZ') or exit(1))
{
	class GalleryImage
	{
		protected $_hideid = EMPTYSTRING;
		protected $_name = EMPTYSTRING;
		protected $_url = EMPTYSTRING;
		protected $_link = EMPTYSTRING;
		protected $_title = EMPTYSTRING;
		protected $_text = EMPTYSTRING;
		
		public function GetHideId()		{ return $this->_hideid;	}
		public function GetName()		{ return $this->_name;		}
		public function GetUrl()			{ return $this->_url;		}
		public function GetLink()		{ return $this->_link;		}
		public function GetThumbnail()	{ return $this->_thumbnail;	}
		public function GetTitle()		{ return $this->_title;		}
		public function GetText()		{ return $this->_text;		}
		
		public function HasInfo()		{ return ($this->_hideid != EMPTYSTRING);	}
		
		public function __construct($url)
		{
			$pathinfo = pathinfo($url);
			$mimetype = getimagesize(GALLERYDIR.SINGLESLASH.$url)["mime"];
			
			$this->_name = $pathinfo['basename'];
			$this->_url = $url;
			$this->_link = reiZ::url_append(GALLERYDIR, $url);
			//$this->_thumbnail = GALLERYDIR.'/'.$pathinfo['dirname'].'/thumbs/'.$pathinfo['filename'].'.'.$pathinfo['extension'].'.jpg';
			$this->_thumbnail = reiZ::url_append(GALLERYDIR, array($pathinfo['dirname'], 'thumbs', $pathinfo['filename'].'.'.$pathinfo['extension'].'.jpg'));
			$infofile = reiZ::url_append(GALLERYDIR, array($pathinfo['dirname'], $pathinfo['filename'].'.'.$pathinfo['extension'].'.info'));
			if (file_exists($infofile))
			{
				$infofile = file($infofile);
				$this->_hideid = reiZ::MakeHideId();
				$this->_title = trim(preg_replace('/\s\s+/', SINGLESPACE, $infofile[0]));
				$this->_text = trim(preg_replace('/\s\s+/', SINGLESPACE, $infofile[1]));
			}
			else
			{
				$this->_title = '&nbsp;';
			}
			if (!file_exists($this->_thumbnail)) { $this->MakeThumbnail($mimetype); }
		}
		
		private function old_MakeThumbnail($pathinfo)
		{
			if (extension_loaded('gd'))
			{
				if (strtolower($pathinfo['extension']) == 'jpg' )
				{
					$img = imagecreatefromjpeg(GALLERYDIR.'/'.$this->_url);
					$width = imagesx($img);
					$height = imagesy($img);
					$new_width = GALLERYTHUMBNAILWIDTH;
					$new_height = floor($height * (GALLERYTHUMBNAILWIDTH / $width));
					$tmp_img = imagecreatetruecolor($new_width, $new_height);
					imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
					imagejpeg($tmp_img, GALLERYDIR.'/'.$pathinfo['dirname'].'/thumbs/'.$pathinfo['filename'].'.'.$pathinfo['extension'], 85);
				}
			}
		}
		
		private function MakeThumbnail($mimetype)
		{
			if (extension_loaded('gd'))
			{
				$img = null;
				$fillbackground = false;
				if		($mimetype == 'image/jpeg')	{ $img = imagecreatefromjpeg(GALLERYDIR.'/'.$this->_url); }
				elseif	($mimetype == 'image/png')	{ $img = imagecreatefrompng(GALLERYDIR.'/'.$this->_url); $fillbackground = true; }
				elseif	($mimetype == 'image/gif')	{ $img = imagecreatefromgif(GALLERYDIR.'/'.$this->_url); $fillbackground = true; }
				else								{ $img = imagecreatefromjpeg(GALLERYNOPREVIEW); }
				
				if ($img != null)
				{
					$dims = $this->GetDimensions($img);
					$tmp_img = imagecreatetruecolor($dims['newwidth'], $dims['newheight']);
					if ($fillbackground)
					{
						$white = imagecolorallocate($tmp_img,  255, 255, 255);
						imagefilledrectangle($tmp_img, 0, 0, $dims['width'], $dims['height'], $white);
					}
					imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $dims['newwidth'], $dims['newheight'], $dims['width'], $dims['height']);
					imagejpeg($tmp_img, $this->_thumbnail, 85);
				}
			}
		}
		
		public function GetDimensions($img)
		{
			$value = array();
			$value['width'] = imagesx($img);
			$value['height'] = imagesy($img);
			
			if ($value['width'] > $value['height'])
			{
				$value['newwidth'] = GALLERYTHUMBNAILWIDTH;
				$value['newheight'] = intval(floor($value['height'] * (GALLERYTHUMBNAILWIDTH / $value['width'])));
			}
			else
			{
				$value['newwidth'] = intval(floor($value['width'] * (GALLERYTHUMBNAILHEIGHT / $value['height'])));
				$value['newheight'] = GALLERYTHUMBNAILHEIGHT;
			}
			
			if ($value['width'] < $value['newwidth'] && $value['height'] < $value['newheight'])
			{
				$value['newwidth'] = $value['width']; $value['newheight'] = $value['height'];
			}
			
			return $value;
		}
	}
}
?>
