<?php
class HtmlElement_Gallery_SubFolders extends RTK_Box
{
	public function __construct($module, $folders)
	{
		if (is_array($folders))
		{
			parent::__construct(null, 'gallerysubfolders');
			
			foreach ($folders as $f)
			{
				$this->AddChild(new HtmlElement_Gallery_SubFolder($module, $f));
			}
			//$this->AddChild(new HtmlElement('hr'));
		}
		else { parent::__construct(); }
	}
}
?>
