<?php
include_once('src/classes/functions.cls.inc');

class FunctionsTest extends PHPUnit_Framework_TestCase
{
    public function testThatAlwaysPasses()
    {
		$this->assertTrue(TRUE);
    }
    
    /*public function testConvertTextToHtml()
    {
		$text = 'This is a line of text.\n\tHere is another line, but it starts with a TAB!\n&qoute;This &lt;line&gt; has special characters in it&qoute;&trade;';
		
		$html = "This is a line of text.
	Here is another line, but it starts with a TAB!
\"This <line> has special characters in it\"™";
		
		echo $text;
		echo $html;
		
		$this->assertEquals(reiZ::ConvertTextToHtml($text), $html);
    }
    
    public function testConvertHtmlToText()
    {
		$text = 'This is a line of text.\n\tHere is another line, but it starts with a TAB!\n&qoute;This &lt;line&gt; has special characters in it&qoute;&trade;';
		
		$html = "This is a line of text.
	Here is another line, but it starts with a TAB!
\"This <line> has special characters in it\"™";
		
		$this->assertEquals($text, reiZ::ConvertHtmlToText($html));
    }*/
}
?>