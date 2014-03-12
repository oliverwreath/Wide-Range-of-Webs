<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
include('header.php');
include('distancelib.php');



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


<h2>Advanced Search For Tutors Demo</h2>

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
	<td>
	<b>Show Map:</b> <input type="checkbox" name="map">
	</td>
	</tr>
	
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
	
	
	
	// views
	//CREATE ALGORITHM=UNDEFINED DEFINER=`fourgrad`@`localhost` SQL SECURITY DEFINER VIEW `TutorsOfferCourseVIEW` AS select distinct `offers`.`TutorID` AS `TutorID` from (((`Tutor` join `offers`) join `Course`) join `Department`) where ((`Tutor`.`id` = `offers`.`TutorID`) and (`offers`.`CRN` = `Course`.`CRN`) and (`Course`.`Major` = `Department`.`Code`));
	//CREATE ALGORITHM=UNDEFINED DEFINER=`fourgrad`@`localhost` SQL SECURITY DEFINER VIEW `TutorListingVIEW` AS select `Tutor`.`id` AS `id`,`User`.`email` AS `email`,`Tutor`.`language` AS `language`,`Tutor`.`education` AS `education`,`Tutor`.`EducationCode` AS `EducationCode`,`Tutor`.`price` AS `price`,`Tutor`.`avgRating` AS `avgRating`,`Department`.`Name` AS `DeptName`,`User`.`name` AS `UserName`,`Setting`.`Location` AS `Location` from ((((`Tutor` join `User`) join `Department`) join `available`) join `Setting`) where ((`Tutor`.`id` = `available`.`UserID`) and (`available`.`SettingID` = `Setting`.`id`) and (`Department`.`Code` = `Tutor`.`EducationCode`) and (`User`.`id` = `Tutor`.`id`));
	//CREATE ALGORITHM=UNDEFINED DEFINER=`fourgrad`@`localhost` SQL SECURITY DEFINER VIEW `TotalTutorTimeVIEW` AS (select `Setting`.`Time_Start` AS `Time_Start`,`Setting`.`Time_End` AS `Time_End`,`Tutor`.`id` AS `id`,sum((time_to_sec(timediff(`Setting`.`Time_End`,`Setting`.`Time_Start`)) / 60)) AS `minutes` from ((`Setting` join `available`) join `Tutor`) where ((`available`.`UserID` = `Tutor`.`id`) and (`available`.`SettingID` = `Setting`.`id`)) group by `Tutor`.`id`);
	
	
	//$sql = "SELECT * FROM `Department`,`offers`,`Tutor`,`User`,`Course`  WHERE User.id=Tutor.id AND Course.CRN = offers.CRN AND offers.TutorID = Tutor.id AND EducationCode = Code  ";
	//$sql="SELECT Tutor.id,email,language,education,EducationCode,price,avgRating,Department.Name,User.name,Location,SUM(TIME_TO_SEC(TIMEDIFF(Time_End,Time_Start))/60) AS minutes  From  `Tutor`,`User`,`Department`,`available`,`Setting` WHERE Time_End <= '".$period_pref1[1]."' AND Time_Start >= '".$period_pref1[0]."' AND Tutor.id=available.UserID AND available.SettingID=Setting.id AND Department.Code=Tutor.EducationCode AND User.id=Tutor.id AND Tutor.id IN(SELECT DISTINCT(TutorID) FROM `Tutor`,`offers`,`Course`,`Department` WHERE Tutor.id=offers.TutorID AND offers.CRN=Course.CRN AND Course.Major=Department.Code ";
	
	function run_results($timewanted, $period_pref1, $period_pref2, $range, $language, $name, $ctlDept, $ctlCourse, $educationlevel, $ratingMin, $ratingMax, $minprice, $maxprice, $from_bldg, $from_addr)
	{
	    echo "timewanted=".$timewanted."<BR>";
	    echo "periodpref1=".$period_pref1."<BR>";
	    echo "periodpref2=".$period_pref2."<BR>";
	    echo "range=".$range."<BR>";
	    echo "language=".$language."<BR>";
	    echo "name=".$name."<BR>";
	    echo "ctlDept=".$ctlDept."<BR>";
	    echo "ctlCourse=".$ctlCourse."<BR>";
	    echo "educationlevel=".$educationlevel."<BR>";
	    echo "ratingMin=".$ratingMin."<BR>";
	    echo "ratingMax=".$ratingMax."<BR>";
	    
	    $criteriaset = false;
	    
	    $from_clause = "`TutorListingVIEW` l ";
	    $where_clause = " WHERE  ";
	    
	    $sql=   "SELECT *  FROM ";
	    if ($timewanted!="" || $period_pref1!="" || $period_pref1!="") {
	        $from_clause .= " ,`TotalTutorTimeVIEW` t ";
	        $where_clause .= " l.id=t.id AND ";
	    }
	    if ($range!="") {
	        $from_clause .= ",`available`,`Setting`";
	        $where_clause .= " l.id=available.UserID AND Setting.id=available.SettingID AND ";
	    }
	    
	    if (isset($period_pref1) && $period_pref1 != "") {
	        $period_pref1 = map_pref_to_range($period_pref1);
	        $where_clause .= "t.Time_End <= '".$period_pref1[1]."' AND t.Time_Start >= '".$period_pref1[0]."' AND ";
	        $criteriaset=true;
	    }
	    
	    if (isset($language) && $language != "") {
	        $where_clause .= " l.language LIKE '%".$language."%' ";
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
	    
	    echo "<textarea>".$sql."</textarea>";
	    include('auth.php');
	    $con = mysql_connect($server,$user,$pass);
	    if (!$con) {
	        die('Could not connect: ' . mysql_error());
	    }
	    
	    mysql_select_db($db, $con);
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
	    <TH>????</TH>
	    <TH>????</TH>
	    <TH>????</TH>
	    <TH>????</TH>
	    <TH>????</TH>
	    <TH>????</TH>
	    <TH>????</TH>
	    <TH>????</TH>
	    <TH>????</TH>
	    <TH>????</TH>
	    <TH>????</TH>
	    
	    <TH>CHOOSE</TH>
	    
	    <?php
	    
	    $mapordinal=1;
	    
	    if ($range!="" && $from!="") {
	        while ($row = mysql_fetch_array($result)) {
	            $location = $row['Location'];
	            $distance=get_distance($from,$location);
	            
	            
	            if (within_range($distance,$range)) {
	                array_push($coordinates, get_coords($location));
	                
	                $username = $row['name'];
	                $email = $row['email'];
	                $minutes = $row['minutes'];
	                
	                
	                
	                
	                echo "<TR>";
	                echo "\t<TD>".$mapordinal . "</TD>\n";
	                echo "\t<TD>".$row[0] . "</TD>\n";
	                echo "\t<TD>".$row['UserName'] . "</TD>\n";
	                echo "\t<TD>".$row['email'] . "</TD>\n";
	                echo "\t<TD>".$row['language'] . "</TD>\n";
	                echo "\t<TD>".$row['education'] . "</TD>\n";
	                echo "\t<TD>".$row['DeptName'] . "</TD>\n";
	                echo "\t<TD>".$row['price'] . "</TD>\n";
	                echo "\t<TD>".$row['avgRating'] . "</TD>\n";
	                echo "\t<TD>".$distance . " miles</TD>\n";
	                echo "\t<TD>".$minutes . " minutes</TD>\n";
	                
	                echo "\t<TD><a href='profile.php?tutorid=".$row[0] . "'>Select Tutor</a></TD>\n";
	                echo "</TR>\n";
	                
	                $mapordinal++;
	            }
	        }
	    } else {
	        while ($row = mysql_fetch_array($result)) {
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
	            echo "\t<TD>".$row['minutes'] . "</TD>\n";
	            
	            echo "\t<TD><a href='profile.php?tutorid=".$row[0] . "'>Select Tutor</a></TD>\n";
	            echo "</TR>\n";
	            
	            
	        }
	    }
	    
	    echo "</TABLE>";
	    
	    mysql_close($con);
	    
	    
	    
	}
	if (isset($_POST["map"])&&$_POST["map"]!="") {
	    echo "<img src=".get_map_url($coordinates).">";
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
	
	
	echo "<h3>You may also want to consider these <i>Related Tutors</i>:</h3>";
	run_results(
	$_POST["timewanted"],
	$_POST["period_pref1"],
	$_POST["period_pref2"],
	$_POST["range"]*2,
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


?>