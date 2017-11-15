<?php
class HtmlElement_Files_Folder extends RTK_Box
{
	public function __construct($module, $folder)
	{
		if (is_a($folder, "FilesFolder"))
		{
			parent::__construct(null, 'files');
			
			// Show folder description if available
			$this->AddChild(new RTK_Header($folder->GetTitle(), 1));
			$this->AddChild(new RTK_Textview($folder->GetText(), true));
			
			// Add a [one level up] link, but only if not in the root dir
			if ($folder->GetLinkPath() != null)
			{
				$this->AddChild(
					new RTK_Box(null, 'fileslinkup', null,
						new RTK_Link(new URL(array(FILESLINKPATH, $folder->GetPath()->GetSubPath(-1))), FILESPREVLINKTEXT)
					)
				);
			}
			
			// Show SubFolders
			if (sizeof($folder->GetSubfolders()) > 0)
			{
				$this->AddChild(new HtmlElement_Files_SubFolders($module, $folder->GetSubfolders()));
			}
			
			if (sizeof($folder->GetFiles()) > 0)
			{
				$this->AddChild(new HtmlElement_Files_Files($module, $folder->GetFiles()));
			}
		}
		else { parent::__construct(); }
	}
}
?>
