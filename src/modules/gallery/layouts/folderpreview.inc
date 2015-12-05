<?php
class HtmlElement_Gallery_FolderPreview extends HtmlElement
{
	public function __construct($module, $folder)
	{
		if (is_a($folder, "GalleryFolder"))
		{
			parent::__construct('div', 'class="gallery"');
			
			// Show folder description if available
			//$this->AddChild(new HtmlElement('h1', EMPTYSTRING, $folder->GetTitle()));
			//$this->AddChild(new HtmlElement('span', EMPTYSTRING, $folder->GetText()));
			
			// Show SubFolders
			if (sizeof($folder->GetSubfolders()) > 0)
			{
				$this->AddChild(new HtmlElement_Gallery_SubFolders($module, $folder->GetSubfolders()));
			}
			
			if (sizeof($folder->GetImages()) > 0)
			{
				$this->AddChild(new HtmlElement_Gallery_Images($module, $folder->GetImages()));
			}
		}
		else { parent::__construct(); }
	}
}
?>
