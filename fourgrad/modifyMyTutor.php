<?php
include('header.php');
include('auth.php');

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

	<h2>Modify Tutor Information</h2>

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

		print "<form action=\"\" method=\"post\">
		    <table border=\"1\">
			    <tr>
				    <td colspan='4'>Hi <b>$name</b> </td>
			    </tr>
			    <tr>
				    <td>Education</td>
				    <td>
				    
				    $htmlDepartments
				    <select name=\"education\"><option></option><option value=\"Master\">Master</option><option value=\"PhD\">PhD</option><option value=\"Bachelor\">Bachelor</option></select></td>
			    </tr>
			    <tr>
				    <td>Language</td>
				    <td><input type=\"text\" name=\"language\" /></td>
			    </tr>
			    <tr>
				    <td>Price</td>
				    <td><input type=\"text\" name=\"price\" /></td>
			    </tr>
			    <tr>
			    	    <td colspan='2'><input type=\"submit\" /></td>
			    </tr>
		    </table>
	    	</form>";


		if(($_POST["EducationCode"]!=""&&$_POST["education"]!="" && $_POST["language"]!="" && $_POST["price"]!="") || $_POST['Course'] != "")
		{
			$query = "SELECT id FROM `Tutor` WHERE id = '$user_id'";
			$result = mysql_query($query);
			$num = mysql_num_rows($result);
			if ($num == 0) {
				die('You must be logged as a Tutor to perform this operation: ' . mysql_error());
			}
			$education = $_POST["education"];
			$educationcode = $_POST["EducationCode"];
			$language = $_POST["language"];
			$price = $_POST["price"];
			$course = $_POST['Course'];

			$query = "UPDATE `$db`.`Tutor` SET education='$education', language='$language', price='$price', EducationCode='$educationcode' WHERE id=$user_id";
			$result = mysql_query($query);
			if (!$result) {
				die('Query error: ' . mysql_error());
			}


			/* Add to OFFERS table -- jurand */
			$query = "INSERT INTO `offers`(`TutorID`, `CRN`) VALUES ('".$user_id."','".$course."')";
			$result = mysql_query($query);
			if (!$result) {
				die('Query error: ' . mysql_error());
			}
			/**/
			if($education!="" && $language!="" && $price!="") {
				print "informations $education, $language, $price added to our database.";
			} else if($course !="") {
				print "informations $course, added to our database.";

			}
		}

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