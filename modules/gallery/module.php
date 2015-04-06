<?php
if (defined('reiZ') or exit(1))
{
	class GalleryModule extends Module
	{
		public function __construct($initialize = true)
		{
			$name = 'gallery';
			$title = 'Gallery';
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
			if (reiZ::SetAndNotNull($this->_htmlextra, 'hidden'))
			{
				$return = $this->_htmlextra['hidden'];
			}
			else
			{
				$return = new HtmlElement("comment", "This block is generated with the main method, and therefore cannot run individually (and even so, only after the main method).");
			}
			return $return;
		}
		
		/*public function GetTitleFromUrl($url)
		{
			$result = false;
			$folder = new GalleryFolder($url, false);
			if ($folder->GetTitle() != EMPTYSTRING) { $result = $folder->GetTitle(); }
			return $result;
		}*/
		
		private function GenerateHtml()
		{
			$url = reiZ::GetSafeArgument(GETARGS);
			$folder = new GalleryFolder($url);
			
			if ($folder->Exists())
			{
				$this->_htmlextra['hidden'] = new HtmlElement('div', 'class="highlight"');
				$this->_html = new HtmlElement_GalleryFolder($this, $folder);
			}
			// Else redirect to Gallery Root
			else { reiZ::Redirect(URLPAGE.reiZ::GetSafeArgument(GETPAGE)); }
			// Else redirect to previous gallery (loops until an existing gallery is found, or the max redirect is reached)
			//else { reiZ::Redirect(URLPAGE.reiZ::GetSafeArgument(GETPAGE).URLARGS.substr($url, 0, strrpos( $url, SINGLESLASH))); }
		}
		
		public function GetHtml_RightPane()
		{
			return $this->GetHtml_HiddenInfo();
		}
		
		public function GetSettings()
		{
			$settings = new Settings();
			$settings->Add('GALLERYDIR',				'File directory',			ST::String,	FOLDERFILES.'/gallery');
			$settings->Add('GALLERYNOPREVIEW',			'Default image',			ST::String,	FOLDERCOMMON."/images/reiz/nopreview.jpg");
			$settings->Add('GALLERYTHUMBNAILWIDTH',		'Thumbnail width',			ST::Integer,	100);
			$settings->Add('GALLERYTHUMBNAILHEIGHT',	'Thumbnail height',			ST::Integer,	75);
			$settings->Add('GALLERYUSELIGHTBOX',		'Use lightbox',				ST::Bool,	true);
			$settings->Add('GALLERYDEFAULTTITLE',		'Default Title',			ST::String,	'Gallery');
			$settings->Add('GALLERYDEFAULTTEXT',		'Default Text',				ST::String,	'&nbsp;');
			$settings->Add('GALLERYPREVLINKTEXT',		'Text on "Back" button',	ST::String,	'Back to previous folder');
			
			return $settings;
		}
	}
	
	$GLOBALS['MODULES'][] = new GalleryModule(false);
}
?>
