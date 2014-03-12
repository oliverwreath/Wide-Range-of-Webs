		
<?php include_once("header.php");
		include_once("head.php")?>
		
	</head>

	<body>
		<?php include_once("headbar_of_navigation.php") ?>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

		<?php include_once("./analytic/analyticstracking.php") ?>

		<img src="/pictures/new-year's-greetings.jpg"  alt="Happy New Year !!" style="width:100%" />
	</body>
</html>
		