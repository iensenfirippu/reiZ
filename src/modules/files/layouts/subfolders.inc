<?php
class HtmlElement_FilesSubFolders extends HtmlElement
{
	public function __construct($module, $folders)
	{
		if (is_array($folders))
		{
			parent::__construct('div', 'class="fileslinkgroup"');
			
			foreach ($folders as $f)
			{
				$this->AddChild(new HtmlElement_FilesSubFolder($module, $f));
			}
			//$this->AddChild(new HtmlElement('hr'));
		}
		else { parent::__construct(); }
	}
}
?>
