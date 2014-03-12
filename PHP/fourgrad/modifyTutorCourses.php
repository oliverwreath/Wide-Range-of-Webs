<?php
include('header.php');
include('auth.php');
print '<META HTTP-EQUIV="Pragma" CONTENT="no-cache">';

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

<script type="text/javascript">
function deleteFunc()
{


}


</script>

<script type="text/javascript">
function addFunc()
{
	<?php
		$result = mysql_query("INSERT INTO `offers`(`TutorID`, `CRN`) VALUES ('$user_id','$_POST[ctlCourse]')");
		//if(!$result)         //INSERT INTO `offers`(`TutorID`, `CRN`) VALUES ('".$user_id."','".$course."')
		//{
			//die('Could not retrieve user information: ' . mysql_error());
		//}
		//echo '<~META HTTP-EQUIV="Refresh" Content="0;>';
	?>
}

</script>


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



<html>
<body>
<?php
function selectGeneration($Name= '', $Options= array(), $Values=array() ){
	$html = '<select name="'.$Name.'">';
	$html .= '<option></option>';
	foreach ($Options as $Option => $value) {

		if($Values != NULL) {
			$html .= '<option value='.$Values[$Option].'>'.$value.'</option>';
		} else {
			$html .= '<option value='.$value.'>'.$value.'</option>';

		}
	}
	$html .= '</select>';
	return $html;
}
?>


	<h2>Modify Tutor Courses</h2>



	<?php
	$loggedin=isset($_SESSION['userid']);
	if($loggedin)
	{
		// starting a connection with the database
		$connection = mysql_connect($server,$user,$pass);
		if (!$connection) {
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($db, $connection);
		$user_id = $_SESSION['userid'];


		if($_POST['ctlCourse'] != "")
		{
			$query = "SELECT id FROM `Tutor` WHERE id = '$user_id'";
			$result = mysql_query($query);
			$num = mysql_num_rows($result);
			if ($num == 0)
			{
				die('You must be logged as a Tutor to perform this operation: ' . mysql_error());
			}
			$course = $_POST['ctlCourse'];


		if($_POST["command"]=="delete") {
				$query = "DELETE FROM `offers` WHERE offers.CRN='$course' AND TutorID=$user_id";

				$result = mysql_query($query);
				if (!$result)
				{
					die('Query error: ' . mysql_error());
				}
			}
		}

		$resource = mysql_query("SELECT name FROM `User` WHERE id = '$user_id' LIMIT 0,1 ");
		if (! $resource) {
			die('Could not retrieve user information: ' . mysql_error());
		}
		$row = mysql_fetch_assoc($resource);
		$name = $row['name'];




		$result = mysql_query("SELECT Distinct Major FROM `$db`.Course");
		$num = mysql_num_rows($result);

		$resultArray = array();

		//MYSQL_ASSOC, MYSQL_NUM, and MYSQL_BOTH.

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			//$resultArray .=  $row[1];
			array_push($resultArray , $row[0]);
			//printf("ID: %s  Name: %s", $row[0], $row[1]);
		}


		$htmlDept = selectGeneration('Course', $resultArray, NULL);


		//

		$result = mysql_query("SELECT Title, CRN, Major, Number FROM `$db`.Course");
		$num = mysql_num_rows($result);

		$titleArray = array();
		$crnArray = array();
		$majorArray = array();
		$numberArray = array();
		$tempArray= array();

		//MYSQL_ASSOC, MYSQL_NUM, and MYSQL_BOTH.

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			//$resultArray .=  $row[1];
			array_push($titleArray , $row[0]);
			array_push($crnArray , $row[1]);
			array_push($majorArray , $row[2]);
			array_push($numberArray , $row[3]);
			$temp = $row[2] . $row[3] . ' - ' . $row[0];
			array_push($tempArray, $temp );

			//printf("Title: %s  CRN: %s Major: %s Number:%s;", $row[0], $row[1], $row[2], $row[3]);
		}


		$result = mysql_query("SELECT * FROM Department");
		$num = mysql_num_rows($result);

		$deptArray= array();

		//MYSQL_ASSOC, MYSQL_NUM, and MYSQL_BOTH.

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			array_push($deptArray, $row[0]);

		}
		$htmlDepartments = selectGeneration( 'EducationCode', $deptArray, NULL);


		//$htmlCourse = selectGeneration( 'Course', $tempArray, $crnArray);
			if($_POST["command"]=="add")
			{
				/* Add to OFFERS table -- jurand */
				$query = "INSERT INTO `offers`(`TutorID`, `CRN`) VALUES ('".$user_id."','".$course."')";

				$result = mysql_query($query);
				if (!$result)
				{
					die('Query error: ' . mysql_error());
				}
				/**/
				if($education!="" && $language!="" && $price!="")
				{
					print "informations $education, $language, $price added to our database.";
				}
				else if($course !="")
				{
					print "informations $course, added to our database.";
				}




			}

		print "

		    <table border=\"1\">
			    <tr>
				    <td colspan='5'>Hi <b>$name</b> </td>
			    </tr>
			    <tr>
				    <td colspan='5'>Pick Courses Below</td> 
			    </tr>
			    <tr>";
		//$CRNs= mysql_query("SELECT * FROM `offers` where TutorID = '101'");

		$Courses= mysql_query("SELECT * FROM Course x, offers y where y.TutorID = $userid AND x.CRN = y.CRN");
		if (!Courses) {
			die('Query error: ' . mysql_error());
		}

		while($row = mysql_fetch_array($Courses))
		{
			$CRN=$row['CRN'];
			$Major=$row['Major'];
			$Number=$row['Number'];
			$Title=$row['Title'];
			//echo "\t\t<option value=$code>$code - $name</option>\n";
			print"
						<tr>
							
							<td>$CRN;</td>
							<td>$Major;</td>
							<td>$Number;</td>
							<td>$Title;</td>
							<td><form action=\"\" method=\"post\"><input type=\"hidden\" name=\"command\" value=\"delete\"><input type='hidden' name='ctlCourse' value='$CRN'><input type=\"submit\" onclick=\"deleteFunc()\" value=\"Delete!\" /></form></td>						
						</tr>";



		}






		print "</tr>
			    
		    </table>
	    	";



		print "<form action=\"\" method=\"post\">
		<input type='hidden' name='command' value='add'>

		    	<table border=\"1\">
			    	<tr>
			    		<td>
					    <select id=\"ctlDept\" name=\"ctlDept\">
						<option value=\"-1\">Select Department</option>";

		while($row = mysql_fetch_array($tutors))
		{
			$code=$row['Code'];
			$name=$row['Name'];
			echo "\t\t<option value=$code>$code - $name</option>\n";
		}

		print "</select>
					    
					    <select id=\"ctlCourse\" name=\"ctlCourse\">
					    	<option value=\"-1\"><- Choose a Dept.</option>
					    </select> 
					</td>
			    	    	<td><input type=\"submit\" onclick=\"addFunc()\" value=\"Add!\" /></td>	
			    	</tr>
			</table>
	    	</form>";


		// closing connection with database
		mysql_close($connection);
	}
	else
	{
		print "You must be logged in to perform this operation";
	}


	?>
</body>
</html>
