	<?php include_once("header.php")?>

		<script>
			var userLang = navigator.language || navigator.userLanguage || navigator.browserLanguage;
		</script>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&language=userLang"></script>
		<script>
			google.maps.visualRefresh = true;
		
			var map;

			function initialize() {
				var mapOptions = {
					zoom: 6
				};
				map = new google.maps.Map(document.getElementById('map-canvas'),
						mapOptions);

				//get current location coords
				if(navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(
					function(position) {
						var pos = new google.maps.LatLng(position.coords.latitude,
							position.coords.longitude);

						var marker = new google.maps.Marker({
							position: pos,
							map: map,
							title: 'You are here!'
						});
						map.setCenter(marker.position);
					}, function() {
						handleNoGeolocation(true);
					}
					);
				} else {
					// Browser doesn't support Geolocation
					handleNoGeolocation(false);
				}
			}

			function handleNoGeolocation(errorFlag) {
				if (errorFlag) {
					var title= 'Err: Allow geolocation for service!';
				} else {
					var title= 'Err: Browser geolocation support require, please upgrade!';
				}

				var marker = new google.maps.Marker({
					position: pos,
					map: map,
					title: title
				});	
				map.setCenter(marker.position);
			}

			google.maps.event.addDomListener(window, 'load', initialize);
		</script>
	</head>
	<body>

		<div class="container">
		
			<?php include_once("navi.php") ?>

			<div class="band">
				<h3>Web Lab</h3>
				<div class="responsive_wrapper">
					<div class="responsive_cell" style="width: 80%; min-width: 280px; max-width: 900px; height:300px">
						<div id="map-canvas"></div>
					</div>
					<div class="responsive_cell" style="max-width: 300px;">	
						<script type="text/javascript" src="http://jk.revolvermaps.com/2/1.js?i=a7yrrtif3ut&amp;s=220&amp;m=0&amp;v=true&amp;r=false&amp;b=000000&amp;n=false&amp;c=ff0000" async="async"></script>
					</div>
					<div class="responsive_cell" style="max-width: 300px;">
						<h4>JavaScript</h4>
						<script>
							function changeTextRed()
							{
								text = document.getElementById("colorText");
								text.style.color = "#FF0000";
							}
							function changeTextGreen()
							{
								text = document.getElementById("colorText");
								text.style.color = "#00FF00";
							}
							function changeTextBlue()
							{
								text = document.getElementById("colorText");
								text.style.color = "#0000FF";
							}
							</script>
							<p id="colorText" style="color:#FF0000">Change colors as you wish!</p>
							<button type="button" onclick="changeTextRed()">Red</button>
							<button type="button" onclick="changeTextGreen()">Green</button>
							<button type="button" onclick="changeTextBlue()">Blue</button>
					</div>
					<div class="responsive_cell" style="max-width: 300px;">
						<script>
						function changeLight()
						{
							light = document.getElementById("light");
							if(light.src.match("on"))
							{
								light.src = "/pics/pic_bulboff.gif";
							}
							else
							{
								light.src = "/pics/pic_bulbon.gif";
							}
						}
						</script>
						<img id="light" src="/pics/pic_bulbon.gif" alt="js switching light pic" onClick="changeLight()">
					</div>
				</div>
				<h4>Interests: Algorithms, Computer Vision, Data Mining, Compilers, Object-Oriented Programming and Information Security.</h4> 
			</div>

			<?php include_once("footer.php") ?>

		</div>

	</body>
</html>