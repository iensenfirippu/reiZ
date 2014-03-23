<?php
define("GALLERYDIR", FOLDERFILES."/gallery");
define("THUMBNAILWIDTH", 100);
define("USELIGHTBOX", true);

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
		
		$this->_html = new HtmlElement('div', 'class="gallery"');
		$this->_htmlextra['hidden'] = new HtmlElement('div', 'class="highlight"');
		
		$folder = new GalleryFolder($url);
		// Show folder description if available
		if ($folder->GetTitle() != '')
		{
			$this->_html->AddChild(new HtmlElement('h3', '', $folder->GetTitle()));
		}
		else { $this->_html->AddChild(new HtmlElement('h3', '', 'Gallery')); }
		if ($folder->GetText() != '')
		{
			$this->_html->AddChild(new HtmlElement('span', '', $folder->GetText()));
		}
		else { $this->_html->AddChild(new HtmlElement('span', '', '&nbsp')); }
		
		// Add a [one level up] link, but only if not in the root dir
		if ($url != '')
		{
			//$link = '';
			//if (substr_count($url, '/') > 2) {
			$pathinfo = pathinfo($url);
			$link = $pathinfo['dirname'].'/';
			if ($link == './') { $link = ''; }
			//}
			$this->_html->AddChild(
				new HtmlElement('div', 'class="gallerylinkup"', '',
					new HtmlElement('a', 'href="'.URLROOT.INDEXFILE.URLPAGE.$this->_name.URLARGS.$link.'"', '..')
				)
			);
			$this->_html->AddChild(new HtmlElement('hr'));
		}
		
		// Show as directory
		if (sizeof($folder->GetImages()) == 0)
		{
			foreach ($folder->GetSubfolders() as $f)
			{
				$putextra = '';
				if ($f->GetHideId() != '')
				{
					$putextra = ' onmouseover="showdiv(\''.$f->GetHideId().'\')" onmouseout="hidediv(\''.$f->GetHideId().'\')"';
					
					$this->_htmlextra['hidden']->AddChild(
						new HtmlElement('div', 'id="'.$f->GetHideId().'" class="hidden"', '',
							array(
								new HtmlElement('h3', '', $f->GetTitle()),
								new HtmlElement('span', '', $f->GetText())
							)
						)
					);
				}
				//var_dump('URLROOT:'.URLROOT.'; INDEXFILE:'.INDEXFILE.'; URLPAGE:'.URLPAGE.'; NAME:'.$this->_name.'; URLARGS:'.URLARGS.'; LINK:'.$f->GetLink());
				$this->_html->AddChild(
					new HtmlElement('div', 'class="gallerylink"'.$putextra, '',
						new HtmlElement('a', 'href="'.URLROOT.INDEXFILE.URLPAGE.$this->_name.URLARGS.$f->GetLink().'"', $f->GetTitle())
					)
				);
			}
		}
		// Show as gallery
		else
		{
			foreach ($folder->GetImages() as $i)
			{
				$putextra = '"';
				if ($i->GetHideId() != '')
				{
					$putextra = ' hasinfo" onmouseover="showdiv(\''.$i->GetHideId().'\')" onmouseout="hidediv(\''.$i->GetHideId().'\')"';
					
					$this->_htmlextra['hidden']->AddChild(
						new HtmlElement('div', 'id="'.$i->GetHideId().'" class="hidden"', '',
							array(
								new HtmlElement('h3', '', $i->GetTitle()),
								new HtmlElement('span', '', $i->GetText())
							)
						)
					);
				}
				
				$lightbox = '';
				if (USELIGHTBOX) { $lightbox =  ' data-lightbox="gallery" title="'.$i->GetName().'"';}
				
				$this->_html->AddChild(
					new HtmlElement('div', 'class="galleryimage'.$putextra,'',
						new HtmlElement('a', 'href="'.URLROOT.'/'.$i->GetLink().'"'.$lightbox.'', '',
							array(
								new HtmlElement('img', 'src="'.URLROOT.'/'.$i->GetThumbnail().'" alt="'.$i->GetName().'"'),
								new HtmlElement('span', 'class="name"', $i->GetTitle())
							)
						)
					)
				);
			}
		}
	}
	
	public function GetHtml_RightPane()
	{
		return $this->GetHtml_HiddenInfo();
	}
}

$MODULE = new GalleryModule(false);
?>
