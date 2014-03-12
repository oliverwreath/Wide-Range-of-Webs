<?php
include('header.php');
include('auth.php');
?>


<h2>Add Tutor Availability</h2>

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
    print "<form action=\"\" method=\"post\">";
    
    print "<table><tr><td>Name:</td><td>";
    print $name;
    print "</td>";
    

    
        print "</tr><tr><td>Location</td>";
    print "<td><input type=\"text\" name=\"Location\" value =\"201 North Goodwin Avenue\" /></td>";
    
        print "</tr><tr><td>Time_Start</td>";
    print "<td><input type=\"time\" name=\"Time_Start\" max =\"23:59:59\" min =\"00:00:00\"  value=\"00:00:00\" /></td>";
    
        print "</tr><tr><td>Time_End</td>";
    print "<td><input type=\"time\" name=\"Time_End\" max =\"23:59:59\" min =\"00:00:00\"  value=\"23:59:59\" /></td>";
    
    	
    
    print "</tr></table><input type=\"submit\" value=\"Add Availability\" /></form>";
    
    if($_POST["Location"]!="" && $_POST["Time_Start"]!="" && $_POST["Time_End"]!="")
    {
        $query = "SELECT id FROM `Tutor` WHERE id = '$user_id'";
        $result = mysql_query($query);
        $num = mysql_num_rows($result);
        if ($num == 0) {
        	die('You must be logged as a Tutor to perform this operation: ' . mysql_error());
        }
        
        $Location = $_POST["Location"];
        $Time_Start = $_POST["Time_Start"];
        $Time_End = $_POST["Time_End"];
        
        $query = "SELECT Setting.id from Setting, User, available where Setting.id = available.SettingID AND available.UserID = User.id AND User.id = $user_id";
        $result = mysql_query($query);
        $num = mysql_num_rows($result);
        //if ($num == 0) {
        	$query = "INSERT INTO `$db`.`Setting` VALUES ( NULL, '\"$Location\"', '$Time_Start', '$Time_End')";
        	$result = mysql_query($query); 
	        if (!$result) {
	        	print "$query";
	            	die('Query error: ' . mysql_error());
	        }
	        
	        $query = "SELECT id
FROM  `Setting` 
Where id >= ALL
(
SELECT id 
FROM Setting
)";
        	$result = mysql_query($query); 
	        if (!$result) {
	        	print "$query";
	            	die('Query error: ' . mysql_error());
	        }
	        $row = mysql_fetch_array( $result );
	        
	        $query = "INSERT INTO
 `available`(`UserID`, `SettingID`) 
VALUES ('$user_id','$row[0]')";
        	$result = mysql_query($query); 
	        if (!$result) {
	        	print "$query";
	            	die('Query error: ' . mysql_error());
	        }



        //}
        //else
        //{
        	//$row = mysql_fetch_array( $result );

        	//$query = "UPDATE `$db`.`Setting` SET Location='\"$Location\"', Time_Start='$Time_Start', Time_End='$Time_End' WHERE id= $row[0]";
        	//$result = mysql_query($query); 
	        //if (!$result) {
	        	//print "$query";
	            	//die('Query error: ' . mysql_error());
	        //}
        	
        //}

        print "Availability Location='$Location', Time_Start='$Time_Start', Time_End='$Time_End' added to our database.";
    }

    // closing connection with database
    mysql_close($connection);
}
else
{
    print "You must be logged in to perform this operation";
}


?>