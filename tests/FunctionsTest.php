<?php
define("reiZ", true);
include_once('src/config/default.cfg.inc');
include_once('src/classes/defines.inc');
include_once('src/classes/constants.inc');
include_once('src/classes/functions.cls.inc');

class FunctionsTest extends PHPUnit_Framework_TestCase
{
    public function testThatAlwaysPasses()
    {
		$this->assertTrue(TRUE);
    }
    
    public function testConvertTextToHtml()
    {
		$text = 'This is a line of text.\n\tHere is another line, but it starts with a TAB!\n&quot;This &lt;line&gt; has html characters in it&quot;';
		
		$html = "This is a line of text.
	Here is another line, but it starts with a TAB!
\"This <line> has html characters in it\"";
		
		echo $text;
		echo $html;
		
		$this->assertEquals(reiZ::ConvertTextToHtml($text), $html);
    }
    
    public function testConvertHtmlToText()
    {
		$text = 'This is a line of text.\n\tHere is another line, but it starts with a TAB!\n&quot;This &lt;line&gt; has html characters in it&quot;';
		
		$html = "This is a line of text.
	Here is another line, but it starts with a TAB!
\"This <line> has html characters in it\"";
		
		$this->assertEquals($text, reiZ::ConvertHtmlToText($html));
    }
	

//$thisstring = "This string starts with \"This\", and ends with \"True story!\". True story!";
//echo "This string starts with \"This\"?";
//vd(String_StartsWith($thisstring, "This"));
//echo "This string starts with \"That\"?";
//vd(String_StartsWith($thisstring, "That"));
//echo "This string ends with \"True story!\"?";
//vd(String_EndsWith($thisstring, "True story!"));
//echo "This string ends with \"Just kidding!\"?";
//vdd(String_EndsWith($thisstring, "Just kidding!"));
}
?>