<?php

define("GUEST", 1);
define("TUTOR", 2);
define("STUDENT", 3);

function logout() {
	session_start();
	session_unset();
	session_destroy();
	print "Logged out.<br/>\n";
}

function loggedin_user() {
	$loggedin=isset($_SESSION['userid']);
	if(!$loggedin) {
		return GUEST;
	} else {
		include('auth.php');
    		$connection = mysql_connect($server,$user,$pass);
    		if (!$connection) {
			die('Could not connect: ' . mysql_error());
    		}
    		mysql_select_db($db, $connection);
    		$user_id = $_SESSION['userid'];
    		$resource = mysql_query("SELECT id FROM `User` WHERE id = '$user_id' LIMIT 0,1 ");
    		if (! $resource) {
			die('Could not retrieve user information: ' . mysql_error());
   		 }
   		$is_tutor = mysql_num_rows($resource);

    		mysql_select_db($db, $connection);
    		$user_id = $_SESSION['userid'];
    		$resource = mysql_query("SELECT id FROM `Student` WHERE id = '$user_id' LIMIT 0,1 ");
    		if (! $resource) {
			die('Could not retrieve user information: ' . mysql_error());
   		 }
   		$is_student = mysql_num_rows($resource);


		if ($is_student) {
			return STUDENT;
		}
		if ($is_tutor) {
			return TUTOR;
		}
	}
}

function listActiveContractsStudent($user_id) {
	$query = "SELECT * FROM `hires` NATURAL JOIN `Contract` WHERE StudentID = '$user_id' AND Status = 'active'";
        $result = mysql_query($query);
        $num = mysql_num_rows($result);
        if($num > 0){
            $count = 1;
            print "<TABLE border=\"1\"><TH>#</TH><TH>COURSE</TH><TH>TUTOR</TH>";
            while($row = mysql_fetch_array($result)){
	        $tutorID = $row['TutorID'];
	        $crn = $row['CRN'];
	        
	        // getting title name
	        $queryCRN = "SELECT Title FROM `Course` WHERE CRN = '$crn'";
	        $resultCRN = mysql_query($queryCRN);
                if(! $resultCRN){
                    die('Query error: ' . mysql_error());
                }
                $rowCRN = mysql_fetch_assoc($resultCRN);
                $courseTitle = $rowCRN['Title'];
	        
	        $queryTutor = "SELECT name FROM `User` WHERE id = '$tutorID'";
	        $resultTutor = mysql_query($queryTutor);
                if(! $resultTutor){
                    die('Query error: ' . mysql_error());
                }
                $rowTutor = mysql_fetch_assoc($resultTutor);
                $tutorName = $rowTutor['name'];
	        
                print "<tr><td>$count</td>";
                print "<td>$courseTitle</td>";
                print "<td>$tutorName</td>";
                print "</tr>";
                $count++;
            }
            print "</TABLE>";
        } else {
            print "No active contracts found in the database\n";
        }
}

function listPendingContractsStudent($user_id){
	$query = "SELECT * FROM `hires` NATURAL JOIN `Contract` WHERE StudentID = '$user_id' AND Status = 'pending'";
    	$result = mysql_query($query);
    	$num = mysql_num_rows($result);
    	
    	if($num > 0){
            $count = 1;
            print "<TABLE border=\"1\"><TH>#</TH><TH>COURSE</TH><TH>TUTOR</TH>";
            while($row = mysql_fetch_array($result)){
	        $tutorID = $row['TutorID'];
	        $crn = $row['CRN'];
	        
	        // getting title name
	        $queryCRN = "SELECT Title FROM `Course` WHERE CRN = '$crn'";
	        $resultCRN = mysql_query($queryCRN);
                if(! $resultCRN){
                    die('Query error: ' . mysql_error());
                }
                $rowCRN = mysql_fetch_assoc($resultCRN);
                $courseTitle = $rowCRN['Title'];
	        
	        $queryTutor = "SELECT name FROM `User` WHERE id = '$tutorID'";
	        $resultTutor = mysql_query($queryTutor);
                if(! $resultTutor){
                    die('Query error: ' . mysql_error());
                }
                $rowTutor = mysql_fetch_assoc($resultTutor);
                $tutorName = $rowTutor['name'];
	        
                print "<tr><td>$count</td>";
                print "<td>$courseTitle</td>";
                print "<td>$tutorName</td>";
                print "</tr>";
                $count++;
            }
            print "</TABLE>";
        } else {
            print "No pending contracts found in the database\n";
        }
}

?>