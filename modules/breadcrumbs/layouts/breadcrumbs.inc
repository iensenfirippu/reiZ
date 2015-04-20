<?php
class HtmlElement_Breadcrumbs extends HtmlElement
{
	public function __construct($module, $breadcrumbs)
	{
		if (is_array($breadcrumbs))
		{
			parent::__construct('ul', 'class="breadcrumbs"');
			
			$arraysize = sizeof($breadcrumbs);
			if ($arraysize > 0)
			{
				for ($i = 0; $i < $arraysize; $i++)
				{
					$flag = 0;
					if ($i == 0) { $flag = -1; }
					else
					{
						if ($i == $arraysize) { $flag = 1; }
						$this->AddChild(new HtmlElement_Separator($module));
					}
					
					$breadcrumb = $breadcrumbs[$i];
					$this->AddChild(new HtmlElement_Breadcrumb($module, $breadcrumb, $flag));
				}
			}
		}
		else { parent::__construct(); }
	}
}
?>
