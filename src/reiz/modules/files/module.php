<?php
if (defined('reiZ') or exit(1))
{
	class FilesModule extends Module
	{
		public function __construct($initialize = true)
		{
			$name = 'files';
			$title = 'Files';
			$author = 'Philip Jensen';
			$version = 0.1;
			$description = 'File viewer/downloader module, without any database calls.';
			parent::__construct($name, $title, $author, $version, $description);
			
			if ($initialize) { $this->Initialize(); }
		}
		
		public function Initialize()
		{
			if (!$this->_initialized)
			{
				parent::Initialize();
				$GLOBALS['HTML']->AddJavascript('common/scripts/hider.js');
			}
		}
		
		public function GetHtml($section=null, $args=null)
		{
			$return;
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
				}
				
			}
			return $return;
		}
		
		public function GetHtml_HiddenInfo()
		{
			$return = false;
			if (SetAndNotNull($this->_htmlextra, 'hidden'))
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
			$folder = new FilesFolder($url, false);
			if ($folder->GetTitle() != EMPTYSTRING) { $result = $folder->GetTitle(); }
			return $result;
		}
		
		private function GenerateHtml()
		{
			$url = GetSafeArgument(GETARGS);
			$folder = new FilesFolder($url);
			
			if ($folder->Exists())
			{
				$this->_htmlextra['hidden'] = new RTK_Box(null, 'highlight');
				$this->_html = new HtmlElement_Files_Folder($this, $folder);
			}
			// Else redirect to Gallery Root
			else { Redirect(URLPAGE.GetSafeArgument(GETPAGE)); }
		}
		
		/*public function GenerateHtml()
		{
			if ($this->_html == null)
			{
				$url = GetSafeArgument(GETARGS);
				
				$this->_html = new HtmlElement('div', 'class="files"');
				$this->_htmlextra['hidden'] = new HtmlElement('div', 'class="highlight"');
				
				$folder = new FilesFolder($url);
				
				// Show folder description if available
				if ($folder->GetTitle() != '')
				{
					$this->_html->AddChild(new HtmlElement('h3', '', $folder->GetTitle()));
				}
				else { $this->_html->AddChild(new HtmlElement('h3', '', 'Files')); }
				if ($folder->GetText() != '')
				{
					$this->_html->AddChild(new HtmlElement('span', '', $folder->GetText()));
				}
				else { $this->_html->AddChild(new HtmlElement('span', '', '&nbsp;')); }
				
				// Add a [one level up] link, but only if not in the root dir
				if ($url != '')
				{
					//$link = '';
					//if (substr_count($url, '/') > 2) { 
					$link = pathinfo($url)['dirname'].'/';
					if ($link == './') { $link = ''; }
					//}
					$this->_html->AddChild(
						new HtmlElement('div', 'class="fileslinkup"', '',
							new HtmlElement('a', 'href="'.URLROOT.INDEXFILE.URLPAGE.$this->_name.URLARGS.$link.'"', '..')
						)
					);
					$this->_html->AddChild(new HtmlElement('hr'));
				}
				
				// Show directories
				foreach ($folder->GetSubfolders() as $f)
				{
					$putextra = '';
					if ($f->GetHideId() != '')
					{
						$putextra = ' onmouseover="showdiv(\''.$f->GetHideId().'\')"';
						
						$this->_htmlextra['hidden']->AddChild(
							new HtmlElement('div', 'id="'.$f->GetHideId().'" class="hidden"', '',
								array(
									new HtmlElement('h3', '', $f->GetTitle()),
									new HtmlElement('span', '', $f->GetText())
								)
							)
						);
					}
					
					$this->_html->AddChild(
						new HtmlElement('div', 'class="fileslink"'.$putextra, '',
							new HtmlElement('a', 'href="'.URLROOT.INDEXFILE.URLPAGE.$this->_name.URLARGS.$f->GetLink().'"', $f->GetTitle())
						)
					);
				}
				
				$this->_html->AddChild(new HtmlElement('hr'));
				
				// Show files
				foreach ($folder->GetFiles() as $f)
				{
					$putextra = '"';
					if ($f->GetHideId() != '')
					{
						$putextra = ' hasinfo" onmouseover="showdiv(\''.$f->GetHideId().'\')"';
						
						$this->_htmlextra['hidden']->AddChild(
							new HtmlElement('div', 'id="'.$f->GetHideId().'" class="hidden"', '',
								array(
									new HtmlElement('h3', '', $f->GetTitle()),
									new HtmlElement('span', '', $f->GetText())
								)
							)
						);
					}
					
					$extensionclass = '';
					if (in_array($f->GetType(), array("jpg", "jpeg", "png", "gif"))) { $extensionclass = ' fileimage'; }
					elseif (in_array($f->GetType(), array("exe", "dmg", "cab", "jar", "msi", "apk"))) { $extensionclass = ' fileexecutable'; }
					elseif (in_array($f->GetType(), array("zip", "rar", "7z", "tar", "gz"))) { $extensionclass = ' filearchive'; }
					
					$this->_html->AddChild(
						new HtmlElement('div', 'class="filesfile'.$extensionclass.$putextra, '',
							new HtmlElement('a', 'href="'.URLROOT.INDEXFILE.URLPAGE.$f->GetLink().'"', $f->GetName())
						)
					);
				}
			}
			return $this->_html;
		}*/
		
		public function GetHtml_RightPane()
		{
			return $this->GetHtml_HiddenInfo();
		}
		
		public function GetSettings()
		{
			$settings = new Settings($this->GetConfigFile());
			$settings->Add('FILESDIR',				'File directory',				ST::StringValue,	FOLDERFILES.'/public');
			$settings->Add('FILESDEFAULTTITLE',	'Default Title',				ST::StringValue,	'Files');
			$settings->Add('FILESDEFAULTTEXT',	'Default Text',				ST::StringValue,	'&nbsp;');
			$settings->Add('FILESPREVLINKTEXT',	'Text on "Back" button',	ST::StringValue,	'Back to previous folder');
			$settings->Load();
			return $settings;
		}
	}
	
	Module::Register(new FilesModule());
	//$GLOBALS['MODULES'][] = new FilesModule(false);
}
?>
