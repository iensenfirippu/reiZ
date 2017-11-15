<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		Extention of basic PHP functionality
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/10
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	class URL
	{
		private $_string = null;
		private $_items = array();
		
		public function __construct($url)
		{
			$this->Append($url);
		}
		
		public function __tostring()
		{
			$this->_string = implode(SINGLESLASH, $this->_items);
			return $this->_string;
		}
		
		public function ToString()
		{
			return $this.EMPTYSTRING;
		}
		
		public function GetRelativePath()
		{
			if ($this->_string == null) { $this.EMPTYSTRING; }
			return $this->_string;
		}
		
		public function GetAbsolutePath($baseurl=SINGLESLASH)
		{
			if ($this->_string == null) { $this.""; }
			
			$string = $baseurl;
			if (!String_EndsWith($baseurl, SINGLESLASH)) { $string .= SINGLESLASH; }
			$string .= $this->_string;
			if (!(String_Contains($this->GetLastItem(), SINGLEDOT) || String_EndsWith($string, SINGLESLASH))) { $string .= SINGLESLASH; }
			
			return $string;
		}
		
		public function GetLastItem()
		{
			return end($this->_items);
		}
		
		public function GetBasename()
		{
			$lastitem = $this->GetLastItem();
			return (String_Contains($lastitem, SINGLEDOT)) ? substr($lastitem, 0, strrpos($lastitem, SINGLEDOT)) : EMPTYSTRING;
		}
		
		public function GetFilename()
		{
			$lastitem = $this->GetLastItem();
			return (String_Contains($lastitem, SINGLEDOT)) ? $lastitem : EMPTYSTRING;
		}
		
		public function GetFileExtension()
		{
			$lastitem = $this->GetLastItem();
			return (String_Contains($lastitem, SINGLEDOT)) ? substr($lastitem, strrpos($lastitem, SINGLEDOT)) : EMPTYSTRING;
		}
		
		public function GetDirectoryPath()
		{
			return String_Contains($this->GetLastItem(), SINGLEDOT) ? $this->GetSubPath(-1) : $this;
		}
		
		public function GetSubPath($to=-1, $from=0)
		{
			$size = sizeof($this->_items);
			
			if (!is_numeric($to)) { $to = -1; }
			if ($to < 0) { $to = $size + $to; }
			if ($to > $size) { $to = $size; }
			
			if (!is_numeric($from)) { $from = 0; }
			if ($from < 0) { $from = 0; }
			if ($from > $to) { $from = $to; }
			
			return new URL(array_slice($this->_items, $from, $to));
		}
		
		public function Replace($search, $replace)
		{
			return new URL(str_replace($search, $replace, $this));
		}
		
		public function ReplaceAt($index, $replace)
		{
			return new URL(array_replace($this->_items, array($index => $replace)));
		}
		
		public function GetLastFolder()
		{
			$last = sizeof($this->_items) -1; 
			$foldername = $this->_items[$last];
			
			if ($foldername == EMPTYSTRING || String_Contains($foldername, SINGLEDOT))
			{
				$foldername = $this->_items[$last -1];
			}
			
			return $foldername;
		}
		
		public function Append($append)
		{
			if (SetAndNotNull($append)) {
				$this->_string = null;
				if ($append instanceof URL) { $append = $append->_items; }
				elseif (String_Contains($append, SINGLESLASH)) { $append = explode(SINGLESLASH, $append); }
				
				if (is_array($append)) {
					foreach ($append as $item) {
						$this->Append($item, false);
					}
				} else {
					$this->_items[] = $append;
				}
			}
		}
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function url_append($url, $append)
	{
		if (!is_a($url, 'URL')) { $url = new URL($url); }
		$url->Append($append);
		
		return $url;
	}
	/**
	 * description
	 * @param param, description.
	 */
	/*function url_append($url, $append)
	{
		$value = $url;
		
		if (is_array($append))
		{
			$array = $append;
			$append = EMPTYSTRING;
			foreach ($array as $item)
			{
				$append = url_append($append, $item);
			}
		}
		
		if (is_string($append))
		{
			if ($url != EMPTYSTRING && $append != EMPTYSTRING)
			{
				$trailing = String_Endswith($url, SINGLESLASH);
				$preceding = String_Startswith($url, SINGLESLASH);
				
				if ($trailing && $preceding) {
					$url = substr($url, 0, -1);
					$append = substr($append, 1);
				} elseif (!$trailing && !$preceding) {
					$value .= SINGLESLASH;
				}
				
				if (!String_Contains($append, SINGLEDOT) && !String_Endswith($append, SINGLESLASH)) {
					vd($url);vd($append);echo "<br />\n<br />\n";
					$append .= SINGLESLASH;
				}
			}
			$value .= $append;
		}
		
		return $value;
	}*/
	
	/**
	 * description
	 * @param param, description.
	 */
	function url_lastfolder($url)
	{
		$pieces = explode(SINGLESLASH, $url);
		$last = sizeof($pieces) -1; 
		$foldername = $pieces[$last];
		
		if ($foldername == EMPTYSTRING || String_Contains($foldername, SINGLEDOT))
		{
			$foldername = $pieces[$last -1];
		}
		
		return $foldername;
	}
}
?>
