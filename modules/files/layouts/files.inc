<?php
class HtmlElement_FilesFiles extends HtmlElement
{
	public function __construct($module, $files)
	{
		if (is_array($files))
		{
			parent::__construct('div', 'class="fileslinkgroup"');
			
			foreach ($files as $f)
			{
				$this->AddChild(new HtmlElement_FilesFile($module, $f));
			}
		}
		else { parent::__construct(); }
	}
}
?>
