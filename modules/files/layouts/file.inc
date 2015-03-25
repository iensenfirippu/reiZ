<?php
class HtmlElement_FilesFile extends HtmlElement
{
	public function __construct($module, $file)
	{
		if (is_a($file, "FilesFile"))
		{
			$attr = 'class="filesfile';
			
			if (in_array($file->GetType(), array("jpg", "jpeg", "png", "gif"))) { $attr .= ' fileimage'; }
			elseif (in_array($file->GetType(), array("exe", "dmg", "cab", "jar", "msi", "apk"))) { $attr .= ' fileexecutable'; }
			elseif (in_array($file->GetType(), array("zip", "rar", "7z", "tar", "gz"))) { $attr .= ' filearchive'; }
			
			if ($file->HasInfo())
			{
				$preview = null;
				if ($file->HasPreview())
				{
					$preview = new HtmlElement('img', 'src="'.$file->GetPreview().'" alt="'.$file->GetName().'"');
				}
				
				$attr .= ' hasinfo" onmouseover="showdiv(\''.$file->GetHideId().'\')"';
				
				$module->GetHtml_HiddenInfo()->AddChild(
					new HtmlElement('div', 'id="'.$file->GetHideId().'" class="hidden"', EMPTYSTRING,
						array(
							new HtmlElement('h3', EMPTYSTRING, $file->GetTitle()),
							new HtmlElement('span', EMPTYSTRING, $file->GetText()),
							$preview
						)
					)
				);
			}
			else { $attr .= '"'; }
			
			parent::__construct('div', $attr, EMPTYSTRING);
			$this->AddChild(new HtmlElement('a', 'href="'.URLROOT.INDEXFILE.URLPAGE.$file->GetLink().'"', $file->GetName()));
		}
		else { parent::__construct(); }
	}
}
?>
