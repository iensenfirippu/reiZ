<?php
class HtmlElement_Files_Files extends RTK_Box
{
	public function __construct($module, $files)
	{
		if (is_array($files))
		{
			parent::__construct(null, 'fileslinkgroup');
			
			foreach ($files as $f)
			{
				$this->AddChild(new HtmlElement_Files_File($module, $f));
			}
		}
		else { parent::__construct(); }
	}
}
?>
