<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
include('header.php');
include('distancelib.php');
include('auth.php');
$con = mysql_connect($server,$user,$pass);
mysql_select_db($db, $con);

if (!$con) {
	die('Could not connect: ' . mysql_error());
}



function map_pref_to_range($pref_code)
{
    
    $range = array("","");
    switch ($pref_code) {
    case MORNING:
        $range[0] = "06:00:00";
        $range[1] = "11:59:59";
        break;
    case AFTERNOON:
        $range[0] = "12:00:00";
        $range[1] = "17:59:59";
        break;
    case EVENING:
        $range[0] = "18:00:00";
        $range[1] = "23:59:59";
        break;
    case NIGHT:
        $range[0] = "00:00:00";
        $range[1] = "05:59:59";
        break;
        default:
        echo "none";
    }
    
    return $range;
    
}


?>


<h2>Advanced Search For Tutors</h2>

<?php
$loggedin=isset($_SESSION['userid']);
if($loggedin)
{
?>
	
	<form action="" method="post">
	<table>
	<tr >
	<td><h3>Basic Information</h3>Cost between($):</td>
	<td><input type="text" value="0" name="minprice" /> </td>
	<td>and</td>
	<td><input type="text" value="100" name="maxprice" /></td>
	</tr>
	<tr >
	<td>Department / Course:</td>
	<td colspan="3"><?php include("courseselector.php");
	?></td>
	</tr>
	
	<tr >
	<td>Tutor Education:</td>
	<td><select name="educationlevel"><option value="-1">Select Degree:</option>
	<option value="Master">Master</option><option value="PhD">PhD</option>
	<option value="Bachelor">Bachelor</option></select></td
	</tr>
	
	</tr>
	
	
	
	<tr>
	<td><h3>Advanced Information</h3>Language</td>	<td><input type="text" value="English" name="language"/></td>
	</tr>
	<tr>
	<td>Name Contains</td>	<td><input type="text" value="" name="name"/></td>
	</tr>
	<tr>
	<!--	<td>Tutor Major</td>	<td colspan="3"><?php include("courseselector.php");
	?></td>-->
	</tr>
	<tr>
	<td>Rating between</td>	<td><input type="text" value="0" name="ratingMin"/></td>	<td>And</td>	<td><input type="text" value="9" name="ratingMax"/></td>
	</tr>
	
	
	
	<tr>
	<td>
	<h3>Distance</h3>
	Within radius distance of
	<select name="range">
	<option value=""></option>
	<option value="0.5">0.5</option>
	<option value="1">1</option>
	<option value="5">5</option>
	<option value="10">10</option>
	<option value="25">25</option>
	<option value="100">100</option>
	</select>miles of </td>
	<td colspan="3">
	<input type=text size=40 name=from_addr> address OR
	</td>
	</tr>
	<tr>
	<td></td>
	<td colspan="3"><select name="from_bldg">
	<option value=''></option>
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
	</select> campus building
	</td></tr>
	

	
	<tr>
	
	</tr>
	
	<tr>
	
	<td><h3>Time</h3>
	I need minutes of time with tutor:</td>
	<td colspan="3"><input type=text name=timewanted> minutes</td></tr>
	<tr>
	<td>My first time preference is in the</td>
	<td colspan="3"><select name="period_pref1">
	<option value="">Select Time of Day</option>
	<option value="<?php echo MORNING; ?>">Morning</option>
	<option value="<?php echo AFTERNOON; ?>">Afternoon</option>
	<option value="<?php echo EVENING; ?>">Evening</option>
	<option value="<?php echo NIGHT; ?>">Night</option>
	</select>
	</td></tr>
	<tr>
	<td>My second time preference is in the</td>
	<td colspan="3"><select name="period_pref2">
	<option value="">Select Time of Day</option>
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
	
	function run_results($timewanted, $period_pref1, $period_pref2, $range, $language, $name, $ctlDept, $ctlCourse, $educationlevel, $ratingMin, $ratingMax, $minprice, $maxprice, $from_bldg, $from_addr)
	{
	    $criteriaset = false;
	    
	    $from_clause = "`TutorListingVIEW` l ";
	    $where_clause = " WHERE  TRUE ";
	    
	    $sql=   "SELECT *  FROM ";
	    if ($timewanted!="" || $period_pref1!="" || $period_pref1!="") {
	        $from_clause .= " ,`TotalTutorTimeVIEW` t ";
	        $where_clause .= " AND l.id=t.id  ";
	    }
	    if ($range!="") {
	        $from_clause .= ",`available`,`Setting`";
	        $where_clause .= " AND l.id=available.UserID AND Setting.id=available.SettingID  ";
	    }
	    
	    if (isset($period_pref1) && $period_pref1 != "") {
	        $period_pref1 = map_pref_to_range($period_pref1);
	        $where_clause .= " AND t.Time_End <= '".$period_pref1[1]."' AND t.Time_Start >= '".$period_pref1[0]."'  ";
	        $criteriaset=true;
	    }
	    
	    if (isset($language) && $language != "") {
	        $where_clause .= " AND l.language LIKE '%".$language."%' ";
	    }
	    
	    if (isset($name) && $name != "" ) {
	        $where_clause .= " AND l.UserName LIKE '%".$name."%' ";
	    }
	    
	    
	    
	    $sql .= $from_clause;
	    $sql .= $where_clause;
	    
	    $sql .= " AND l.id IN ( ";
	    
	    
	    $sql .= "SELECT DISTINCT(TutorID) FROM `Tutor`,`offers`,`Course`,`Department`,`Setting`,`available` WHERE  Tutor.id=offers.TutorID AND offers.CRN=Course.CRN AND Course.Major=Department.Code ";
	    
	    if (isset($minprice) &&
	    isset($maxprice) &&
	    $maxprice!=""&&$minprice!=""
	    ) {
	        
	        $sql .= " AND price<$maxprice AND price>=$minprice";
	        $criteriaset=true;
	    }
	    if (isset($ctlDept) && $ctlDept!='-1') {
	        $sql .= " AND Course.Major='$ctlDept'";
	        $criteriaset=true;
	    }
	    
	    if (isset($ctlCourse) && $ctlCourse!='-1') {
	        $ctlCourse = $ctlCourse;
	        $sql .= " AND offers.CRN='$ctlCourse'";
	        
	        $criteriaset=true;
	    }
	    
	    if (isset($educationlevel) && $educationlevel!='-1') {
	        $sql .= " AND Tutor.education='$educationlevel'";
	        $criteriaset=true;
	    }
	    
	    
	    if (isset($ratingMin) && isset($ratingMax)) {
	        $sql .= " AND Tutor.avgRating BETWEEN ".$ratingMin." AND ".$ratingMax." ";
	    }
	    
	    
	    
	    if (!$criteriaset) {
	        $sql .= " AND FALSE";
	    }
	    
	    $sql .=") ";
	    if (isset($timewanted)&&$timewanted!="") {
	        $sql .=" GROUP BY l.id HAVING minutes>=".$timewanted;
	        
	    }
	    include('auth.php');

		echo "<!-- $sql -->";
		
	    $result = mysql_query($sql);
	    if (!$result ) {
	        die('Could not connect: ' . mysql_error());
	    }
	    
	    
	
	    $coordinates = array();
	    
	    
	    
	    
	    if ($from_addr == "" && $from_bldg == "") {
	        $from = "";
	    } else if ($from_addr != "" && $from_bldg == "") {
	        $from = $from_addr;
	    } else if ($from_addr == "" && $from_bldg != "") {
	        $from = $from_bldg;
	    }
	    
	    array_push($coordinates, get_coords($from));
	    
	    

	    
	    
		?>
	    <TABLE border="1">
	    <TH>ID#</TH>
	    <TH>Name</TH>
	    <TH>Email</TH>
	    <TH>Language</TH>
	    <TH>Education Level</TH>
	    <TH>Major</TH>
	    <TH>Price</TH>
	    <TH>Avg Rating</TH>
	    <TH>Distance (mi.)</TH>
	    <TH>Time (min.)</TH>
	    
	    <TH>CHOOSE</TH>
<?php
	        while ($row = mysql_fetch_array($result)) {
	            $location = $row['Location'];
	            
				$range_check = true;
				
	            if($range != "") {
					$distance = get_distance($from,$location);
					$range_check = within_range($distance,$range);
				}
				
	            if ($range_check) {
	                
	                $username = $row['name'];
	                $email = $row['email'];
	                $minutes = $row['minutes'];
	                
	                
	                
	                
	                echo "<TR>";
	                echo "\t<TD>".$row[0] . "</TD>\n";
	                echo "\t<TD>".$row['UserName'] . "</TD>\n";
	                echo "\t<TD>".$row['email'] . "</TD>\n";
	                echo "\t<TD>".$row['language'] . "</TD>\n";
	                echo "\t<TD>".$row['education'] . "</TD>\n";
	                echo "\t<TD>".$row['DeptName'] . "</TD>\n";
	                echo "\t<TD>".$row['price'] . "</TD>\n";
	                echo "\t<TD>".$row['avgRating'] . "</TD>\n";
	                echo "\t<TD>".$distance . " </TD>\n";
	                echo "\t<TD>".$minutes . " </TD>\n";
	                
	                echo "\t<TD><a href='profile.php?tutorid=".$row[0] . "'>Select Tutor</a></TD>\n";
	                echo "</TR>\n";
	                
	            }
	        }

	    
	    echo "</TABLE>";
	    
	    
	    
	    
	}
	


	
	
	
	
	
	$original_result=run_results(
	$_POST["timewanted"],
	$_POST["period_pref1"],
	$_POST["period_pref2"],
	$_POST["range"],
	$_POST["language"],
	$_POST["name"],
	$_POST["ctlDept"],
	$_POST["ctlCourse"],
	$_POST["educationlevel"],
	$_POST["ratingMin"],
	$_POST["ratingMax"],
	$_POST["minprice"],
	$_POST["maxprice"],
	$_POST["from_bldg"],
	$_POST["from_addr"]
	);
	

	
	
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$finish = $time;
	$total_time = round(($finish - $start), 4);
	echo 'Page generated in '.$total_time.' seconds.';
	
}
else
{
    print "You must be logged in to perform this operation";
}

mysql_close($con);
?>