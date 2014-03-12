<?php

$cost_start="";
$cost_end="";
$department_of_course="";
$course_CRN="61549";
$language="";
$name="";
$rating_start="";
$rating_end="";
$distradius="";
$map="";
$minutes=0;
$time_pref="";

include('auth.php');
    $con = mysql_connect($server,$user,$pass);

    mysql_select_db($db, $con);


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




function similar_courses($crn) {

$sql = "SELECT CRN,Title FROM `Course` WHERE CRN='$crn'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$title = $row['Title'];



echo "original: <b>".$title."</b><P>";

$sql = "SELECT CRN,Title FROM `Course`";

$result = mysql_query($sql);

$title = strtolower($title);
	while($row = mysql_fetch_array($result))
	{
//		$lev = levenshtein ($title,strtolower($row['Title']));
		$lev = editdistance ($title,strtolower($row['Title']));
		if($lev<strlen($title)*0.35) {
		echo $row['Title']."(".$row['CRN'].") dist: ".$lev."<BR>"; }
	}

}




similar_courses("61549");
similar_courses("58477");

echo"reference: ".levenshtein ("University Physics: Elec & Mag","University Physics: Mechanics")." mine: ".editdistance("University Physics: Elec & Mag","University Physics: Mechanics");

?>

