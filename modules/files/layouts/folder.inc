<?php
class HtmlElement_FilesFolder extends HtmlElement
{
	public function __construct($module, $folder)
	{
		if (is_a($folder, "FilesFolder"))
		{
			parent::__construct('div', 'class="files"');
			
			// Show folder description if available
			$this->AddChild(new HtmlElement('h1', EMPTYSTRING, $folder->GetTitle()));
			$this->AddChild(new HtmlElement('span', EMPTYSTRING, $folder->GetText()));
			
			// Add a [one level up] link, but only if not in the root dir
			if ($folder->GetLink() != EMPTYSTRING)
			{
				$prev = pathinfo($folder->GetLink())['dirname'];
				$prev = $prev != SINGLEDOT ? $prev.'/' : EMPTYSTRING;
				$this->AddChild(
					new HtmlElement('div', 'class="fileslinkgroup"', EMPTYSTRING,
						new HtmlElement('div', 'class="fileslinkup"', EMPTYSTRING,
							new HtmlElement('a', 'href="'.URLROOT.INDEXFILE.URLPAGE.$module->GetName().URLARGS.$prev.'"', FILESPREVLINKTEXT)
						)
					)
				);
				//$this->AddChild(new HtmlElement('hr'));
			}
			
			// Show SubFolders
			if (sizeof($folder->GetSubfolders()) > 0)
			{
				$this->AddChild(new HtmlElement_FilesSubFolders($module, $folder->GetSubfolders()));
			}
			
			if (sizeof($folder->GetFiles()) > 0)
			{
				$this->AddChild(new HtmlElement_FilesFiles($module, $folder->GetFiles()));
			}
		}
		else { parent::__construct(); }
	}
}
?>
