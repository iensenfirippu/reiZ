<?php
/*
 * GalleryFolder class, for containing folder objects for the gallery module
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
if (defined('reiZ') or exit(1))
{
	class GalleryFolder
	{
		protected $_hideid = EMPTYSTRING;
		protected $_name = EMPTYSTRING;
		protected $_url = EMPTYSTRING;
		protected $_link = EMPTYSTRING;
		protected $_title = EMPTYSTRING;
		protected $_text = EMPTYSTRING;
		protected $_subfolders = array();
		protected $_images = array();
		protected $_exists = false;
		
		public function GetHideId()		{ return $this->_hideid;		}
		public function GetName()		{ return $this->_name;			}
		public function GetUrl()		{ return $this->_url;			}
		public function GetLink()		{ return $this->_link;			}
		public function GetTitle()		{ return $this->_title;			}
		public function GetText()		{ return $this->_text;			}
		public function GetSubfolders()	{ return $this->_subfolders;	}
		public function GetImages()		{ return $this->_images;		}
		public function Exists()		{ return $this->_exists;		}
		
		public function HasInfo()		{ return ($this->_hideid != EMPTYSTRING);	}
		
		// TODO: Consider renaming $firstlevel to something more apporpriate, like: $load or $loadcontents
		public function __construct($url, $firstlevel = true)
		{
			$pathinfo = pathinfo($url);
			$this->_url = reiZ::url_append(GALLERYDIR, $url);
			$this->_link = $url;
			$this->_name = $pathinfo['filename'];
			
			if (file_exists($this->_url))
			{
				$this->_exists = true;
				
				$infofile = null;
				$directoryinfofile = reiZ::url_append($this->_url, 'directory.info');
				$galleryinfofile = reiZ::url_append($this->_url, 'gallery.info');
				if (file_exists($directoryinfofile))
				{
					$infofile = file($directoryinfofile);
				}
				elseif (file_exists($galleryinfofile))
				{
					$thumbdsdir = reiZ::url_append($this->_url, 'thumbs');
					if (!is_dir($thumbdsdir)) { mkdir($thumbdsdir); }
					$infofile = file($galleryinfofile);
				}
				
				if ($infofile != null && is_array($infofile) && sizeof($infofile > 2))
				{
					$this->_title = trim(preg_replace('/\s\s+/', ' ', $infofile[0]));
					$this->_text = trim(preg_replace('/\s\s+/', ' ', $infofile[1]));
				}
				else { $this->_title = $this->_name; }
				
				if ($this->_title == EMPTYSTRING ) { $this->_title = GALLERYDEFAULTTITLE; }
				if ($this->_text == EMPTYSTRING ) { $this->_text = GALLERYDEFAULTTEXT; }
				
				if ($firstlevel)
				{
					$this->LoadContents();
				}
				elseif ($infofile != null) { $this->_hideid = reiZ::MakeHideId(); }
			}
		}
		
		private function LoadContents()
		{
			//$precedingslash = EMPTYSTRING; if ($this->_url != EMPTYSTRING) { $precedingslash = '/'; }
			foreach (glob(reiZ::url_append($this->_url, '*')) as $file)
			{
				$fileinfo = pathinfo($file);
				
				if (is_dir($file)) // if file is directory...
				{
					if ($fileinfo['filename'] != 'thumbs') // ...and not the thumbs dir...
					{
						array_push($this->_subfolders, new GalleryFolder(reiZ::url_append($this->_link, $fileinfo['filename']), false));
					}
				}
				else
				{
					$mimetype = false;
					if (reiZ::string_is_one_of(array('info', 'txt'), strtolower($fileinfo['extension']))) { }
					elseif (strtolower($fileinfo['extension']) == 'svg') { $mimetype = 'image/svg'; }
					else { $mimetype = getimagesize($file)["mime"]; }
					
					if ($mimetype != false)
					{
						array_push($this->_images, new GalleryImage(reiZ::url_append($this->_link, $fileinfo['basename'])));
					}
				}
			}
		}
		
		/**
		 * Finds the folder path of the first occurance of name in the gallery folder
		 * @param name, the directory name to search for.
		 */
		public static function Find($name)
		{
			$base = GALLERYDIR.SINGLESLASH;
			$url = substr(GalleryFolder::Recurse($base, $name), strlen($base));
			return new GalleryFolder($url, true);
		}
		
		/**
		 * Traverses the gallery folder and returns the path of the first occurance of $name
		 * @param dir, the directory to recurse.
		 * @param name, the directory name to search for.
		 */
		private static function Recurse($dir, $name)
		{
			$value = null;
			
			$subdirs = glob($dir.'*/');
			
			for ($i = 0; $i < sizeof($subdirs); $i++)
			{
				$newdir = $subdirs[$i];
				$dirname = reiZ::url_lastfolder($newdir);
				
				if ($dirname == $name) { $value = $newdir; }
				else { $value = GalleryFolder::Recurse($newdir, $name); }
				
				if ($value != null) { $i = sizeof($subdirs); }
			}
			
			return $value;
		}
		
		public function GetRandomImage()
		{
			$image = null;
			$imagecount = sizeof($this->_images);
			$gallerycount = sizeof($this->_subfolders);
			
			if ($imagecount == 0 && $gallerycount == 0)
			{
				$this->LoadContents();
				$imagecount = sizeof($this->_images);
				$gallerycount = sizeof($this->_subfolders);
			}
			
			if ($imagecount > 0)
			{
				$image = null;
				$index = rand(0, $imagecount -1);
				if (isset($this->_images[$index])) { $image = $this->_images[$index]; }
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
			
			return $image;
		}
	}
}
?>
