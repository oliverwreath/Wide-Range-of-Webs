<?php
include('auth.php');
?>
<form action="" method="post">
	cost between <input type="text" name="minprice" /> and <input
		type="text" name="maxprice" /> <input type="submit" />
</form>
<TABLE border=1>
	<TH>#</TH>
	<TH>NAME</TH>
	<TH>EMAIL</TH>
	<TH>EDUCATION</TH>
	<TH>LANGUAGE</TH>
	<TH>PRICE</TH>
	<TH>AVG RATING</TH>
	<?php
	if(
	isset($_POST["minprice"]) &&
	isset($_POST["maxprice"])
	) {
		$minprice = $_POST["minprice"];
		$maxprice = $_POST["maxprice"];
	} else {
		die('Please input the above fields.' . mysql_error());
	}



	$con = mysql_connect($server,$user,$pass);
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

	mysql_select_db($db, $con);
	$sql = "SELECT * FROM `Tutor` WHERE price>=$minprice AND price <=$maxprice LIMIT 0, 30 ";

	$result = mysql_query($sql);
	if (!$result )
	{
		die('Could not connect: ' . mysql_error());
	}

	while($row = mysql_fetch_array($result))
	{
		echo "<TR>";
		echo "\t<TD>".$row['id'] . "</TD>\n";
		echo "\t<TD>".$row['name'] . "</TD>\n";
		echo "\t<TD>".$row['email'] . "</TD>\n";
		echo "\t<TD>".$row['education'] . "</TD>\n";
		echo "\t<TD>".$row['language'] . "</TD>\n";
		echo "\t<TD>".$row['price'] . "</TD>\n";
		echo "\t<TD>".$row['avgRating'] . "</TD>\n";
		echo "</TR>\n";

	}

	?>
</TABLE>
