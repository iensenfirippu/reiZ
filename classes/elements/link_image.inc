<?php
class HtmlLink_Image extends HtmlLink
{
	public function __construct($link=EMPTYSTRING, $image=EMPTYSTRING, $title=EMPTYSTRING, $linkargs=null, $imageargs=null)
	{
		parent::__construct($link, EMPTYSTRING, $linkargs);
		$this->AddChild(new HtmlImage($image, $title, $imageargs));
		$this->SetOneLine(true);
	}
}
?>