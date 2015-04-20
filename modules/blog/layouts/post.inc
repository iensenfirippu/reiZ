<?php
class HtmlElement_Blog_Post extends HtmlElement
{
	public function __construct($module, $post, $full, $page=null)
	{
		if (is_a($post, "BlogPost"))
		{
			$baseurl = $module->GetFullUrl(); if ($page != null) { $baseurl .= $page.SINGLESLASH; }
			$timestamp = reiZ::TimestampToHumanTime($post->GetPosted());
			
			// Make top of post
			$posttop = new HtmlElement('div', 'class="top"', EMPTYSTRING,
				array(
					new HtmlElement('h3', 'class="title"', $post->GetTitle()),
					new HtmlElement('span', 'class="timestamp"', $timestamp)
				)
			);
			
			// Make middle of post
			$postcontent = new HtmlElement('div', 'class="center"');
			if ($full)
			{
				$tmp = new HtmlElement('div', 'class="text"', $post->GetFullText());
				//reiZ::vdd($tmp.EMPTYSTRING);
				$postcontent->AddChild($tmp);
			}
			else
			{
				$postcontent->AddChild(new HtmlElement('div', 'class="text"', $post->GetText()));
				if ($post->HasFullText())
				{
					$postcontent->AddChild(
						new HtmlElement('div', 'class="readmore"', EMPTYSTRING,
							new HtmlElement('a', 'href="'.$baseurl.$post->GetPostID().'/"', '...')
						)
					);
				}
			}
			
			// Make bottom of post
			$postbottom = new HtmlElement('div', 'class="bottom"');
			if (sizeof($post->GetTags()))
			{
				$postbottom->AddChild(new HtmlElement_Blog_PostTags($module, $post->GetTags()));
			}
			if ($post->GetEdited() > 0)
			{
				$postbottom->AddChild(
					new HtmlElement('span', 'class="timestamp"', '(edited: '.reiZ::TimestampToHumanTime($post->GetEdited()).')')
				);
			}
			
			parent::__construct('div', 'class="blogpost"', EMPTYSTRING,
				array(
					$posttop,
					$postcontent,
					$postbottom
				)
			);
		}
		else { parent::__construct(); }
	}
}
?>
