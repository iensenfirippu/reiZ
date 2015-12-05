<?php
include_once('src/classes/functions.php');

class FunctionsTest extends PHPUnit_Framework_TestCase
{
    public function testConvertTextToHtml()
    {
		$text = 'This is a line of text.\n\tHere is another line, but it starts with a TAB!\n&qoute;This &lt;line&gt; has special characters in it&qoute;&trade;';
		
		$html = "This is a line of text.
	Here is another line, but it starts with a TAB!
\"This <line> has special characters in it\"™";
		
		$this->assertEquals(reiZ::ConvertTextToHtml($text), $html);
    }
    
    public function testConvertHtmlToText()
    {
		$text = 'This is a line of text.\n\tHere is another line, but it starts with a TAB!\n&qoute;This &lt;line&gt; has special characters in it&qoute;&trade;';
		
		$html = "This is a line of text.
	Here is another line, but it starts with a TAB!
\"This <line> has special characters in it\"™";
		
		$this->assertEquals($text, reiZ::ConvertHtmlToText($html));
    }
}
?>