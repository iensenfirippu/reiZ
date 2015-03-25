<?php
class HtmlElement_GalleryFolder extends HtmlElement
{
	public function __construct($module, $folder)
	{
		if (is_a($folder, "GalleryFolder"))
		{
			parent::__construct('div', 'class="gallery"');
			
			// Show folder description if available
			$this->AddChild(new HtmlElement('h1', EMPTYSTRING, $folder->GetTitle()));
			$this->AddChild(new HtmlElement('span', EMPTYSTRING, $folder->GetText()));
			
			// Add a [one level up] link, but only if not in the root dir
			if ($folder->GetLink() != EMPTYSTRING)
			{
				$prev = pathinfo($folder->GetLink())['dirname'];
				$prev = $prev != SINGLEDOT ? $prev.'/' : EMPTYSTRING;
				$this->AddChild(
					new HtmlElement('div', 'class="gallerylinkup"', EMPTYSTRING,
						new HtmlElement('a', 'href="'.URLROOT.INDEXFILE.URLPAGE.$module->GetName().URLARGS.$prev.'"', GALLERYPREVLINKTEXT)
					)
				);
				//$this->AddChild(new HtmlElement('hr'));
			}
			
			// Show SubFolders
			if (sizeof($folder->GetSubfolders()) > 0)
			{
				$this->AddChild(new HtmlElement_GallerySubFolders($module, $folder->GetSubfolders()));
			}
			
			if (sizeof($folder->GetImages()) > 0)
			{
				$this->AddChild(new HtmlElement_GalleryImages($module, $folder->GetImages()));
			}
		}
		else { parent::__construct(); }
	}
}
?>
