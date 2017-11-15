<?php
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains the definition of a link (a/anchor) in HTML
	 **/
	class RTK_Link extends HtmlElement
	{
		/**
		 * A widget containing a clickable link (a)
		 * @param string $url The url of the link
		 * @param string $name The title of the list
		 * @param boolean $forcehttps Specify if the link has to have https
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 **/
		public function __construct($url=null, $name=null, $forcehttps=false, $args=null)
		{
			if ($url == null) { $url = EMPTYSTRING; }
			else { $url = array('href' => (new URL($url))->GetAbsolutePath(GetBaseURL($forcehttps))); }
			if ($name == null) { $name = EMPTYSTRING; }
			
			parent::__construct('a', $url, $name);
			$this->AddAttributes($args);
		}
	}
}
?>