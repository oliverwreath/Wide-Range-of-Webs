<?php
include('header.php');
include('auth.php');
?>


<h2>Add Major</h2>

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
    print "</tr><tr><td>Major</td>";
    print "<td><input type=\"text\" name=\"major\" /></td>";
    print "</tr></table><input type=\"submit\" value=\"Change Major\" /></form>";
    if($_POST["major"]!="")
    {
        $query = "SELECT major FROM `Student` WHERE id = '$user_id'";
        $result = mysql_query($query);
        $num = mysql_num_rows($result);
        if ($num == 0) {
        	die('You must be logged as a student to perform this operation: ' . mysql_error());
        }
        $major = $_POST["major"];
        $query = "UPDATE `$db`.`Student` SET major='$major' WHERE id=$user_id";
        $result = mysql_query($query); 
        if (!$result) {
            die('Query error: ' . mysql_error());
        }
        print "Major $major added to our database.";
    }

    // closing connection with database
    mysql_close($connection);
}
else
{
    print "You must be logged in to perform this operation";
}


?>