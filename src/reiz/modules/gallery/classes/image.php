<?php
/*
 * GalleryImage class, for containing image objects for the gallery module
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
if (defined('reiZ') or exit(1))
{
	class GalleryImage extends FolderFile
	{
		protected $_hideid = EMPTYSTRING;
		protected $_thumbnail = EMPTYSTRING;
		
		public function GetHideId()		{ return $this->_hideid;	}
		public function GetThumbnail()	{ return $this->_thumbnail;	}
		public function HasInfo()		{ return ($this->_hideid != EMPTYSTRING);	}
		
		public function __construct($url)
		{
			parent::__construct($url);
			
			$this->_thumbnail = new URL(array($this->_path->GetDirectoryPath()->Replace(GALLERYLOCALPATH, GALLERYTHUMBSPATH), $this->_path->GetFilename().'.jpg'));
			$infofile = new URL(array($this->_path->GetDirectoryPath(), $this->_path->GetFilename().'.info'));
			
			if (file_exists($infofile))
			{
				$infofile = file($infofile);
				$this->_hideid = MakeHideId();
				$this->_title = trim(preg_replace('/\s\s+/', SINGLESPACE, $infofile[0]));
				$this->_text = trim(preg_replace('/\s\s+/', SINGLESPACE, $infofile[1]));
			}
			else { $this->_title = '&nbsp;'; }
			
			if (!file_exists($this->_thumbnail)) { $this->MakeThumbnail(); }
		}
		
		private function MakeThumbnail($mimetype=false)
		{
			if (extension_loaded('gd'))
			{
				if ($mimetype == false) {
					$mimetype = getimagesize($this->_path->GetRelativePath());
					$mimetype = (array_key_exists("mime", $mimetype)) ? $mimetype["mime"] : null;
				}
				
				$img = null;
				$fillbackground = false;
				if		($mimetype == 'image/jpeg')	{ $img = imagecreatefromjpeg($this->_path); }
				elseif	($mimetype == 'image/png')	{ $img = imagecreatefrompng($this->_path); $fillbackground = true; }
				elseif	($mimetype == 'image/gif')	{ $img = imagecreatefromgif($this->_path); $fillbackground = true; }
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
					imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $dims['newwidth'], $dims['newheight'], $dims['width'], $dims['height']);
					imagejpeg($tmp_img, $this->_thumbnail->ToString(), 90);
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
