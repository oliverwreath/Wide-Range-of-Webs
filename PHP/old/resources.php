		
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
						<h2>Quick Links - Accelerating Science and Engineering</h2>
						<table>
							<tr>
								<td>
									<h3>Productivity</h3>
								</td>
							</tr>
							<tr>
								<td>
									<a href="http://www.sublimetext.com/" target="_blank">Sublime Text</a>
								</td>
							</tr>
							<tr>
								<td>
									<a href="http://www.grc.com/dns/benchmark.htm" target="_blank">DNS Benchmark</a>
								</td>
							</tr>
							<tr>
								<td>
									<h3>Academic</h3>
								</td>
							</tr>
							<tr>
								<td>
									<a href="http://dl.acm.org/dl.cfm?CFID=179366373&CFTOKEN=27402769" target="_blank">ACM</a>
								</td>
							</tr>
							<tr>
								<td>
									<a href="http://ieeexplore.ieee.org/Xplore/home.jsp?tag=1" target="_blank">IEEE</a>
								</td>
							</tr>
							<tr>
								<td>
									<a href="http://www.comsoc.org/publications-content-digest" target="_blank">IEEE Digest</a>	
								</td>
							</tr>
							<tr>
								<td>
									<a href="http://www.ntu.edu.sg/home/ASSourav/crank.htm" target="_blank">Conference Ranking</a>	
								</td>
							</tr>
							<tr>
								<td>
									<h3>CS & Math</h3>
								</td>
							</tr>
							<tr>
								<td>
									<a href="http://web.mit.edu/afs/.athena/astaff/project/logos/olh/Math/Matlab/TOC.html" target="_blank">Matlab</a>
								</td>
							</tr>
							<tr>
								<td>
									<a href="http://www.mathworks.com/discovery/gallery.html" target="_blank">Plot Gallery</a>
								</td>
							</tr>
							<tr>
								<td>
									<h3>Performance</h3>
								</td>
							</tr>
							<tr>
								<td>
									<a href="http://www.mathworks.com/matlabcentral/fileexchange/5685" target="_blank">Writing fast code</a>
								</td>
							</tr>
							<tr>
								<td>
									<h3>References</h3>
								</td>
							</tr>
							<tr>
								<td>
									<a href="http://choosealicense.com/" target="_blank">Choose A License</a>
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
		