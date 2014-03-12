		
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
						<h2>Resume</h2>
						<table>
							<tr>
								<td>
									<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
									<script type="IN/MemberProfile" data-id="http://www.linkedin.com/in/yanliangoliver" data-format="inline" data-related="false"></script>

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
		