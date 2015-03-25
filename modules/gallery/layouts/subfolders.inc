<?php
class HtmlElement_GallerySubFolders extends HtmlElement
{
	public function __construct($module, $folders)
	{
		if (is_array($folders))
		{
			parent::__construct('div', 'class="gallerysubfolders"');
			
			foreach ($folders as $f)
			{
				$this->AddChild(new HtmlElement_GallerySubFolder($module, $f));
			}
			//$this->AddChild(new HtmlElement('hr'));
		}
		else { parent::__construct(); }
	}
}
?>
