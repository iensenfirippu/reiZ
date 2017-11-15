<?php
class HtmlElement_Files_SubFolder extends RTK_Box
{
	public function __construct($module, $folder)
	{
		if ($folder instanceof FilesFolder)
		{
			$attr = new HtmlAttributes(array('class' => 'fileslink'));
			if ($folder->HasInfo())
			{
				$attr['onmouseover'] = 'showdiv(\''.$folder->GetHideId().'\')';
				$attr['onfocus'] = 'showdiv(\''.$folder->GetHideId().'\')';
				
				$module->GetHtml_HiddenInfo()->AddChild(
					new RTK_Box($folder->GetHideId(), 'hidden', null,
						array(
							new RTK_Header($folder->GetTitle(), 3),
							new RTK_TextView($folder->GetText(), true)
						)
					)
				);
			}
			
			parent::__construct(null, null, $attr);
			$this->AddChild(
				new RTK_Link((new URL(array($module->GetName(), $folder->GetPath())))->GetAbsolutePath(), $folder->GetTitle())
			);
		}
		else { parent::__construct(); }
	}
}
?>
