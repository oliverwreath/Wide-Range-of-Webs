<?php
include('header.php');
include('auth.php');
print '<META HTTP-EQUIV="Pragma" CONTENT="no-cache">';
?>


<h2>Delete Tutor Availability</h2>


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
    
    		if($_POST["command"] == "delete") {
			$number = $_POST["deleteNumber"];
				$query = "DELETE 
				FROM Setting
				WHERE Setting.id = '$number'";
				
				$query2 = "DELETE 
				FROM available 
				WHERE available.SettingID = '$number'";

				$result = mysql_query($query);
				if (!$result)
				{
					print "$query";
					die('Query error: ' . mysql_error());
				}
				
				$result = mysql_query($query2);
				if (!$result)
				{
					print "$query2";
					die('Query error: ' . mysql_error());
				}
		}    
		
    
    
    	$query = "SELECT s.id, s.Location, s.Time_Start, s.Time_End
		FROM `Setting` s, available, User 
		WHERE User.id = available.UserID AND available.SettingID = s.id AND User.id ='$user_id'";

	
	$result = mysql_query($query);
	$num = mysql_num_rows($result);
				if (!$result)
				{
					die('Query error: ' . mysql_error());
				}
	
	function mysql_fetch_all($res) {
	   while($row=mysql_fetch_array($res)) {
	       $return[] = $row;
	   }
	   return $return;
	}

	$all = mysql_fetch_all($result);
	if( count($all)<1 )
	{
		print "<table>";
		print "<tr><th>id</th><th>Location</th><th>Time_Start</th><th>Time_End</th></tr>";
		print "<tr><td colspan = \"4\">No Previous Availability Records</td></tr>";
		print "</table>";
	}
	else
	{
		print "<table>";
		print "<tr><th>id</th><th>Location</th><th>Time_Start</th><th>Time_End</th><th>Action For Press</th></tr>";
		for( $i = 0 ; $i < count($all) ; $i++ )
		{
			$number = $all[$i][0] ;
			
			print "<tr><td>{$all[$i][0]}</td><td>{$all[$i][1]}</td><td>{$all[$i][2]}</td><td>{$all[$i][3]}</td>
			
			<td>
			<form action=\"\" method=\"post\">
				<input type='hidden' name='deleteNumber' value='$number' />
				<input type=\"submit\" name=\"command\" value=\"delete\" />
			</form>
			</td></tr>";
		}
		print "</table>";
	}
    

 
 

    // closing connection with database
    mysql_close($connection);
}
else
{
    print "You must be logged in to perform this operation";
}


?>