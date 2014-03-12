		
<?php include_once("header.php");
		include_once("head.php")?>
		
	</head>

	<body>
		<?php include_once("navigation.php") ?>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		
		<script>
		function changeLight()
		{
			light = document.getElementById("light");
			if(light.src.match("on"))
			{
				light.src = "/pictures/pic_bulboff.gif";
			}
			else
			{
				light.src = "/pictures/pic_bulbon.gif";
			}
		}
		</script>

		<?php include_once("./analytic/analyticstracking.php") ?>


		<div class="center">
			<table>
				<tr>
					<td>
						<br/>
						<h2>Yanliang H.</h2>
						<h3>Master of Computer Science in</h3>
						<h3>University of Illinois at Urbana-Champaign</h3>
						<h3>ACM and IEEE Member</h3>
						<hr/>
						<h3>Works Gallery - To be continued.</h3>
						<table style="width:100%">
							<tr style="width:100%">
								<td style="width:250px">
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
									<p id="colorText">Change colors as you wish!</p>
									<button type="button" onclick="changeTextRed()">Red</button>
									<button type="button" onclick="changeTextGreen()">Green</button>
									<button type="button" onclick="changeTextBlue()">Blue</button>
								</td>
							</tr>
							<tr style="width:100%">
								<td style="width:250px">
									<img id="light" src="/pictures/pic_bulbon.gif" onClick="changeLight()"></img>
								</td>
							</tr>
						</table>
						<h3>Research Interests: Compilers, Object-Oriented Programming, Machine Learning, Computer Vision, Data Mining, Algorithms, Information Security and bringing improvements to people's life with cutting-edge technologies.</h3> 
					</td>
				</tr>
				
				<?php include_once("bottom.php") ?>
				
			</table>
		</div>
	</body>
</html>