<?php
check_running();

$user='ID3059'; // fill in your details
$password='RM9HnNg1SN'; // fill in your details

$hosts=array(
  'localhost', '127.0.0.1', $_SERVER['HTTP_HOST'], $_SERVER['SERVER_ADDR'], 'mysql.gografix.dk', '87.54.18.157', '87.54.18.158'
);
foreach ($hosts as $addr) {
   try_con($addr, $user, $password);
   try_con($addr . ':3306', $user, $password);
}

$link = mysql_connect($hosts[0], $user, $pass);
mysql_set_charset('utf-8', $link);
mysql_select_db($user);

function try_con($host, $user, $password)
{
 $dbh=mysql_connect($host, $user, $password);
 if ($dbh) {
     print "MySql connected OK with hostname=$host user=$user password=YES<br />\n";
 } else {
     print "Mysql failed to connect with hostname=$host user=$user password=YES<br />\n";
 }
}

function check_running()
{
   // this assumes that you are using Apache on a Unix/Linux box
   $chk=`ps -ef | grep httpd | grep -v grep`;
   if ($chk) {
      print "Checking for mysqld process: " . `ps -ef | grep mysqld | grep -v grep` . "<br />\n";
   } else {
       print "Cannot check mysqld process<br />\n";
   }
 }
?>
