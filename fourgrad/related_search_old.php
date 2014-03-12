

<?php

define("MORNING",1);
define("AFTERNOON",2);
define("EVENING",3);
define("NIGHT",4);

define("PRICE_MAX_MULTIPLIER",1.3);
define("PRICE_MIN_MULTIPLIER",0.5);
define("RELATEDNESS_TOLERANCE",0.35);

function editdistance($s, $t)
// Edit Distance algorithm to find distance between two strings.
{
    $m=strlen($s)-1;
    $n=strlen($t)-1;
    
    $s_arr = str_split($s);
    $t_arr = str_split($t);
    
    
    for ($i=0; $i<=$m; $i++) {
        $distance[$i][0] = $i;
    }
    
    for ($j=0; $j<=$n; $j++) {
        $distance[0][$j] = $j;
    }
    
    for ($j=1; $j<=$n; $j++) {
        for ($i=1; $i<=$m; $i++) {
            if ($s_arr[$i] == $t_arr[$j]) {
                $distance[$i][$j] = $distance[$i-1][$j-1];
            } else {
                $distance[$i][$j] = min($distance[$i-1][$j]+1,
				            $distance[$i][$j-1]+1,
				            $distance[$i-1][$j-1]+1);
            }
        }
    }
    return $distance[$m][$n];
}


function show_related_courses($crn_list) {

$related_CRNs = array();

$sql = "SELECT CRN,Title FROM `Course` WHERE CRN = 99999 ";
foreach($crn_list as $crn) {
	$sql .= " OR CRN='$crn' ";
}


$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$title = $row['Title'];



echo "original: <b>".$title."</b><P>";

$sql = "SELECT CRN,Title FROM `Course`";

$result = mysql_query($sql);

$title = strtolower($title);
	while($row = mysql_fetch_array($result))
	{
		$lev = editdistance ($title,strtolower($row['Title']));
		if($lev<strlen($title)*RELATEDNESS_TOLERANCE) {
			echo $row['Title']."(".$row['CRN'].") dist: ".$lev."<BR>";
			array_push($related_CRNs, $row['CRN']);

		}
	}

	return $related_CRNs;
}



function var_set($var) {
	return isset($var) && $var != "";
}

