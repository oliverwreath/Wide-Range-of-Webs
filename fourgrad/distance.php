<?php
include('auth.php');
include('header.php');
define("MORNING", 1);
define("AFTERNOON", 2);
define("EVENING", 3);
define("NIGHT", 4);

?>
<table>
<form action="" method="post">
<tr>
<td>Within radius distance of 
</tr><tr><td><select name="max">
  <option value="0.5">0.5</option>
  <option value="1">1</option>
  <option value="5">5</option>
  <option value="10">10</option>
  <option value="25">25</option>
  <option value="100">100</option>
</select> miles of <input type=text size=40 name=from1> address
</td></tr>
<tr><td>or <select name="from">
<option value='201 N GOODWIN AVE, URBANA ,IL'>SIEBEL CENTER FOR COMPUTER SCIENCE</option>
<option value='301 N MATHEWS AVE, URBANA ,IL'>CIVIL ENGINEERING HYDROSYSTEMS LAB </option>
<option value='305 N MATHEWS AVE, URBANA ,IL'>NORTH CAMPUS CHILLER PLANT </option>
<option value='3515 S LINCOLN AVE, URBANA ,IL'>BEE RESEARCH FACILITY </option>
<option value='208 N WRIGHT ST, URBANA ,IL'>MICRO AND NANOTECHNOLOGY LABORATORY </option>
<option value='2102 S WRIGHT ST, CHAMPAIGN ,IL'>AGRONOMY SEED HOUSE </option>
<option value='2109 S OAK ST, CHAMPAIGN ,IL'>TECH DEV & FAB CENTER 2 </option>
<option value='1103 S SIXTH ST, CHAMPAIGN ,IL'>HARDING BAND BUILDING </option>
<option value='104 S WRIGHT ST, URBANA ,IL'>TALBOT LABORATORY </option>
<option value='1308 W GREEN ST, URBANA ,IL'>ENGINEERING HALL </option>
<option value='1401 W GREEN ST, URBANA ,IL'>ILLINI UNION </option>
<option value='1305 W GREEN ST, URBANA ,IL'>HARKER HALL </option>
<option value='1409 W GREEN ST, URBANA ,IL'>ALTGELD HALL </option>
<option value='1301 W GREEN ST, URBANA ,IL'>NATURAL HISTORY BUILDING </option>
<option value='1304 W GREEN ST, URBANA ,IL'>MATERIALS SCIENCE AND ENG BLDG </option>
<option value='1406 W GREEN ST, URBANA ,IL'>EVERITT ELEC & COMP ENGR LAB </option>
<option value='1114 W NEVADA ST, URBANA ,IL'>MUSIC BUILDING </option>
<option value='1206 S FOURTH ST, CHAMPAIGN ,IL'>HUFF HALL </option>
<option value='1105 W NEVADA ST, URBANA ,IL'>CHILD DEVELOPMENT LABORATORY </option>
<option value='1110 W GREEN ST, URBANA ,IL'>LOOMIS LABORATORY OF PHYSICS </option>
<option value='1007 W NEVADA ST, URBANA ,IL'>INST GOV & PUBLIC AFFAIRS BLDG </option>
<option value='1201 S FOURTH ST, CHAMPAIGN ,IL'>LUNDGREN HALL </option>
<option value='1203 S FOURTH ST, CHAMPAIGN ,IL'>CLARK HALL </option>
<option value='1205 S FOURTH ST, CHAMPAIGN ,IL'>BARTON HALL </option>
<option value='1207 S FOURTH ST, CHAMPAIGN ,IL'>FLAGG HALL </option>
<option value='1209 S FOURTH ST, CHAMPAIGN ,IL'>NOBLE HALL </option>
<option value='1213 S FOURTH ST, CHAMPAIGN ,IL'>TAFT HALL </option>
<option value='1111 W NEVADA ST, URBANA ,IL'>BUSEY HALL </option>
<option value='1206 W GREEN ST, URBANA ,IL'>MECHANICAL ENGINEERING BUILDING </option>
<option value='1115 W NEVADA ST, URBANA ,IL'>EVANS HALL </option>
<option value='1117 S OAK ST, CHAMPAIGN ,IL'>ABBOTT POWER PLANT </option>
<option value='1116 S OAK ST, CHAMPAIGN ,IL'>GEOLOGICAL SURVEY LABORATORY </option>
<option value='1107 W GREEN ST, URBANA ,IL'>STUDENT STAFF APTS #2-GREEN/GOODWIN </option>
<option value='1201 W NEVADA ST, URBANA ,IL'>AFRICAN AMERICAN STUDIES </option>
<option value='1206 S SIXTH ST, CHAMPAIGN ,IL'>WOHLERS HALL </option>
<option value='1310 S SIXTH ST, CHAMPAIGN ,IL'>EDUCATION BUILDING </option>
<option value='1609 S OAK ST, CHAMPAIGN ,IL'>CENTRAL RECEIVING BUILDING </option>
<option value='1207 S OAK ST, CHAMPAIGN ,IL'>REHABILITATION EDUCATION CENTER </option>
<option value='1010 W GREEN ST, URBANA ,IL'>DANIELS HALL </option>
<option value='1325 S OAK ST, CHAMPAIGN ,IL'>UNIVERSITY PRESS BUILDING </option>
</select> campus building</td>
</td></tr>

