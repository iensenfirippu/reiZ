<?php
class HtmlElement_Files_File extends RTK_Box
{
	public function __construct($module, $file)
	{
		if (is_a($file, "FilesFile"))
		{
			$attr = new HtmlAttributes();
			$attr->AddClass('filesfile');
			
			$iconclass = 'fileicon ';
			if (in_array($file->GetType(), array("jpg", "jpeg", "png", "gif"))) { $iconclass .= 'typeimage'; }
			elseif (in_array($file->GetType(), array("exe", "dmg", "cab", "jar", "msi", "apk"))) { $iconclass = 'typeexecutable'; }
			elseif (in_array($file->GetType(), array("zip", "rar", "7z", "tar", "gz"))) { $iconclass .= 'typearchive'; }
			else { $iconclass .= 'typeunknown'; }
			
			if ($file->HasInfo())
			{
				$preview = null;
				if ($file->HasPreview())
				{
					$preview = new RTK_Image($file->GetPreview(), $file->GetName());
				}
				
				$attr->AddClass('hasinfo');
				$attr->Add('onmouseover', 'showdiv(\''.$file->GetHideId().'\')');
				$attr->Add('onfocus', 'showdiv(\''.$file->GetHideId().'\')');
				
				$module->GetHtml_HiddenInfo()->AddChild(
					new RTK_Box($file->GetHideId(), 'hidden', null,
						array(
							new RTK_Header($file->GetTitle(), 3),
							new RTK_Textview($file->GetText(), true),
							$preview
						)
					)
				);
			}
			
			parent::__construct(null, null, $attr);
			$link = new RTK_link($file->GetLinkPath());
			$link->AddChild(new RTK_Box(null, $iconclass));
			$link->AddChild(new RTK_Textview($file->GetName(), true));
			$this->AddChild($link);
		}
		else { parent::__construct(); }
	}
}
?>
