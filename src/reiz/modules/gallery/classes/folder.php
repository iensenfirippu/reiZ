<?php
/*
 * GalleryFolder class, for containing folder objects for the gallery module
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
if (defined('reiZ') or exit(1))
{
	class GalleryFolder extends Folder
	{
		protected $_BASE_LOCAL = GALLERYLOCALPATH;
		protected $_BASE_LINK = GALLERYLINKPATH;
		
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
				$contentfile = new URL(array($this->_path, 'gallery.info'));
				if (file_exists($directoryfile->GetAbsolutePath(GALLERYLOCALPATH)))
				{
					$infofile = file($directoryfile->GetAbsolutePath(GALLERYLOCALPATH));
				}
				elseif (file_exists($contentfile->GetAbsolutePath(GALLERYLOCALPATH)))
				{
					$thumbdsdir = (new URL($this->_path))->Replace(GALLERYLOCALPATH, GALLERYTHUMBSPATH);
					if (!is_dir($thumbdsdir)) { mkdir($thumbdsdir->GetAbsolutePath(GALLERYLOCALPATH)); }
					$infofile = file($contentfile);
				}
				
				if ($infofile != null && is_array($infofile) && sizeof($infofile > 2))
				{
					$this->_title = trim(preg_replace('/\s\s+/', ' ', $infofile[0]));
					$this->_text = trim(preg_replace('/\s\s+/', ' ', $infofile[1]));
				}
				else { $this->_title = $this->_name; }
				
				if ($this->_title == EMPTYSTRING ) { $this->_title = GALLERYDEFAULTTITLE; }
				if ($this->_text == EMPTYSTRING ) { $this->_text = GALLERYDEFAULTTEXT; }
				
				if ($loadcontents) { $this->LoadContents(); }
				elseif ($infofile != null) { $this->_hideid = MakeHideId(); }
			}
		}
		
		protected function LoadSubFolder($file)
		{
			$this->_subfolders[] = new GalleryFolder(new URL(array($this->_path, $file->GetLastFolder())), false);
		}
		
		protected function LoadFile($file)
		{
			$ext = strtolower($file->GetFileExtension());
			$mimetype = false;
			if (Array_Contains(array('info', 'txt'), $ext)) { }
			elseif ($ext == 'svg') { $mimetype = 'image/svg'; }
			else { $mimetype = getimagesize($file->GetRelativePath())["mime"]; }
			
			if ($mimetype != false)
			{
				$this->_files[] = new GalleryImage($file);
			}
		}
		
		public static function Find($name)
		{
			$base = GALLERYLOCALPATH.SINGLESLASH;
			$url = substr(parent::Recurse($base, $name), strlen($base));
			return new GalleryFolder($url, true);
		}
		
		public function GetRandomImage()
		{
			$image = null;
			
			if ($this->_exists) {
				$imagecount = sizeof($this->_files);
				$gallerycount = sizeof($this->_subfolders);
				
				if ($imagecount == 0 && $gallerycount == 0)
				{
					$this->LoadContents();
					$imagecount = sizeof($this->_files);
					$gallerycount = sizeof($this->_subfolders);
				}
				
				if ($imagecount > 0)
				{
					$image = null;
					$index = rand(0, $imagecount -1);
					if (isset($this->_files[$index])) { $image = $this->_files[$index]; }
				}
				elseif ($gallerycount > 0)
				{
					$order = array();
					for ($i = 0; $i < $gallerycount; $i++) { array_push($order, $i); }
					shuffle($order);
					
					for ($i = 0; $i < sizeof($order); $i++)
					{
						$image = $this->_subfolders[$order[$i]]->GetRandomImage();
						if ($image != null) { $i = sizeof($order); }
					}
				}
			}
			
			return $image;
		}
	}
}
?>
