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
			if (!$this->_initialized)
			{
				parent::Initialize();
				
				$GLOBALS['HTML']->AddJavascript(FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/hider.js');
				if (GALLERYUSELIGHTBOX)
				{
					$GLOBALS['HTML']->AddStyleSheet(FOLDERCOMMON.'/'.FOLDERSTYLES.'/lightbox.css');
					$GLOBALS['HTML']->AddJavascript(FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/jquery-1.10.2.min.js');
					$GLOBALS['HTML']->AddJavascript(FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/lightbox-2.6.min.js');
				}
			}
		}
		
		public function GetHtml($section=null, $args=null)
		{
			$return = false;
			if ($section == null)
			{
				if ($this->_html == null)
				{
					$this->GenerateHtml();
				}
				$return = $this->_html;
			}
			else
			{
				switch ($section)
				{
					case "rightpane":
						$return = $this->GetHtml_RightPane();
						break;
					default:
						$return = $this->GetHtml_Preview($section, $args);
						break;
				}
			}
			return $return;
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
		
		public function GetTitleFromUrl($url)
		{
			$result = false;
			$folder = new GalleryFolder($url, false);
			if ($folder->GetTitle() != EMPTYSTRING) { $result = $folder->GetTitle(); }
			return $result;
		}
		
		private function GenerateHtml()
		{
			$url = reiZ::GetSafeArgument(GETARGS);
			$folder = new GalleryFolder($url);
			
			if ($folder->Exists())
			{
				$this->_htmlextra['hidden'] = new HtmlElement('div', 'class="highlight"');
				$this->_html = new HtmlElement_Gallery_Folder($this, $folder);
			}
			// Else redirect to Gallery Root
			else { reiZ::Redirect(URLPAGE.reiZ::GetSafeArgument(GETPAGE)); }
			// Else redirect to previous gallery (loops until an existing gallery is found, or the max redirect is reached)
			//else { reiZ::Redirect(URLPAGE.reiZ::GetSafeArgument(GETPAGE).URLARGS.substr($url, 0, strrpos( $url, SINGLESLASH))); }
		}
		
		private function GetHtml_Preview($name, $args)
		{
			$value = false;
			$folder = GalleryFolder::Find($name);
			
			if ($folder != null)
			{
				if ($args != null)
				{
					$images = array();
					if (reiZ::SetAndNotNull($args, 0))
					{
						if (is_numeric($args[0]) && sizeof($folder->GetImages() >= $args[0])) { $images[] = $folder->GetImages()[$args[0] -1]; }
						elseif (is_string($args[0]))
						{
							if (reiZ::string_contains($args[0], SINGLECOMMA))
							{
								$indexes = explode(SINGLECOMMA, $args[0]);
								foreach ($indexes as $index) { $images[] = $folder->GetImages()[$index -1]; }
							}
							elseif (reiZ::string_contains($args[0], '-'))
							{
								$limits = explode('-', $args[0]);
								
								if (is_array($limits) && sizeof($limits == 2) && is_numeric($limits[0]) && is_numeric($limits[1]))
								{
									for ($i = $limits[0]; $i <= $limits[1]; $i++) { $images[] = $folder->GetImages()[$i -1]; }
								}
							}
						}
					}
					
					$value = new HtmlElement_Gallery_ImagePreview($this, $images);
				}
				else
				{
					$value = new HtmlElement_Gallery_FolderPreview($this, $folder);
				}
			}
			
			return $value;
		}
		
		public function GetHtml_RightPane()
		{
			return $this->GetHtml_HiddenInfo();
		}
		
		public function GetSettings()
		{
			$settings = new Settings($this->GetConfigFile());
			$settings->Add('GALLERYDIR',					'File directory',				ST::StringValue,		FOLDERFILES.'/gallery');
			$settings->Add('GALLERYNOPREVIEW',			'Default image',				ST::StringValue,		FOLDERCOMMON.'/images/reiz/nopreview.jpg');
			$settings->Add('GALLERYTHUMBNAILWIDTH',	'Thumbnail width',			ST::IntegerValue,		100);
			$settings->Add('GALLERYTHUMBNAILHEIGHT',	'Thumbnail height',			ST::IntegerValue,		75);
			$settings->Add('GALLERYUSELIGHTBOX',		'Use lightbox',				ST::BooleanValue,		true);
			$settings->Add('GALLERYDEFAULTTITLE',		'Default Title',				ST::StringValue,		'Gallery');
			$settings->Add('GALLERYDEFAULTTEXT',		'Default Text',				ST::StringValue,		'&nbsp;');
			$settings->Add('GALLERYPREVLINKTEXT',		'Text on "Back" button',	ST::StringValue,		'Back to previous folder');
			$settings->Load();
			return $settings;
		}
	}
	
	Module::Register(new GalleryModule());
	//$GLOBALS['MODULES'][] = new GalleryModule(false);
}
?>