<tr>
<td>
Show Map: <input type="checkbox" name="map" checked> 
</td>
</tr>

<tr>
<td><input type=submit></td>
</tr>
</form>
</table>
<?php



include('distancelib.php');




$sql = "SELECT Tutor.id,Location FROM available, Tutor, Setting WHERE Tutor.id=available.UserID and available.SettingID=Setting.id";
$con = mysql_connect($server,$user,$pass);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db($db, $con);
$result = mysql_query($sql);
if (!$result ) {
    die('Could not connect: ' . mysql_error());
}


$arr = array();
$distances = array();
$coordinates = array();
$locations = array();

if($_POST["max"]=="") {
	$max=1;
} else {
	$max = $_POST["max"];
}
if($_POST["from"] == "") {
	$from = "1401 West Green Street, Urbana, IL 61801";
} else {
	$from = $_POST["from"];
}

array_push($coordinates, get_coords($from));
array_push($locations, $from);
while ($row = mysql_fetch_array($result)) {
    
    $location = $row['Location'];
    $id = $row['id'];
    
    
    $distance = get_distance($from, $location);
    
    
    
    if (within_range($distance,$max)) {
        array_push($arr, $id);
        array_push($coordinates, get_coords($location));
        array_push($distances, $distance);
        array_push($locations, $location);
    }
}

$ids="";
if (sizeof($arr)>0) {
    $ids=$arr[0];
}
for ($i = 1, $size = sizeof($arr); $i < $size; $i++) {
    $ids.=",".$arr[$i];
}



$emails = array();

if(isset($_POST["map"])) {
	echo "<img src=".get_map_url($coordinates).">";
}


$sql2 = "SELECT User.email FROM User WHERE id IN (".$ids.")";
$result = mysql_query($sql2);
if (!$result ) {
    die('Could not connect: ' . mysql_error());
}


while ($row = mysql_fetch_array($result)) {
    array_push($emails,$row['email']);
    
    
}

echo "<P>";
echo "from (".$from.") (green)<P>";
echo "displaying radius $max miles<P>";
?>

<TABLE border="1">
	<TH>#</TH>
	<TH>TutorID</TH>
	<TH>Distance</TH>
	<TH>Coordinates</TH>
	<TH>Address</TH>


<?php
foreach($arr as $i => $value) {
echo "<TR>";
	echo "<TD>".($i+1)."</TD>";
	echo "<TD>".$arr[$i]."</TD>";
	echo "<TD>".$distances[$i]. "miles</TD>";
	echo "<TD>(".$coordinates[$i+1][0].",".$coordinates[$i+1][1].")</TD>";
	echo "<TD>".$locations[$i+1]."</TD>";
	echo "</TR>";
}
?>
</TABLE>
<p>Directions Courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png"></p>