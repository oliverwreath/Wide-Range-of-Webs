		
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
						<h2>Academic Experience</h2>
						<table>
							<tr>
								<td>
									<h3>Selected Academic Projects</h3>
									<ul>
										<li>Object Oriented Tic-tac-toe (2013). Smalltalk-based Ultimate Tic-tac-toe Implementation with Seaside framework. With a series of AI support. </li>
										<li>Panorama Stitching, 3D Construction and Affine Factorization (2013). Feature detection with RANSAC and Homography Transform. </li>
										<li>Illini Tutor Search (2012). Selected demo as a class project and win full score. Full featured UIUC tutor searching website with extensive database design that is already ready to launch on campus. </li>
										<li>Team Leader in Mobile Multiparty Authentication System (MMAS) (2010). 3rd Prize in National Information Security Contest among over 500 competitors, 1st Prize in SYSU selective trials. </li>
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
		