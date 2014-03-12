<?php
include('distancelib.php');

// constants
define('MAX_DISTANCE','10');

// Function to compare two arrays with respect to their scores
function cmpScore($a, $b)
{
    if ($a['score'] == $b['score']) {
        return 0;
    }
    return ($a['score'] < $b['score']) ? 1 : -1;
}

// Function to get a linearly distributed score between two extreme values
function getLinearScore($min, $max, $val)
{
	if($max == $min){
		return 1;
	} else {
		return ($val - $min)/($max - $min);
	}
}

// Function to compute the minimum distance between two lists of locations
function getMinDistance($student, $tutor)
{
	$minDistance = MAX_DISTANCE;
	foreach ($student as &$studentLoc) {
		//print "$studentLoc<br>";
		foreach ($tutor as &$tutorLoc){
			$distance = get_distance($studentLoc,$tutorLoc);
			//print "$distance $tutorLoc<br>";
			if($distance < $minDistance)
				$minDistance = $distance;
		}
	}
	return $minDistance;
}

// Function to determine the intersection between two lists of words
function intersection($keywords, $terms)
{
	$count = 0;
	foreach($keywords as $key => $value){
		if($terms[$key] > 0)
			$count++;
	}
	return $count;
}

?>