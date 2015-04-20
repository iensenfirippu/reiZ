<?php
class HtmlElement_Gallery_Image extends HtmlElement
{
	public function __construct($module, $image)
	{
		if (is_a($image, "GalleryImage"))
		{
			$attr = 'class="galleryimage';
			$lightbox = GALLERYUSELIGHTBOX ? ' data-lightbox="gallery" title="'.$image->GetName().'"' : EMPTYSTRING;
			
			if ($image->HasInfo())
			{
				$attr .= ' hasinfo" onmouseover="showdiv(\''.$image->GetHideId().'\')"';
				
				$module->GetHtml_HiddenInfo()->AddChild(
					new HtmlElement('div', 'id="'.$image->GetHideId().'" class="hidden"', EMPTYSTRING,
						array(
							new HtmlElement('h3', EMPTYSTRING, $image->GetTitle()),
							new HtmlElement('span', EMPTYSTRING, $image->GetText())
						)
					)
				);
			}
			else { $attr .= '"'; }
			
			parent::__construct('div', $attr, EMPTYSTRING);
			
			$this->AddChild(
				new HtmlElement('a', 'href="'.URLROOT.'/'.$image->GetLink().'"'.$lightbox.EMPTYSTRING, EMPTYSTRING,
					array(
						new HtmlElement('img', 'src="'.URLROOT.'/'.$image->GetThumbnail().'" alt="'.$image->GetName().'"'),
						new HtmlElement('span', 'class="name"', $image->GetTitle())
					)
				)
			);
		}
		else { parent::__construct(); }
	}
}
?>
