<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		Library to do calculate and manipulate colors
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2017/05/19
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	class Color {
		protected $_red;
		protected $_green;
		protected $_blue;
		protected $_alpha;
		
		public function GetRed() { return $this->_red->GetInteger(); }
		public function GetGreen() { return $this->_green->GetInteger(); }
		public function GetBlue() { return $this->_blue->GetInteger(); }
		public function GetAlpha() { return $this->_alpha->GetFloat(); }
		public function GetLuma() { return ($this->_red->GetInteger() + $this->_green->GetInteger() + $this->_blue->GetInteger()); }
		
		/**
		 * description
		 * @param red, description.
		 * @param green, description.
		 * @param blue, description.
		 * @param alpha, description.
		 */
		public function __construct($red=null, $green=null, $blue=null, $alpha=null)
		{
			$this->_red = new ColorValue($red);
			$this->_green = new ColorValue($green);
			$this->_blue = new ColorValue($blue);
			$this->_alpha = new ColorValue($alpha);
		}
		
		/**
		 * Compares two colors
		 * @param a, The color to compare.
		 * @param b, The color to compare it with.
		 */
		public static function Compare($a, $b)
		{
			return Color::CompareLuma($a, $b);
		}
		
		/**
		 * Checks which of the two colors has the highest total color
		 * @param a, The color to compare.
		 * @param b, The color to compare it with.
		 */
		private static function CompareLuma($a, $b)
		{
			if ($a->GetLuma() < $b->GetLuma()) return -1;
			else if ($a == $b) return 0;
			else return 1;
		}
		
		/**
		 * Checks which of the two colors has the highest total color
		 * @param a, The color to compare.
		 * @param b, The color to compare it with.
		 */
		private static function CompareAlpha($a, $b)
		{
			if ($a->_alpha < $b->_alpha) return -1;
			else if ($a == $b) return 0;
			else return 1;
		}
		
		/**
		 * Checks if two colors have the same values
		 * @param a, The color to compare.
		 * @param b, The color to compare it with.
		 */
		public static function Equals($a, $b)
		{
			return ($a->_red->GetInteger() == $b->_red->GetInteger() &&
					$a->_green->GetInteger() == $b->_green->GetInteger() &&
					$a->_blue->GetInteger() == $b->_blue->GetInteger() &&
					$a->_alpha->GetInteger() == $b->_alpha->GetInteger());
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public function GetChannel($channel) {
			$value = null;
			if ($channel instanceof ColorChannel) {
				switch ($channel) {
					case (ColorChannel::Red):
						$value = $this->_red;
						break;
					case (ColorChannel::Green):
						$value = $this->_green;
						break;
					case (ColorChannel::Blue):
						$value = $this->_blue;
						break;
					case (ColorChannel::Alpha):
						$value = $this->_alpha;
						break;
				}
			}
			return $value;
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public function SetChannel($channel, $value) {
			if ($channel instanceof ColorChannel) {
				switch ($channel) {
					case (ColorChannel::Red):
						$this->_red = $value;
						break;
					case (ColorChannel::Green):
						$this->_green = $value;
						break;
					case (ColorChannel::Blue):
						$this->_blue = $value;
						break;
					case (ColorChannel::Alpha):
						$this->_alpha = $value;
						break;
				}
			}
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function Average($colors)
		{
			$red = $green = $blue = $alpha = $i = 0;
			
			foreach ($colors as $color) {
				if ($color instanceof Color) {
					$i++;
					$red += $color->GetRed();
					$green += $color->GetGreen();
					$blue += $color->GetBlue();
					$alpha += $color->GetAlpha();
				}
			}
			
			$red = intval($red/$i);
			$green = intval($green/$i);
			$blue = intval($blue/$i);
			$alpha = $alpha/$i;
			
			return new Color($red, $green, $blue, $alpha);
		}
		
		/* From functions */
		
		public static function FromString($colorstring)
		{
			$result = null;
			
			if (SetAndNotNull($colorstring)) {
				$matches = null;
				
				// RRGGBB or RRGGBBAA
				if (preg_match('/^\#?([0-9A-Fa-f]{2})([0-9A-Fa-f]{2})([0-9A-Fa-f]{2})([0-9A-Fa-f]{2})?$/', $colorstring, $matches)) {
					$result = new Color(hexdec($matches[1]),hexdec($matches[2]),hexdec($matches[3]),SetAndNotNull($matches,4)?CalculatePercentage(hexdec($matches[4]), 256, true):null);
				}
				
				// rgb() or rgba()
				elseif (preg_match('/^(?:rgb(?:a)?)\([\s]*([0-9]{1,3})[\s]*,[\s]*([0-9]{1,3})[\s]*,[\s]*([0-9]{1,3}) *,?(?:(0?\.?[0-9]*))?\)$/', $colorstring, $matches)) {
					$result = new Color($matches[1],$matches[2],$matches[3],SetAndNotNull($matches,4)?$matches[4]:null);
				}
				
				// RGB or RGBA
				elseif (preg_match('/^\#?([0-9A-Fa-f]{1})([0-9A-Fa-f]{1})([0-9A-Fa-f]{1})([0-9A-Fa-f]{1})?$/', $colorstring, $matches)) {
					$result = new Color(hexdec($matches[1].$matches[1]),hexdec($matches[2].$matches[2]),hexdec($matches[3].$matches[3]),SetAndNotNull($matches,4)?CalculatePercentage(hexdec($matches[4].$matches[4]), 256, true):null);
				}
			}
			
			return $result;
		}
		
		public static function Random() {
			return new Color(rand(0,255), rand(0,255), rand(0,255), rand(0,255));
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function FromImage($imageFile, $numColors)
		{
			$colors = array();
			
			if (file_exists($imageFile)) {
				$size = @getimagesize($imageFile);
				
				if ($size !== false) {
					$img = false;
					
					switch (exif_imagetype($imageFile)) {
						case 1: //gif image
							$img = imagecreatefromgif($imageFile);
							break;
						case 2: //jpeg or jpg image
							$img = imagecreatefromjpeg($imageFile);
							break;
						case 3: //png image
							$img = imagecreatefrompng($imageFile);
							break;
					}
					
					if ($img !== false) {
						for ($x = 0; $x < $size[0]; $x++) {
							for ($y = 0; $y < $size[1]; $y++) {
								$thisColor = imagecolorat($img, $x, $y);
								$rgb = imagecolorsforindex($img, $thisColor);
								$rgb['alpha'] = 1 - CalculatePercentage($rgb['alpha'], 127);
								$color = new Color($rgb['red'], $rgb['green'], $rgb['blue'], $rgb['alpha']);
								$colorstring = $color->ToRRGGBB();
								$simple = $color->ToRGB();
								
								if ($color->GetAlpha() == 1) {
									if (array_key_exists($simple, $colors)) {
										$colors[$simple]["Amount"]++;
										
										if (array_key_exists($colorstring, $colors[$simple]["Colors"])) {
											$colors[$simple]["Colors"][$colorstring]["Amount"]++;
										} else {
											$colors[$simple]["Colors"][$colorstring] = array("Color" => $color, "Amount" => 1);
										}
									} else {
										$colors[$simple] = array("Colors" => array(), "Amount" => 1);
										$colors[$simple]["Colors"][$colorstring] = array("Color" => $color, "Amount" => 1);
									}
								}
							}
						}
						
						uasort($colors, 'Color::SortColors');
						$colors = array_slice($colors, 0, $numColors);
						$tmp_colors = array();
						
						foreach($colors as $color) {
							if (SetAndNotEmpty($color, "Colors")) {
								$first = each($color["Colors"]);
								if (SetAndNotEmpty($first, "value")) { $first = $first["value"]; }
								if (SetAndNotEmpty($first, "Color")) { $tmp_colors[] = $first["Color"]; }
							}
						}
						
						$colors = $tmp_colors;
					}
				}
			}
			
			return $colors;
		}
		
		/* To functions */
		
		public function __toString() {
			$string = EMPTYSTRING;
			
			if ($this->_alpha->GetFloat() < 1) {
				$string = $this->ToDecimalRGBA();
			} else {
				$string = $this->ToRRGGBB();
			}
			
			return $string;
		}
		
		public function ToString($format=null) {
			$string = EMPTYSTRING;
			
			if ($format !== null) {
				switch ($format) {
					case ColorFormat::RRGGBB:
						$string = $this->ToRRGGBB();
						break;
					case ColorFormat::rgb:
						$string = $this->ToDecimalRGB();
						break;
					case ColorFormat::rgba:
						$string = $this->ToDecimalRGBA();
						break;
					case ColorFormat::RGB:
						$string = $this->ToRGB();
						break;
					default:
						$string = (string)$this;
						break;
				}
			} else {
				$string = (string)$this;
			}
			
			return $string;
		}
		
		public function ToRGB() {
			return sprintf('#%s%s%s', $this->_red->GetHexSingle(), $this->_green->GetHexSingle(), $this->_blue->GetHexSingle()); // Color::RoundDec2SingleHex($this->_red), Color::RoundDec2SingleHex($this->_green), Color::RoundDec2SingleHex($this->_blue));
		}
		
		public function ToRGBA() {
			return sprintf('#%s%s%s%s', $this->_red->GetHexSingle(), $this->_green->GetHexSingle(), $this->_blue->GetHexSingle(), $this->_alpha->GetHexSingle());
		}
		
		public function ToRRGGBB() {
			return sprintf('#%s%s%s', $this->_red->GetHex(), $this->_green->GetHex(), $this->_blue->GetHex());
		}
		
		public function ToRRGGBBAA() {
			return sprintf('#%s%s%s%s', $this->_red->GetHex(), $this->_green->GetHex(), $this->_blue->GetHex(), $this->_alpha->GetHex());
		}
		
		public function ToDecimalRGB() {
			return sprintf('rgba(%u,%u,%u)', $this->_red->GetInteger(), $this->_green->GetInteger(), $this->_blue->GetInteger());
		}
		
		public function ToDecimalRGBA() {
			return sprintf('rgba(%u,%u,%u,%u)', $this->_red->GetInteger(), $this->_green->GetInteger(), $this->_blue->GetInteger(), $this->_alpha->GetFloat());
		}
		
		public function Invert($keepalpha=true)
		{
			return new Color(255-$this->_red, 255-$this->_green, 255-$this->_blue, $keepalpha===true?$this->_alpha:1-$this->_alpha);
		}
		
		public function IsDark() {
			return ($this->GetLuma() <= 382.5);
		}
		
		public function IsLight() {
			return !$this->IsDark();
		}
		
		public function IsDarkerThan($color) {
			return ($color instanceof Color) && ($this->GetLuma() < $color->GetLuma());
		}
		
		public function IsLighterThan($color) {
			return !($this->IsDarkerThan($color));
		}
		
		private static function SortColors($a, $b)
		{
			if (SetAndNotNull($a, "Colors")) { uasort($a["Colors"], "Color::SortColors"); }
			return (SetAndNotNull($a, "Amount") && SetAndNotNull($b, "Amount")) ? $b["Amount"] - $a["Amount"] : 0;
		}
	}
	
	class ColorValue
	{
		protected $_int;
		protected $_hex;
		protected $_float;
		
		public function GetInteger() { return $this->_int; }
		public function GetFloat() { return $this->_float; }
		public function GetHex() { return $this->_hex; }
		public function GetHexSingle() { return substr($this->_hex, 0, 1); } // intval(round(15 * Color::CalculatePercentage(round($decimal), 255)))
		
		public function __construct($value) {
			$this->Set($value);
		}
		
		public function Set($value) {
			if (is_string($value)) {
				$this->_int = hexdec($value);
			} elseif (is_int($value)) {
				$this->_int = $value;
			} elseif (is_float($value)) {
				if ($value > 1) { $value = 1; }
				elseif ($value < 0) { $value = 0; }
				$this->_int = intval(255 * $value);
				$this->_float = $value;
			}
			
			if ($this->_int > 255) { $this->_int = 255; }
			elseif ($this->_int < 0) { $this->_int = 0; }
			
			if ($this->_hex == null) { $this->_hex = sprintf("%02X", $this->_int); }
			
			if ($this->_float == null) { $this->_float = ($this->_int > 0) ? (255 / $this->_int) : 0; } //(Color::CalculatePercentage($this->_int, 255) / 100); }
		}
	}
	
	abstract class ColorChannel
	{
		const Red = 1;
		const Green = 2;
		const Blue = 3;
		const Alpha = 4;
	}
	
	abstract class ColorFormat
	{
		const RRGGBB = 1;
		const rgba = 2;
		const RGB = 3;
		const rgb = 4;
		const RGBA = 5;
		const RRGGBBAA = 6;
	}
		
	/* "Eventually move to math"(tm) functions */
	
	function CalculatePercentage($part, $full, $round=false) {
		$value = ($part / ($full / 100));
		if ($round === true) { $value = round($value); }
		return ($value / 100);
	}
	
	//function RoundDec2SingleHex($decimal) {
	//	return intval(round(15 * CalculatePercentage(round($decimal), 255)));
	//}
	
	/**
	 * description
	 * @param param, description.
	 * @param $returnonerror, Value to return color string can't 
	 */
	function ColorInvert($color)
	{
		$result = null;
		if (SetAndNotNull($color)) {
			if (is_string($color)) { $color = Color::FromString($color); }
			if ($color instanceof Color) { $result = $color->Invert(); }
		}
		return $result;
	}
	
	/**
	 * description
	 * @param param, description.
	 * @param $returnonerror, Value to return color string can't 
	 */
	function ColorIsDark($color)
	{
		$result = null;
		if (SetAndNotNull($color)) {
			if (is_string($color)) { $color = Color::FromString($color); }
			if ($color instanceof Color) { $result = $color->IsDark(); }
		}
		return $result;
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function ColorIsLight($color)
	{
		return !ColorIsDark($color);
	}
	
	///**
	// * Tests the IsDark functionality
	// */
	//function Test() {
	//	echo "<pre>";
	//	var_dump(ColorIsDark("123"));           // true
	//	var_dump(ColorIsDark("#556633"));       // true
	//	var_dump(ColorIsLight("00aBee"));	    // true
	//	var_dump(ColorIsDark("#42f"));          // true
	//	var_dump(ColorIsDark("7f7f7f"));        // true
	//	var_dump(ColorIsDark("nej"));           // null
	//	var_dump(ColorIsDark("#808080"));       // false
	//	var_dump(ColorIsLight("0B00B5"));       // false
	//	var_dump(ColorIsDark("DEAD"));          // false
	//	var_dump(ColorIsDark("#aaaAAa"));       // false
	//	die(var_dump(ColorIsLight("#000000"))); // false
	//}
	
	/**
	 * Tests the Random functionality
	 * @param param, description.
	 */
	//function Test() {
	//	$palette = array();
	//	for ($i=0;$i<10;$i++) { $palette[] = Color::Random(); }
	//	$bgcolor = Color::Average($palette);
	//	
	//	echo "<html>";
	//	echo "<head><title>test</title></head>";
	//	echo "<body style=\"background-color:".$bgcolor.";text-align:center;\">";
	//	echo "<table>";
	//	if (sizeof($palette) > 0) {
	//		foreach($palette as $color)
	//		{
	//			echo "<tr><td style='background-color:".$color->ToRRGGBB().";width:2em;'>&nbsp;</td><td>".$color->ToRRGGBBAA()."</td></tr>";
	//		}
	//	} else {
	//		echo "<tr><td>no image... no colors...</td></tr>\n";
	//	}
	//	echo "</table>";
	//	echo "</body>";
	//	echo "</html>";
	//	die();
	//}
	
	/**
	 * Tests the "colors from image" functionality
	 * @param param, description.
	 */
	//function Test() {
	//	$image = "*";
	//	for ($i=0;$i<10;$i++) {
	//		$files = glob('content/gallery/'.$image.'.png');
	//		if (sizeof($files) > 0) {
	//			$image = $files[rand(0,sizeof($files)-1)];
	//			$i=10;
	//		} else {
	//			$image .= "/*";
	//		}
	//	}
	//	
	//	$palette = Color::FromImage($image, 10);
	//	$bgcolor = Color::Average($palette);
	//	$bgcolorstring = $bgcolor->ToRRGGBB() ?? "#ffffff";
	//	
	//	echo "<html>";
	//	echo "<head><title>test</title></head>";
	//	echo "<body style=\"background-color:".$bgcolorstring.";background-image:url('/".$image."');background-position:center center;background-repeat:no-repeat;text-align:center;padding-top:1vh;\">";
	//	if (sizeof($palette) > 0) {
	//		echo "<div style=\"margin-left:auto;margin-right:auto;\">";
	//		$i = 0;
	//		$fib = 1;
	//		foreach($palette as $color)
	//		{
	//			$i++;
	//			$fib += $fib;
	//			if ($color instanceof Color) {
	//				$rgb = $color->ToRRGGBB();
	//				$text = $color->ToRRGGBB();
	//				$shadow = $color->IsDarkerThan($bgcolor) ? "#ffffff" : "#000000";
	//				
	//				echo "<div style=\"font-family:monospace;font-size:".((20+($i*10))/($fib))."vw;color:".$rgb.";text-shadow:0px 0px 4px ".$shadow.";\">".$text."</div>";
	//			} else {
	//				echo "<div>OMG! couldn't color (".var_dump($color).")</div>";
	//			}
	//		}
	//		echo "</div>";
	//	} else {
	//		echo "<div>no image, or image is gray-scale... T, T</div>";
	//	}
	//	echo "</body>";
	//	echo "</html>";
	//	die();
	//}
}

//Test();
?>