
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<style TYPE="text/css">
			div.clock_class{
				width: 100%;
				height: 100%;
				display: flex;
			}

			div.clock_class div{
				height: 100%;
			}
		</style>
		<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script>
		//control
			function updateClock(){
				//data
				var currentTime = new Date();
				//view
				animation(currentTime);
			};

			setInterval( updateClock, 1000 );

			function animation( currentTime ){
				document.getElementById("hour").innerHTML = currentTime.getHours()+":";
				document.getElementById("min").innerHTML = currentTime.getMinutes()+":";
				document.getElementById("sec").innerHTML = currentTime.getSeconds();
			};
		</script>
	</head>
	<body style="height: 300px">
		<div id="clock" class="clock_class" style="height: 30px">
			<div id="hour"></div>
			<div id="min"></div>
			<div id="sec"></div>
		</div>
	</body>
</html>
