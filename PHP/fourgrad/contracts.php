<?php
include('header.php');
include('auth.php');
include('common.php');
?>

<h2>Contracts</h2>

<?php
define( "ALPHA", 0.80 );
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
    $studentName = $row['name'];
    $tutorName = $row['name'];
    
    // providing two different views depending on type of user
    $query = "SELECT * FROM `Student` WHERE id = '$user_id'";
    $result = mysql_query($query);
    $num = mysql_num_rows($result);
    if ($num == 0) {
   
        // getting parameters for insertion
        if($_POST["contract"]!="") {
            $contractID = $_POST["contract"];
            
            // modifying a contract
            $query = "UPDATE `Contract` SET Status = 'active' WHERE ContractID = $contractID";
    	    $result = mysql_query($query);
            if(! $result){
                die('Query error: ' . mysql_error());
            }
        }
        
        // getting parameters for modification
        if($_POST["finishContract"]!="") {
            $contractID = $_POST["finishContract"];
            
            // modifying a contract
            $query = "UPDATE `Contract` SET Status = 'finished' WHERE ContractID = $contractID";
    	    $result = mysql_query($query);
            if(! $result){
                die('Query error: ' . mysql_error());
            }
        }
        
        // tutor view
    	print "<h2>Pending Contracts for $tutorName</h2>";
   
        // starting the form
   	print "<form action=\"\" method=\"post\"><table>";

        // list of contracts
        print "<tr><td>Contracts: </td>";
        print "<td><select name=\"contract\">";
        print "<option value=\"\">Select a Contract</option>";
        $query = "SELECT * FROM `hires` NATURAL JOIN `Contract` WHERE tutorID = '$user_id' AND Status = 'pending'";
    	$result = mysql_query($query);
	while($row = mysql_fetch_array($result)){
	    $contractID = $row['ContractID'];
	    $crn = $row['CRN'];
	    
	    // getting title name
	    $queryCRN = "SELECT Title FROM `Course` WHERE CRN = '$crn'";
	    $resultCRN = mysql_query($queryCRN);
            if(! $resultCRN){
                die('Query error: ' . mysql_error());
            }
            $rowCRN = mysql_fetch_assoc($resultCRN);
            $courseTitle = $rowCRN['Title'];
            print "<option value=\"$contractID\">$courseTitle</option>";
        }
        print "</select></td></tr>";
    
        // closing the form
        print "</table><input type=\"submit\" /></form>";
        
        // Finish a contract
        print "<h2>Finish a Contract</h2>";

	// starting the form to finish a contract
   	print "<form action=\"\" method=\"post\"><table>";

        // list of contracts
        print "<tr><td>Contracts: </td>";
        print "<td><select name=\"finishContract\">";
        print "<option value=\"\">Select a Contract</option>";
        $query = "SELECT * FROM `hires` NATURAL JOIN `Contract` WHERE tutorID = '$user_id' AND Status = 'active'";
    	$result = mysql_query($query);
	while($row = mysql_fetch_array($result)){
	    $contractID = $row['ContractID'];
	    $crn = $row['CRN'];
	    
	    // getting title name
	    $queryCRN = "SELECT Title FROM `Course` WHERE CRN = '$crn'";
	    $resultCRN = mysql_query($queryCRN);
            if(! $resultCRN){
                die('Query error: ' . mysql_error());
            }
            $rowCRN = mysql_fetch_assoc($resultCRN);
            $courseTitle = $rowCRN['Title'];
            print "<option value=\"$contractID\">$courseTitle</option>";
        }
        print "</select></td></tr>";
    
        // closing the form
        print "</table><input type=\"submit\" /></form>";
        
        // listing the active contracts
        print "<h2>Active Contracts for $tutorName</h2>";
        $query = "SELECT * FROM `hires` NATURAL JOIN `Contract` WHERE TutorID = '$user_id' AND Status = 'active'";
        $result = mysql_query($query);
        $num = mysql_num_rows($result);
        
        if($num > 0){
            $count = 1;
            print "<TABLE border=\"1\"><TH>#</TH><TH>COURSE</TH><TH>STUDENT</TH>";
            while($row = mysql_fetch_array($result)){
	        $studentID = $row['StudentID'];
	        $crn = $row['CRN'];
	        
	        // getting title name
	        $queryCRN = "SELECT Title FROM `Course` WHERE CRN = '$crn'";
	        $resultCRN = mysql_query($queryCRN);
                if(! $resultCRN){
                    die('Query error: ' . mysql_error());
                }
                $rowCRN = mysql_fetch_assoc($resultCRN);
                $courseTitle = $rowCRN['Title'];
	        
	        // getting student name
	        $queryStudent = "SELECT name FROM `User` WHERE id = '$studentID'";
	        $resultStudent = mysql_query($queryStudent);
                if(! $resultStudent){
                    die('Query error: ' . mysql_error());
                }
                $rowStudent = mysql_fetch_assoc($resultStudent);
                $studentName = $rowStudent['name'];
	        
                print "<tr><td>$count</td>";
                print "<td>$courseTitle</td>";
                print "<td>$studentName</td>";
                print "</tr>";
                $count++;
            }
            print "</TABLE>";
        } else {
            print "No active contracts found in the database\n";
        }

    
    } else {
    
    	// student view
    	print "<h2>Create a Contract</h2>";
	print "<form action=\"\" method=\"post\"><table>";

	include("courseselector.php");
	
        // list of tutors
        print "<tr><td>Tutor: </td>";
        print "<td><select name=\"tutor\">";
        print "<option value=\"\">Select a Tutor</option>";
        $query = "SELECT * FROM `Tutor` NATURAL JOIN `User`";
    	$result = mysql_query($query);
	while($row = mysql_fetch_array($result)){
	    $id = $row['id'];
	    $tutor = $row['name'];
            print "<option value=\"$id\">$tutor</option>";
        }
        print "</select></tr>";
        
        
        // closing the form
        print "</table><input type=\"submit\" /></form>";
        
        // getting parameters for insertion
        if($_POST["ctlCourse"]!="" && $_POST["tutor"]!="") {
            $crn = $_POST["ctlCourse"];
            $id = $_POST["tutor"];
            
            // adding a new contract
            $query = "SELECT MAX(ContractID) FROM `Contract` WHERE 1";
            $result = mysql_query($query);
            if(! $result){
                die('Query error: ' . mysql_error());
            }
            $row = mysql_fetch_assoc($result);
            $contractID = $row['MAX(ContractID)'];
            $contractID++;
            $query = "INSERT INTO `Contract` (`ContractID`, `Status`, `Semester`) VALUES ($contractID,'pending','spring2012')";
    	    $result = mysql_query($query);
            if(! $result){
                die('Query error: ' . mysql_error());
            }

            // adding a new entry in table named hires
            $query = "INSERT INTO `hires` (`TutorID`, `StudentID`, `CRN`, `ContractID`) VALUES ($id,$user_id,$crn,$contractID)";
    	    $result = mysql_query($query);
            if(! $result){
                die('Query error: ' . mysql_error());
            }
        }

        // getting parameters for modification
        if($_POST["finishContract"]!="") {
            $contractID = $_POST["finishContract"];
            
            // modifying a contract
            $query = "UPDATE `Contract` SET Status = 'finished' WHERE ContractID = $contractID";
    	    $result = mysql_query($query);
            if(! $result){
                die('Query error: ' . mysql_error());
            }
        }
        
        // Finish a contract
        print "<h2>Finish a Contract</h2>";

        // starting the form to finish a contract
   	print "<form action=\"\" method=\"post\"><table>";

        // list of contracts
        print "<tr><td>Contracts: </td>";
        print "<td><select name=\"finishContract\">";
        print "<option value=\"\">Select a Contract</option>";
        $query = "SELECT * FROM `hires` NATURAL JOIN `Contract` WHERE studentID = '$user_id' AND Status = 'active'";
    	$result = mysql_query($query);
	while($row = mysql_fetch_array($result)){
	    $contractID = $row['ContractID'];
	    $crn = $row['CRN'];
	    
	    // getting title name
	    $queryCRN = "SELECT Title FROM `Course` WHERE CRN = '$crn'";
	    $resultCRN = mysql_query($queryCRN);
            if(! $resultCRN){
                die('Query error: ' . mysql_error());
            }
            $rowCRN = mysql_fetch_assoc($resultCRN);
            $courseTitle = $rowCRN['Title'];
            print "<option value=\"$contractID\">$courseTitle</option>";
        }
        print "</select></td></tr>";
    
        // closing the form
        print "</table><input type=\"submit\" /></form>";
        
        // listing the active contracts
        print "<h2>Active Contracts for $studentName</h2>";
        listActiveContractsStudent($user_id);
        
        // listing the pending contracts
        print "<h2>Pending Contracts for $studentName</h2>";
        listPendingContractsStudent($user_id);
    	
    	// Leave Feedback
        print "<h2>Leave Feedback</h2>";

        // starting the form to finish a contract
   	print "<form action=\"\" method=\"post\"><table>";

        // list of contracts
        print "<tr><td>Contracts: </td>";
        print "<td><select name=\"feedbackContract\">";
        print "<option value=\"\">Select a Contract</option>";
        $query = "SELECT * FROM `hires` NATURAL JOIN `Contract` WHERE studentID = '$user_id' AND Status = 'finished'";
    	$result = mysql_query($query);
	while($row = mysql_fetch_array($result)){
	    $contractID = $row['ContractID'];
	    $crn = $row['CRN'];
	    
	    // getting title name
	    $queryCRN = "SELECT Title FROM `Course` WHERE CRN = '$crn'";
	    $resultCRN = mysql_query($queryCRN);
            if(! $resultCRN){
                die('Query error: ' . mysql_error());
            }
            $rowCRN = mysql_fetch_assoc($resultCRN);
            $courseTitle = $rowCRN['Title'];
            print "<option value=\"$contractID\">$courseTitle</option>";
        }
        print "</select></td></tr>";
        print "<tr><td>Comment:</td><td><input type=\"text\" name=\"comment\" size=\"100\" /></td></tr>";
        print "<tr><td>Rating:</td><td><input type=\"text\" name=\"rating\" size=\"10\" /> (0-10)</td></tr>";
        
        // closing the form
        print "</table><input type=\"submit\" /></form>";
    
    	// getting parameters for feedback
        if($_POST["feedbackContract"]!="" && $_POST["comment"] != "" && $_POST["rating"] != "") {
            $contractID = $_POST["feedbackContract"];
            $comment = $_POST["comment"];
            $rating = $_POST["rating"];
                        
            // modifying a contract
            $query = "INSERT INTO `Feedback` (`FeedbackID`, `Comment`, `Rating`, `ContractID`) VALUES (NULL, '$comment', '$rating','$contractID')"; 
    	    $result = mysql_query($query);
            if(! $result){
                die('Query error: ' . mysql_error());
            }
            
            // modifying tutor's average rating
            // we use an exponential moving average: avg_(t+1) = ALPHA*rating + (1-ALPHA)*avg_t
            $query = "SELECT TutorID FROM `hires` WHERE contractID = '$contractID'";
            $result = mysql_query($query);
            if(! $result){
                die('Query error: ' . mysql_error());
            }
            $row = mysql_fetch_assoc($result);
            $tutorID = $row['TutorID'];
            
            // retrieving the previous rating
            $query = "SELECT avgRating FROM `Tutor` WHERE id = '$tutorID'";
            $result = mysql_query($query);
            if(! $result){
                die('Query error: ' . mysql_error());
            }
            $row = mysql_fetch_assoc($result);
            $avgRating = $row['avgRating'];
            $avgRating = ALPHA*$rating + (1-ALPHA)*$avgRating;
            
            // updating average rating in the database
            $query = "UPDATE `Tutor` SET avgRating = '$avgRating' WHERE id = '$tutorID'";
            $result = mysql_query($query);
            if(! $result){
                die('Query error: ' . mysql_error());
            }
            
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