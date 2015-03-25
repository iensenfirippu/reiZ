<?php
class HtmlElement_GallerySubFolder extends HtmlElement
{
	public function __construct($module, $folder)
	{
		if (is_a($folder, "GalleryFolder"))
		{
			$attr = 'class="gallerylink"';
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
			
			$images = array();
			for ($i = $fail = 0; $fail < 4 && ($i - $fail) < 4; $i++)
			{
				$image = $folder->GetRandomImage();
				if ($image != null && !in_array($image, $images))
				{
					array_push($images, $image);
				}
				else
				{
					$fail++;
				}
			}
			
			$gallerypreview = new HtmlElement('div', 'class="gallerypreview"');
			foreach ($images as $image)
			{
				$gallerypreview->AddChild(new HtmlElement('img', 'class="gallerythumbnail" src="'.URLROOT.INDEXFILE.URLPAGE.$image->GetThumbnail().'"', EMPTYSTRING));				
			}
			
			parent::__construct('div', $attr);
			$this->AddChild(
				new HtmlElement('a', 'href="'.URLROOT.INDEXFILE.URLPAGE.$module->GetName().URLARGS.$folder->GetLink().'"', EMPTYSTRING,
					array(
						new HtmlElement('div', 'class="gallerylinktitle"', $folder->GetTitle()),
						$gallerypreview
					)
				)
			);
		}
		else { parent::__construct(); }
	}
}
?>
