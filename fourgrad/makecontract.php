<?php
include('header.php');
include('auth.php');
include('common.php');
?>

<?php
    $id=intval($_GET["tutorid"]);
    $crn=intval($_GET["CRN"]);
    $user_id = $_SESSION['userid'];
    
    // starting a connection with the database
    $connection = mysql_connect($server,$user,$pass);
    if (!$connection) {
	die('Could not connect: ' . mysql_error());
    }
    mysql_select_db($db, $connection);

    // getting name of student
    $resource = mysql_query("SELECT name FROM `User` WHERE id = '$user_id' LIMIT 0,1 ");
    if (! $resource) {
	die('Could not retrieve user information: ' . mysql_error());
    }
    $row = mysql_fetch_assoc($resource);
    $studentName = $row['name'];
            
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
    $query = "INSERT INTO `hires` (`TutorID`, `StudentID`, `CRN`, `ContractID`) VALUES ('$id','$user_id','$crn','$contractID')";
    $result = mysql_query($query);
    if(! $result){
        die('Query error: ' . mysql_error());
    }
    
    // Giving feedback to the user
    print "New contract added to the pending list";

    // listing the active contracts
        print "<h2>Active Contracts for $studentName</h2>";
        listActiveContractsStudent($user_id);
        
        
        // listing the pending contracts
        print "<h2>Pending Contracts for $studentName</h2>";
        listPendingContractsStudent($user_id);


    // closing connection with database
    mysql_close($connection);
?>