<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$var = "%8c%be%97t%82%cc%91S%82%ad%82%ed%82%a9%82%e7%82%c8%82%a2%93y%92n%82%c9%8f%89%82%df%82%c4%94%f2%82%d1%8d%9e%82%dd%82%dc%82%b5%82%bd%81Binternational+ticket%82%e0%88%b5%82%c1%82%c4%82%e9%83J%83E%83%93%83%5e%81%5b%82%c5%89p%8c%ea%82%e0%83h%83C%83c%8c%ea%82%e0%92%ca%82%b6%82%c8%82%a2%82%cc%82%cd%83r%83b%83N%83%8a%82%b5%82%dc%82%b5%82%bd(%8f%ce)%0d%0a%94%ad%82%c2%92%bc%91O%82%cd%83I%81%5b%83o%81%5b%83%8f%81%5b%83N%82%c6%8e%a9%93%5d%8e%d4%82%cc%83p%83%93%83N%81E%83%60%83P%83b%83g%82%aa%94z%91%97%82%b3%82%ea%82%c8%82%a2%82%c8%82%c7%95s%89%5e%82%aa%8fd%82%c8%82%e8%81A%8fo%8a%7c%82%af%82%e9%92%bc%91O%82%c9%8eO%8e%9e%8a%d4%82%ad%82%e7%82%a2%8b%83%82%ab%82%c8%82%aa%82%e7%93d%98b%82%b5%82%c4%8f%80%94%f5%82%b5%82%c4%89%c6%82%f0%8fo%82%c4%82%ab%82%dc%82%b5%82%bd%81B%82%dc%82%be%83%60%83P%83b%83g%82%cc%96%e2%91%e8%82%aa%96%a2%89%f0%8c%88%82%be%82%c1%82%bd%82%e8%81A%82%e2%82%e7%82%c8%82%af%82%ea%82%ce%82%a2%82%af%82%c8%82%a2%82%b1%82%c6%82%aa%90i%82%dc%82%c8%82%a9%82%c1%82%bd%82%e8%81A%90F%81X%82%c6%96%e2%91%e8%82%cd%8eR%90%cf%82%dd%82%c5%82%b7%82%aa%81A%83%7c%81%5b%83%89%83%93%83h%82%cc%e3Y%97%ed%82%c8%97%f0%8ej%92n%8b%e6%82%e2%97%f0%8ej%93I%82%c8%83%82%83j%83%85%83%81%83%93%83g%82%f0%8c%a9%82%c4%82%a2%82%e9%82%a4%82%bf%82%c9%8f%ad%82%b5%90S%82%c9%97%5d%97T%82%aa%82%c5%82%ab%82%bd%82%e6%82%a4%82%c8%8bC%82%aa%82%b5%82%dc%82%b7%81B%0d%0a%82%c5%82%e0%96%d8%97j%93%fa%82%a9%82%e7%83R%83y%83%93%83n%81%5b%83Q%83%93%82%c5%82%e0%95%e0%82%ab%92%ca%82%b5%82%be%82%c1%82%bd%82%cc%82%c5%81A%91%ab%82%aa%82%bb%82%eb%82%bb%82%eb%83X%83y%83C%83%93%97%b7%8ds%8d%c5%8fI%93%fa%95%c0%82%dd%81c%81I";

//echo "<br />\n\n";

$cnv = mb_convert_encoding($var, "UTF-8", "Shift-JIS");

echo $cnv;
?>