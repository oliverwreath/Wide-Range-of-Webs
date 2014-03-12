		
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
									<h3>Panorama Stitching</h3>
									<div>
										<a href="http://www.skywalkerhunter.com/pictures/Capture33.jpg"  target="_blank">
											<img style="width:100%" src="http://www.skywalkerhunter.com/pictures/Capture33.jpg" alt="Panorama Stitching! Yee, Haw!" />
										</a>
										<a href="http://www.skywalkerhunter.com/pictures/Capture32.jpg"  target="_blank">
											<img style="width:100%" src="http://www.skywalkerhunter.com/pictures/Capture32.jpg" alt="Panorama Stitching! Yee, Haw!" />
										</a>
									</div>
								</td>
								<td style="width:250px">
									<h3>Full featured Tutor Search</h3>
									<h3>With Live Demo!</h3>
									<div>
										<a href="http://www.skywalkerhunter.com/fourgrad/index.php"  target="_blank">
											<img style="width:100%" src="http://www.skywalkerhunter.com/pictures/ITS.png" alt="ITS" />
										</a>
										<a href="http://youtu.be/0LILG0sJjjM"  target="_blank">
											<img style="width:100%" src="http://www.skywalkerhunter.com/pictures/Video.png" alt="ITS" />
										</a>
									</div>
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