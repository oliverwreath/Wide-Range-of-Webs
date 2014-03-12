<?php
include('header.php');
include('auth.php');
?>


<h2>Modify Tutor Information</h2>

<?php
$loggedin=isset($_SESSION['userid']);
if($loggedin)
{
    // starting a connection with the database
    // $connection = mysql_connect($server,$user,$pass);
    //if (!$connection) {
	//die('Could not connect: ' . mysql_error());
    //}
    //mysql_select_db($db, $connection);
    //$user_id = $_SESSION['userid'];
    //$resource = mysql_query("SELECT name FROM `User` WHERE id = '$user_id' LIMIT 0,1 ");
    //if (! $resource) {
//	die('Could not retrieve user information: ' . mysql_error());
  //  }
    

    // closing connection with database
    // mysql_close($connection);
}
else
{
    print "It Works            You must be logged in to perform this operation";
}


?>