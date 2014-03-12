<?php
include('auth.php');
include('header.php');
include('myTutorLib.php');
?>

<h2>My Tutor</h2>


<link rel="stylesheet" type="text/css" href="slider.css">
<script type="text/javascript" src="addanevent.js"></script>
<script type="text/javascript" src="slider.js"></script>
<script type="text/javascript" src="slider-setup.js"></script>

<?php
$loggedin=isset($_SESSION['userid']);
if($loggedin)
{
?>
	<!--The slider bar javascript code was taken from  www.arantious.com-->
	<form action="" method="post">
	<table>
	<tr >
	<td>Department / Course:</td>
	<td><?php include("courseselector.php"); ?></td>
	
	<tr>
	<td>Price Relevance</td>
	<td>
	<div class="slider" id="slider01">
		<div class="left"></div>
		<div class="right"></div>
		<img src="img/knob.png" width="31" height="15" />
	</div></td>
	<td><input id="output1" name="priceRelevance"/></td>
	</tr>
	
	<tr>
	<td>Rating Relevance</td>
	<td>
	<div class="slider" id="slider02">
		<div class="left"></div>
		<div class="right"></div>
		<img src="img/knob.png" width="31" height="15" />
	</div></td>
	<td><input id="output2" name="ratingRelevance"/></td>
	</tr>
	
	<tr>
	<td>Language Relevance</td>
	<td>
	<div class="slider" id="slider03">
		<div class="left"></div>
		<div class="right"></div>
		<img src="img/knob.png" width="31" height="15" />
	</div></td>
	<td><input id="output3" name="languageRelevance" /></td>
	</tr>
	
	<tr>
	<td>Location Relevance</td>
	<td>
	<div class="slider" id="slider04">
		<div class="left"></div>
		<div class="right"></div>
		<img src="img/knob.png" width="31" height="15" />
	</div></td>
	<td><input id="output4" name="locationRelevance" /></td>
	</tr>
	
	<tr>
	<td>Feedback Relevance</td>
	<td>
	<div class="slider" id="slider05">
		<div class="left"></div>
		<div class="right"></div>
		<img src="img/knob.png" width="31" height="15" />
	</div></td>
	<td><input id="output5" name="feedbackRelevance" /></td>
	</tr>
	
	<tr>
	<td>Describe a good tutor:<td/></tr>
	<tr><td><input type="text" name="description" width="80"/></td>
	</tr>
	
	<tr ><td><input type="submit" text="Find my tutor" /></td></tr>
	</tr>
	
	</table>
	
	</form>
	
	<?php	
		if(isset($_POST['ctlCourse']) && $_POST['ctlCourse']!='-1') {
			$crn = $_POST["ctlCourse"];
			$priceRelevance = $_POST["priceRelevance"];
			$ratingRelevance = $_POST["ratingRelevance"];
			$locationRelevance = $_POST["locationRelevance"];
			$languageRelevance = $_POST["languageRelevance"];
			$feedbackRelevance = $_POST["feedbackRelevance"];
			$description = $_POST["description"];
			
			//print "The class is $crn with $priceRelevance $ratingRelevance $locationRelevance\n<hr>";
			
			// starting a connection with the database
			$connection = mysql_connect($server,$user,$pass);
			if (!$connection) {
				die('Could not connect: ' . mysql_error());
			}
			mysql_select_db($db, $connection);
			$user_id = $_SESSION['userid'];
			
			// finding the tutors available for that course
			$query = "SELECT Tutor.id,name,language,price,avgRating FROM `offers`,`Tutor`,`User` WHERE Tutor.id = offers.tutorID AND User.id = Tutor.id AND CRN='$crn'";
	    		$result = mysql_query($query);
	    		if(! $result){
	                	die('Query error: ' . mysql_error());
	            	}
	            	
	            	// creating the array with resulting tutors
	            	$tutorArray = array();
	            	$index = 1;
			while($row = mysql_fetch_array($result)){
				$tutorID = $row['id'];
				$tutorName = $row['name'];
	        		$tutorPrice = $row['price'];
	        		$tutorRating = $row['avgRating'];
	        		$tutorLanguage = $row['language'];
	        		
	        		$tutorArray[$index] = array('id' => $tutorID, 'name' => $tutorName, 'price' => $tutorPrice, 
	        		'rating' => $tutorRating, 'language' => $tutorLanguage, 'distance' => "", 'feedback' => 0, 'score' => 0);
	        		//print "Tutor $tutorID $tutorName with $tutorPrice $tutorRating\n<hr>";
	        		$index++;
	        	}
	        	
	        	// getting locations for the student
	        	$query = "SELECT Location FROM `available`,`Setting` WHERE UserID = $user_id AND available.SettingID = Setting.id";
	    		$result = mysql_query($query);
	    		if(! $result){
	                	die('Query error: ' . mysql_error());
	            	}
	            	
	            	// creating the array with resulting locations
	            	$studentLocations = array();
	            	$index = 1;
			while($row = mysql_fetch_array($result)){
				$studentLocations[$index] = $row['Location'];
				$index++;
	        	}
	        	
	        	// getting locations for each tutor
	        	foreach ($tutorArray as &$value) {
	        		$tutorID = $value['id'];
	        		$query = "SELECT Location FROM `available`,`Setting` WHERE UserID = $tutorID AND available.SettingID = Setting.id";
	    			$result = mysql_query($query);
	    			if(! $result){
	                		die('Query error: ' . mysql_error());
	            		}
	            	
	            		// creating the array with resulting tutors
	            		$tutorLocations = array();
	            		$index = 1;
				while($row = mysql_fetch_array($result)){
					$tutorLocations[$index] = $row['Location'];
					$index++;
	        		}
	        		
	        		// computing the shortest distance between student's and tutor's locations
	        		$tutorDistance = getMinDistance($studentLocations,$tutorLocations);
				
				$value['distance'] = $tutorDistance;
			}
	        	
	        	// computing max and min price
	        	$maxPrice = 0;
			foreach ($tutorArray as &$value) {
				$tutorPrice = $value['price'];
				if($tutorPrice > $maxPrice){
					$maxPrice = $tutorPrice;
				}
			}
			$minPrice = $maxPrice;
			//print "Max price is $maxPrice <hr>";
			foreach ($tutorArray as &$value) {
				$tutorPrice = $value['price'];
				if($tutorPrice < $minPrice){
					$minPrice = $tutorPrice;
				}
			}
			//print "Min price is $minPrice <hr>";
			foreach ($tutorArray as &$value) {
				$tutorPrice = $value['price'];
				$score = getLinearScore($minPrice, $maxPrice, $tutorPrice);
				$value['score'] = $value['score'] + (1 - $score) * $priceRelevance;
			}
			
			// computing max and min rating
	        	$maxRating = 0;
			foreach ($tutorArray as &$value) {
				$tutorRating = $value['rating'];
				if($tutorRating > $maxRating){
					$maxRating = $tutorRating;
				}
			}
			$minRating = $maxRating;
			foreach ($tutorArray as &$value) {
				$tutorRating = $value['rating'];
				if($tutorRating < $minRating){
					$minRating = $tutorRating;
				}
			}
			foreach ($tutorArray as &$value) {
				$tutorRating = $value['rating'];
				$score = getLinearScore($minRating, $maxRating, $tutorRating);
				$value['score'] = $value['score'] + $score * $ratingRelevance;
			}
	        	
	        	// getting student's language
	        	$query = "SELECT language FROM `Student` WHERE id = '$user_id'";
	    		$result = mysql_query($query);
	    		if(! $result){
	                	die('Query error: ' . mysql_error());
	            	}
			$row = mysql_fetch_array($result);
			$studentLanguage = $row['language'];
				
	        	// computing language score
	        	foreach ($tutorArray as &$value) {
				$tutorLanguage = $value['language'];
				if(strcasecmp($studentLanguage,$tutorLanguage) == 0)
					$score = 1;
				else
					$score = 0;
				$value['score'] = $value['score'] + $score * $languageRelevance;
			}
	     
	     		// computing location score
	     		$maxDistance = 0;
			foreach ($tutorArray as &$value) {
				$tutorDistance = $value['distance'];
				if($tutorDistance > $maxDistance){
					$maxDistance = $tutorDistance;
				}
			}
			$minDistance = $maxDistance;
			foreach ($tutorArray as &$value) {
				$tutorDistance = $value['distance'];
				if($tutorDistance < $minDistance){
					$minDistance = $tutorDistance;
				}
			}
			foreach ($tutorArray as &$value) {
				$tutorDistance = $value['distance'];
				$score = getLinearScore($minDistance, $maxDistance, $tutorDistance);
				$value['score'] = $value['score'] + $score * $locationRelevance;
			}
			
			// *** computing feeback score ***

			// getting list of keywords in description
			$descWords = preg_split("/[\s,]+/", $description);
			
			// transforming an array into a hash
			$keywords = array();

			foreach ($descWords as &$word) {
                                $counter++;
				$keywords['$word'] = 1;
			}
			
			// iterating over tutors and retrieving the corresponding feedbacks
			foreach ($tutorArray as &$value) {
				$tutorID = $value['id'];
				
				$query = "SELECT Feedback.Comment FROM `hires`,`Feedback` WHERE Feedback.ContractID = hires.ContractID AND hires.TutorID = $tutorID";
	    			$result = mysql_query($query);
	    			if(! $result){
	                		die('Query error: ' . mysql_error());
	            		}
	            	
	            		// creating the array with resulting words in feedback
	            		$terms = array();
				while($row = mysql_fetch_array($result)){
					$feedback = $row['Comment'];
					$words = preg_split("/[\s,]+/", $feedback);
					foreach ($words as &$word) {
						$terms['$word']++;
					}
				}
				
				// computing similarity 
				$match = intersection($keywords,$terms);
				$value['feedback'] = $match / $counter;
			}
			
			// computing feedback score
	     		$maxFeedback = 0;
			foreach ($tutorArray as &$value) {
				$tutorFeedback = $value['feedback'];
				$score = $tutorFeedback;
				$value['score'] = $value['score'] + $score * $feedbackRelevance;
			}
	     	   	
	        	// sorting the tutor array
	        	usort($tutorArray,"cmpScore");
			
			// printing ranking of tutors
			print "<table border=\"1\"> <tr> <th>Rank</th> <th>Name</th> <th>Score</th> <th>Choose</th> </tr>";
			$rank = 1;
			foreach ($tutorArray as &$value) {
				$tutorName = $value['name'];
				$tutorScore = $value['score'];
				$tutorID = $value['id'];
				//added create a contract link --jn
				print "<tr> <td>$rank </td> <td>$tutorName</td> <td>$tutorScore </td> <td><a href='profile.php?tutorid=$tutorID'>Select Tutor</a></td>";
				$rank++;
			}
			print "</table>";
			
			// closing connection with database
			mysql_close($connection);
			
		}
		
		
}
else
{
    print "You must be logged in to perform this operation";
}


?>