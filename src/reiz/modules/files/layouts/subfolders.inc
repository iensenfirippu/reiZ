<?php
class HtmlElement_Files_SubFolders extends HtmlElement
{
	public function __construct($module, $folders)
	{
		if (is_array($folders))
		{
			parent::__construct('div', new HtmlAttributes(array('class' => 'fileslinkgroup')));
			
			foreach ($folders as $f)
			{
				$this->AddChild(new HtmlElement_Files_SubFolder($module, $f));
			}
			//$this->AddChild(new HtmlElement('hr'));
		}
		else { parent::__construct(); }
	}
}
?>
