<?php
class HtmlElement_FilesSubFolder extends HtmlElement
{
	public function __construct($module, $folder)
	{
		if (is_a($folder, "FilesFolder"))
		{
			$attr = 'class="fileslink"';
			if ($folder->HasInfo())
			{
				$attr .= ' onmouseover="showdiv(\''.$folder->GetHideId().'\')"';
				$module->GetHtml_HiddenInfo()->AddChild(
					new HtmlElement('div', 'id="'.$folder->GetHideId().'" class="hidden"', EMPTYSTRING,
						array(
							new HtmlElement('h3', EMPTYSTRING, $folder->GetTitle()),
							new HtmlElement('span', EMPTYSTRING, $folder->GetText())
						)
					)
				);
			}
			
			parent::__construct('div', $attr);
			$this->AddChild(
				new HtmlElement('a', 'href="'.URLROOT.INDEXFILE.URLPAGE.$module->GetName().URLARGS.$folder->GetLink().'"', $folder->GetTitle())
			);
		}
		else { parent::__construct(); }
	}
}
?>
