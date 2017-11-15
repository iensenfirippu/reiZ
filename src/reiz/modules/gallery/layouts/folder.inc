<?php
class HtmlElement_Gallery_Folder extends RTK_Box
{
	public function __construct($module, $folder)
	{
		if (is_a($folder, "GalleryFolder"))
		{
			parent::__construct(null, 'gallery');
			
			// Show folder description if available
			$this->AddChild(new RTK_Header($folder->GetTitle(), 1));
			$this->AddChild(new RTK_Textview($folder->GetText(), true));
			
			// Add a [one level up] link, but only if not in the root dir
			if ($folder->GetLinkPath() != null)
			{
				$this->AddChild(
					new RTK_Box(null, 'gallerylinkup', null,
						new RTK_Link(new URL(array(GALLERYLINKPATH, $folder->GetPath()->GetSubPath(-1))), GALLERYPREVLINKTEXT)
					)
				);
			}
			
			// Show SubFolders
			if (sizeof($folder->GetSubfolders()) > 0)
			{
				$this->AddChild(new HtmlElement_Gallery_SubFolders($module, $folder->GetSubfolders()));
			}
			
			if (sizeof($folder->GetFiles()) > 0)
			{
				$this->AddChild(new HtmlElement_Gallery_Images($module, $folder->GetFiles()));
			}
		}
		else { parent::__construct(); }
	}
}
?>