function show_html_table($matrix) {
	echo "<PRE>";
	print_r ( $matrix );
	echo "</PRE>";
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

function search_results($price_min, $price_max, $timewanted, $period_pref1, $period_pref2,
                        $range, $language, $name, $ctlDept, $ctlCourse, $educationlevel, $ratingMin, $ratingMax) {


echo "price_min->".$price_min."<BR>";
echo "price_max->".$price_max."<BR>";
echo "timewanted->".$timewanted."<BR>";
echo "period_pref1->".$period_pref1."<BR>";
echo "period_pref2->".$period_pref2."<BR>";
echo "range->".$range."<BR>";
echo "language->".$language."<BR>";
echo "name->".$name."<BR>";
echo "ctlDept->".$ctlDept."<BR>";
echo "ctlCourse->".$ctlCourse."<BR>";
echo "educationlevel->".$educationlevel."<BR>";
echo "ratingMin->".$ratingMin."<BR>";
echo "ratingMax->".$ratingMax."<BR>";




    include('auth.php');



    $where_clause = "";
    $from_clause = "`Department`,`offers`,`Tutor`,`User`,`Course`";
    $join_clause = " User.id=Tutor.id AND Course.CRN = offers.CRN AND offers.TutorID = Tutor.id AND Department.Code = Tutor.EducationCode";

    // Set meeting time preference
    if ( var_set($period_pref1) &&
         var_set($period_pref2) ) {

	 //// NEED TO FILL IT IN
        echo map_pref_to_range($period_pref1);
        echo map_pref_to_range($period_pref2);


    }

    // Set minutes wanted with tutor
    if ( var_set($timewanted) ) {
        $from_clause .= ", TotalTutorTimeVIEW time ";
	 $where_clause .= " time.id=Tutor.id ";
 	 $where_clause .= " AND ";
	 $where_clause .= " minutes >= $timewanted ";
 	 $where_clause .= " AND ";
    }

    // Set course CRN for tutoring
    if( var_set($ctlCourse) )  {
	$where_clause .= "offers.CRN = $ctlCourse";
    }

    // Set dept of course for tutoring
    if( var_set($ctlDept) )  {
       $where_clause .= "Course.Major = '$ctlDept'";
	$where_clause .= " AND ";
    }

    // Set price range.
    if( var_set($price_min) &&
        var_set($price_max))  {
	$where_clause .= " price BETWEEN $price_min AND $price_max ";
	$where_clause .= " AND ";
    }

    // Set name substring.
    if( var_set($name))  {
	$where_clause .= " User.name LIKE '%$name%'";
	$where_clause .= " AND ";
    }

    // Set language.
    if( var_set($language) ) {
        $where_clause .= " language = '$language' ";
      	 $where_clause .= " AND ";
    }

    // Set educationlevel.
    if( var_set($educationlevel) ) {
        $where_clause.= " education = '$educationlevel' ";
        $where_clause .= " AND ";
    }

    // Set average rating.
    if( var_set($ratingMin) &&
        var_set($ratingMax) ) {
        $where_clause .= " avgRating BETWEEN $ratingMin AND $ratingMax ";
        $where_clause.= " AND ";
    }

    // Set tutor's firstname/surname
    if( var_set($name) ) {
        $where_clause.= " User.name LIKE '%$name%' ";
        $where_clause .= " AND ";
    }
   
    $sql = "SELECT * FROM " . $from_clause . " WHERE  " . $where_clause . $join_clause;


echo $sql;

    //$con = mysql_connect($server,$user,$pass);
    if (!$con) {
        die('Could not connect: ' . mysql_error());
    }
    
    mysql_select_db($db, $con);
    $result = mysql_query($sql);
    if (!$result ) {
        die('Could not connect: ' . mysql_error());
    }

    while($r[]=mysql_fetch_array($result));


    
    return $r;
    
    mysql_close($con);

}

function expand_query ($price_min, $price_max, $timewanted, $period_pref1, $period_pref2,
                        $range, $language, $name, $ctlDept, $ctlCourse, $educationlevel, $ratingMin, $ratingMax) {

	if( var_set($price_min) && var_set($price_max) ) {
		$price_range = expand_price_range($price_min, $price_max);
		$price_min = $price_range[0];
		$price_max = $price_range[1];
	}

	if( var_set($period_pref1) && var_set($period_pref2) ) {
		//expand_time_preference();
	}



	return search_results($price_min, $price_max, $timewanted, $period_pref1, $period_pref2,
                             $range, $language, $name, $ctlDept, $ctlCourse, $educationlevel, $ratingMin, $ratingMax);

}

function expand_price_range($price_min, $price_max) {
	$price_min = $price_min * PRICE_MIN_MULTIPLIER;
	$price_max = $price_max * PRICE_MAX_MULTIPLIER;
	return array($price_min, $price_max);
}

function expand_time_preference() {
}

function expand_course_search() {
}




$plain_results = search_results(10,15,0,MORNING,AFTERNOON,5,"English","","CHEM","53762","",0,9);

$plain_CRNs = array();
foreach($plain_results as $row) {
	$crn = $row["CRN"];
	if(!in_array($crn, $plain_CRNs)) {
		array_push($plain_CRNs, $crn);
	}
}


echo "PLAIN CRNs: ";
print_r ($plain_CRNs);

//$related_CRNs = show_related_courses($plain_CRNs);

	echo "<PRE>";
foreach($related_CRNs as $crn) {
	echo $crn."\n";
}
	echo "</PRE>";

show_html_table($plain_results);


//$expanded_results = expand_query(10,15,0,MORNING,AFTERNOON,5,"English","","CHEM","","",0,9);
//show_html_table($expanded_results);



?>