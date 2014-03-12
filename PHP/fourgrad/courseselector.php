<?php
include("auth.php");
$con = mysql_connect($server,$user,$pass);
if (!$con) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db($db, $con);
$tutors=mysql_query("SELECT * FROM `Department`");
if (!tutors ) {
	die('Query error: ' . mysql_error());
}
?>

<script
	type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8">
	//based on http://remysharp.com/2007/01/20/auto-populating-select-boxes-using-jquery-ajax/
		$(function(){
			$("select#ctlDept").change(function(){
				$.getJSON("coursesjson.php",{id: $(this).val()}, function(j){
					var options = '';
					for (var i = 0; i < j.length; i++) {
						options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
					}
					$("#ctlCourse").html(options);
					$('#ctlCourse option:first').attr('selected', 'selected');
				})
			})			
		})
		</script>

<select id="ctlDept" name="ctlDept">
	<option value="-1">Select Department</option>
	<?php
	while($row = mysql_fetch_array($tutors))
	{
		$code=$row['Code'];
		$name=$row['Name'];
		echo "\t\t<option value=$code>$code - $name</option>\n";
	}
	?>

</select>
<select id="ctlCourse" name="ctlCourse">

	<option value="-1"><- Choose a Dept.</option>
</select>

