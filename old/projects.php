		
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
						<h2>Technical Experience</h2>
						<table>
							<tr>
								<td>
									<h3>Selected Engineering Projects</h3>
									<ul>
										<li><a href= "../fourgrad/index.php"><h4>Illini Tutor Search</h4></a></li>
										<li><a href= "http://inextcube.cs.uiuc.edu/NetClus/"><h4>Data Mining Demo</h4></a></li>
									</ul>
								</td>
							</tr>
							<tr>
								<td>
								Details are in Github for your reference.
								</td>
							</tr>
							<tr>
								<td>
								To Be Cont.
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php include_once("bottom.php") ?>
			</table>
		</div>
	</body>
</html>
		