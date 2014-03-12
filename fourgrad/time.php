<?php
include('auth.php');
include('header.php');
define("MORNING",1);
define("AFTERNOON",2);
define("EVENING",3);
define("NIGHT",4);

$times = array("","MORNING","AFTERNOON","EVENING","NIGHT");

?>
<table>
<form action="" method="post">
<tr><td>I need minutes of time with tutor:</td><td><input type=text name=timewanted> minutes</td></tr>
<tr>
<td>My first time preference is in the</td>
<td><select name="period_pref1">
  <option value="-1">Select Time of Day</option>
  <option value="<?php echo MORNING; ?>">Morning</option>
  <option value="<?php echo AFTERNOON; ?>">Afternoon</option>
  <option value="<?php echo EVENING; ?>">Evening</option>
  <option value="<?php echo NIGHT; ?>">Night</option>
</select>
</td></tr>
<tr>
<td>My second time preference is in the</td>
<td><select name="period_pref2">
  <option value="-1">Select Time of Day</option>
  <option value="<?php echo MORNING; ?>">Morning</option>
  <option value="<?php echo AFTERNOON; ?>">Afternoon</option>
  <option value="<?php echo EVENING; ?>">Evening</option>
  <option value="<?php echo NIGHT; ?>">Night</option>
</select> 
</td></tr>
<tr>
<td><input type=submit></td>
</tr>
</form>
</table>
<?php

function get_preference($start, $end)
{
//    $query = "SELECT Tutor.id, SUM(TIME_TO_SEC(TIMEDIFF(Time_End,Time_Start))/60) AS minutes FROM Setting,available, Tutor";
//    $query .= " ";
//    $query .= "WHERE available.UserID=Tutor.id AND available.SettingID= Setting.id";
//    $query .= " ";
    $query = "SELECT * FROM TotalTutorTimeVIEW";
    $query .= " ";
    $query .= "WHERE Time_End <= '".$end."' AND Time_Start >= '".$start."'";
//    $query .= "AND Time_End <= '".$end."' AND Time_Start >= '".$start."'";
    $query .= " ";
//    $query .= "GROUP BY Tutor.id";
//    $query .= " ";
    $query .= "HAVING minutes>=".$_POST["timewanted"];
    
echo $query;

    $result = array();
    
    $tutors = mysql_query($query);
    if (!tutors ) {
        die('Query error: ' . mysql_error());
    }
    
    while ($row = mysql_fetch_array($tutors)) {
        
        array_push($result, array($row["minutes"],$row["id"]));
    }
    return $result;
}

if ($_GET["mins"]!="") {
    $mins = $_GET["mins"];
} else {
    $mins = 0;
}

if ($_GET["id"]!="") {
    $id = $_GET["id"];
} else {
    $id = 0;
}

$con = mysql_connect($server,$user,$pass);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db($db, $con);

$time_start = array("0","0");
$time_end = array("0","0");

$period_pref1 = $_POST["period_pref1"];
switch ($period_pref1) {
case MORNING:
    $time_start[0] = "06:00:00";
    $time_end[0] = "11:59:59";
    break;
case AFTERNOON:
    $time_start[0] = "12:00:00";
    $time_end[0] = "17:59:59";
    break;
case EVENING:
    $time_start[0] = "18:00:00";
    $time_end[0] = "23:59:59";
    break;
case NIGHT:
    $time_start[0] = "00:00:00";
    $time_end[0] = "05:59:59";
    break;
    default:
    echo "none of the above";
}

$period_pref2 = $_POST["period_pref2"];
switch ($period_pref2) {
case MORNING:
    $time_start[1] = "06:00:00";
    $time_end[1] = "11:59:59";
    break;
case AFTERNOON:
    $time_start[1] = "12:00:00";
    $time_end[1] = "17:59:59";
    break;
case EVENING:
    $time_start[1] = "18:00:00";
    $time_end[1] = "23:59:59";
    break;
case NIGHT:
    $time_start[1] = "00:00:00";
    $time_end[1] = "05:59:59";
    break;
    default:
    echo "none of the above";
}

echo "first preference:";
echo $time_start[0];
echo "--";
echo $time_end[0];
echo "<BR>";

echo "second preference:";
echo $time_start[1];
echo "--";
echo $time_end[1];
echo "<BR>";


echo "TUTORS WITH MOST AMOUNT OF TIME IN pref1:";
echo"<br>";
?>
<TABLE border="1">
	<TH>Minutes</TH>
	<TH>TutorID</TH>
	<TH>Preference</TH>

<?php



$pref1 = get_preference($time_start[0], $time_end[0]);
echo "<TR>";
foreach($pref1 as $i => $time) {
    echo "<TD>".$time[0]."</TD>";
    echo "<TD>".$time[1]."</TD>";
    echo "<TD>$times[$period_pref1]</TD>";
}

echo "</TR>";
if($period_pref1 != $period_pref2) {
echo "TUTORS WITH MOST AMOUNT OF TIME IN pref2:";
echo"<br>";
$pref2 = get_preference($time_start[1], $time_end[1]);
foreach($pref2 as $i => $time) {
    echo "<TD>".$time[0]."</TD>";
    echo "<TD>".$time[1]."</TD>";
    echo "<TD>$times[$period_pref2]</TD>";
}
echo"<br>";
}

// also can do
// TOTAL AMOUNT OF TUTOR TIME YOU WANT -> SEE WHICH TUTORS HAVE ENOUGH TIME FOR YOU

    $query = "SELECT Tutor.id, SUM(TIME_TO_SEC(TIMEDIFF(Time_End,Time_Start))/60) AS minutes FROM Setting,available, Tutor";
    $query .= " ";
    $query .= "WHERE available.UserID=Tutor.id AND available.SettingID= Setting.id";
    $query .= " ";
    $query .= "GROUP BY Tutor.id";
    $query .= " ";
    $query .= "HAVING minutes > ".$_POST["timewanted"];
    
	echo $_POST["timewanted"]." minutes wanted <p>";


    $result = array();
    
    $tutors = mysql_query($query);
    if (!tutors ) {
        die('Query error: ' . mysql_error());
    }
    
    while ($row = mysql_fetch_array($tutors)) {
        
        echo $row["minutes"] ." minutes total for #".$row["id"];
	echo "<br>";
    }
    return $result;


mysql_close($con);

?>
