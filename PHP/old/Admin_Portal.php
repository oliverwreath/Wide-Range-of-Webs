		
<?php include_once("header.php");
		include_once("head.php")?>
		
	</head>

	<body>
		<?php
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
		    echo 'Please proceed to login if you know what you are doing, otherwise, hope you enjoy it here!';
		} else {
		    echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
		    echo "<p>You entered {$_SERVER['PHP_AUTH_PW']} as your password.</p>";
		    
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
							<h2>Admin_Portal</h2>
							<table>
								<tr>
									<td>
										<img src ="/pictures/Website-Under-Construction.gif" width="256" height="256" alt="Site Under Construction ! " />
									</td>
								</tr>
							</table>
	
						</td>
					</tr>
					<?php include_once("bottom.php") ?>
				</table>
			</div>
		
		
		}
		?> 


	</body>
</html>
		