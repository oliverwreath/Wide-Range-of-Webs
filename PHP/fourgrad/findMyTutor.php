<?php
include('header.php');
include('auth.php');
?>

<h2>Find My Tutor</h2>

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
    
    print "<form action=\"\" method=\"post\">
	    <table>
	    	    <tr>
			    <td>Name</td>
			    <td><input type=\"text\" name=\"name\" /></td>
		    </tr>
	    	    <tr>
			    <td>Email</td>
			    <td><input type=\"text\" name=\"email\" /></td>
		    </tr>
		    <tr>
			    <td>Education</td>
			    <td><input type=\"text\" name=\"education\" /></td>
		    </tr>
		    <tr>
			    <td>Language</td>
			    <td><input type=\"text\" name=\"language\" /></td>
		    </tr>
		    <tr>
			    <td>Price</td>
			    <td><input type=\"text\" name=\"price\" /></td>
		    </tr>
	    </table>
	    <input type=\"submit\" />
    </form>";
    
    if($_POST["education"]!="" && $_POST["language"]!="" && $_POST["price"]!="" )
    {
        $query = "SELECT id FROM `Tutor` WHERE id = '$user_id'";
        $result = mysql_query($query);
        $num = mysql_num_rows($result);
        if ($num == 0) {
        	die('You must be logged as a Tutor to perform this operation: ' . mysql_error());
        }
        $education = $_POST["education"];
        $language = $_POST["language"];
        $price = $_POST["price"];
        
        $query = "UPDATE `$db`.`Tutor` SET education='$education', language='$language', price='$price' WHERE id=$user_id";
        $result = mysql_query($query);
        if (!$result) {
            die('Query error: ' . mysql_error());
        }
        print "informations $education, $language, $price added to our database.";1
    }

    // closing connection with database
    mysql_close($connection);
}
else
{
    print "You must be logged in to perform this operation";
}


?>