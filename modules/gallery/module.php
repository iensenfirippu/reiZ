<?php
define("GALLERYDIR", FOLDERFILES."/gallery");
define("GALLERYNOPREVIEW", FOLDERCOMMON."/images/reiz/nopreview.jpg"); // must be jpg
define("THUMBNAILWIDTH", 100);
define("THUMBNAILHEIGHT", 75);
define("USELIGHTBOX", true);
define("GALLERYDEFAULTTITLE", "Gallery");
define("GALLERYDEFAULTTEXT", "&nbsp");
define("GALLERYPREVLINKTEXT", "Back to previous folder"); // ".."

if (defined('reiZ') or exit(1))
{
	class GalleryModule extends Module
	{
		public function __construct($initialize = true)
		{
			$name = 'gallery';
			$title = 'Gallery module';
			$author = 'Philip Jensen';
			$version = 0.1;
			$description = 'Image gallery viewer module, without any database calls.';
			parent::__construct($name, $title, $author, $version, $description);
			
			if ($initialize) { $this->Initialize(); }
		}
		
		public function Initialize()
		{
			foreach (glob(FOLDERMODULES.'/'.$this->_name.'/'.FOLDERCLASSES.'/*.php') as $classfile) { include_once($classfile); }
			foreach (glob(FOLDERMODULES.'/'.$this->_name.'/'.FOLDERLAYOUT.'/*.inc') as $classfile) { $this->LoadLayout($classfile); }
			if (USELIGHTBOX)
			{
				$this->_stylesheets = array(FOLDERMODULES.'/'.$this->_name.'/'.FOLDERSTYLES.'/style.css', FOLDERCOMMON.'/'.FOLDERSTYLES.'/lightbox.css');
				$this->_javascripts = array(FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/hider.js', FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/jquery-1.10.2.min.js', FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/lightbox-2.6.min.js');
			}
			else
			{
				$this->_stylesheets = array(FOLDERMODULES.'/'.$this->_name.'/'.FOLDERSTYLES.'/style.css');
				$this->_javascripts = array(FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/hider.js');
			}
		}
		
		public function GetHtml()
		{
			if ($this->_html == null)
			{
				$this->GenerateHtml();
			}
			return $this->_html;
		}
		
		public function GetHtml_HiddenInfo()
		{
			$return = false;
			if ($this->_htmlextra['hidden'] != null)
			{
				$return = $this->_htmlextra['hidden'];
			}
			else
			{
				$return = new HtmlElement("comment", "This block is generated with the main method, and therefore cannot run individually (and even so, only after the main method).");
			}
			return $return;
		}
		
		public function TranslateBreadcrumb($string)
		{
			
			return false;
		}
		
		private function GenerateHtml()
		{
			$url = reiZ::GetSafeArgument(GETARGS);
			$folder = new GalleryFolder($url);
			
			$this->_htmlextra['hidden'] = new HtmlElement('div', 'class="highlight"');
			$this->_html = new HtmlElement_GalleryFolder($this, $folder);
		}
		
		public function GetHtml_RightPane()
		{
			return $this->GetHtml_HiddenInfo();
		}
	}
	
	$MODULE = new GalleryModule(false);
}
?>
