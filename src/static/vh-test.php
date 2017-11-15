<html>
	<head>
		<title>TEST!</title>
		<style>
			body { margin:0; padding:0; }
			div { margin:0; padding:0; box-sizing:border-box; text-align:center; font-size:2vh; }
			
			body.landscape { margin:0; padding:0; background-color:#5555ff; }
			body.portrait { margin:0; padding:5vw 0; background-color:#ff5555; }
			
			body.landscape div#wrapper { background-color:#55ff55; width:100vh; /*min-width: 800px;*/ margin:0 auto; text-align:center; }
			body.portrait div#wrapper { background-color:#5555ff; width:90vw; margin:0 auto; text-align:center; }
			
			body.landscape div#wrapper div.row { width:100%; height:auto; text-align:center; }
			body.portrait div#wrapper div.row { width:100%; clear: both; }
			
			/*body div.row::after {
				content: ".";
				display: block;
				height: 0;
				clear: both;
				visibility: hidden;
			}*/
			
			body.landscape div#wrapper div.row div.L { display:inline-block; width:100%; height:25%; /*min-height:20vh;*/ }
			body.portrait div#wrapper div.row div.L { display:inline-block; width:100%; height:25%; /*min-height:10vh;*/ }
			
			body.landscape div#wrapper div.row div.M { display:inline-block; width:49.5%; height:25%; /*min-height:20vh;*/ }
			body.portrait div#wrapper div.row div.M { display:inline-block; width:100%; height:25%; }
			
			body.landscape div#wrapper div.row div.S { display:inline-block; width:32.75%; height:33.33333%; /*min-height:10vh;*/ }
			body.portrait div#wrapper div.row div.S { display:inline-block; width:49.5%; height:25%; }
			
			
			
			
/* Stolen gallery style */
.galleryimage { display:inline-block; position:relative; width:90%; height:90%;  text-align:center; border-radius:1vh; box-shadow:1vh 1vh 2vh 0 rgba(0,0,0,0.3); background-color:#eeeedd !important; }
.galleryimage img { display:block; width:80%; height:80%; margin-top:2vh; margin-left:auto; margin-right:auto; }
.galleryimage span { display:block; position:absolute; bottom:3px; left:5px; width:100px; margin:0px;  color:#000000; font-size:10px; font-weight:bold; text-align:center; text-decoration:none; text-transform:uppercase; }
		</style>
	</head>
	<body class="landscape">
		<div id="wrapper">
			<div class="row">
				<div class="L header">This is the site title!</div>
			</div>
			
			<div class="row">
				<div class="M">Medium div (1)</div>
				<div class="M">Medium div (2)</div>
				<div class="M">Medium div (3)</div>
			</div>
			
			<div class="row">
				<div class="S">
					<a class="galleryimage" href="http://localhost/content/gallery/Oxygen-Apps/Charm.png">
						<img alt="Charm" src="http://localhost/content/gallery-thumbs/Oxygen-Apps/Charm.png.jpg">
						<span class="name">&nbsp;</span>
					</a>
				</div>
				<div class="S">
					<a class="galleryimage" href="http://localhost/content/gallery/Oxygen-Apps/k3b.png">
						<img alt="k3b" src="http://localhost/content/gallery-thumbs/Oxygen-Apps/k3b.png.jpg">
						<span class="name">&nbsp;</span>
					</a>
				</div>
				<div class="S">
					<a class="galleryimage" href="http://localhost/content/gallery/Oxygen-Apps/kdevelop.png">
						<img alt="kdevelop" src="http://localhost/content/gallery-thumbs/Oxygen-Apps/kdevelop.png.jpg">
						<span class="name">&nbsp;</span>
					</a>
				</div>
				<div class="S">
					<a class="galleryimage" href="http://localhost/content/gallery/Oxygen-Apps/knetattach.png">
						<img alt="knetattach" src="http://localhost/content/gallery-thumbs/Oxygen-Apps/knetattach.png.jpg">
						<span class="name">&nbsp;</span>
					</a>
				</div>
				<div class="S">
					<a class="galleryimage" href="http://localhost/content/gallery/Oxygen-Apps/clock.png">
						<img alt="Clock" src="http://localhost/content/gallery-thumbs/Oxygen-Apps/clock.png.jpg">
						<span class="name">&nbsp;</span>
					</a>
				</div>
			</div>
			
			<div class="row">
				<div class="L footer"><?= date("d-m-y H:j \(\G\M\T\)"); ?></div>
			</div>
			
		</div>
		<script>
			function CheckScreenOrientation() { if (window.innerHeight > window.innerWidth) { document.body.className = "portrait"; } else { document.body.className = "landscape"; } }
			window.onload = function(event) { CheckScreenOrientation(); }
			window.onresize = function(event) { CheckScreenOrientation(); }
		</script>
	</body>
</html>
