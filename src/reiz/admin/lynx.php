<?php

// This should be a reasonably secure way to configure the CMS without SSH
if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' && $_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) {
?><html>
	<head>
		<title>Test</title>
	</head>
	<body style="text-align:center;">
		<div style="width:50%;margin:0 auto;">
			<div style="text-align:center;">
				Hello World!<br />
				Here is another line of text.<br />
			<div>
		<div>
	</body>
</html><?php
} else {
	echo "Go away!";
}

?>
