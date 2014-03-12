<?php
/*
Illini Tutor Search
Related Search, Jurand Nogiec
Spring 2012, UIUC CS411

*/


include('header.php');
include('auth.php');
$con = mysql_connect($server, $user, $pass);
mysql_select_db($db, $con);
    if (!$con) {
        die('Could not connect: ' . mysql_error());
    }
	
define("DEBUG", false);

define("MORNING", 1);
define("AFTERNOON", 2);
define("EVENING", 3);
define("NIGHT", 4);

// Fuzzy search algorithm modifiers
define("PRICE_MAX_MULTIPLIER"	,	1.3);
define("PRICE_MIN_MULTIPLIER"	,	0.5);
define("RELATEDNESS_TOLERANCE"	,	0.400);
define("TIME_SCALE_FACTOR"		,	1.500);
define("TIME_PREFERENCE_MODIFIER",	2 * 60);
define("PRICE_MIN_SCALE_FACTOR",	0.500);
define("PRICE_MAX_SCALE_FACTOR",	1.500);
///////////////////////////////////////////

/********************************/
/*** Similar course functions ***/
function remove_string($input)
{
    $bad_words = array(
        "I",
        "II",
        "III",
        "IV",
        "V",
        "study",
        "beginner",
		"basic",
        "intermediate",
        "advanced",
        "adv",
        "sem",
        "introduction",
        "intro",
		"introductory",
		"elementary",
        "special",
        "to",
        "in",
        "&",
        "from",
        "and",
        "the",
        "for",
        "or",
        ":",
        ",",
        "."
    );
    
    $output = "";
    $tok    = strtok($input, " ");
    while ($tok != false) {
        $tok_found = false;
        foreach ($bad_words as $word) {
            if ($tok == $word) {
                $tok_found = true;
            }
        }
        if (!$tok_found) {
            $output .= $tok . " ";
        }
        $tok = strtok(" ");
    }
    
    
    return $output;
}

function edit_similarity($str1, $str2, $distance)
{
    $str1_length           = strlen($str1);
    $str2_length           = strlen($str2);
    $max_levenshtein_score = max($str1_length, $str2_length);
    
    return $distance / ($max_levenshtein_score);
}
function editdistance($str1, $str2)
// Levenshtein ("Edit Distance") dynamic programming algorithm to find distance between two strings.
// idea from "Algorithm Design" book, Tardos
// We prefer insertions (e.g. characters added to course title)
{
    $deletion_weight     = 1;
    $insertion_weight    = 2;
    $substitution_weight = 1;
    
    $str1_length = strlen($str1) - 1;
    $str2_length = strlen($str2) - 1;
    $str1_arr    = str_split($str1);
    $str2_arr    = str_split($str2);
    for ($row = 0; $row <= $str1_length; $row++) {
        $distance[$row][0] = $row;
    }
    for ($col = 0; $col <= $str2_length; $col++) {
        $distance[0][$col] = $col;
    }
    for ($col = 1; $col <= $str2_length; $col++) {
        for ($row = 1; $row <= $str1_length; $row++) {
            if ($str1_arr[$row] == $str2_arr[$col]) {
                $distance[$row][$col] = $distance[$row - 1][$col - 1] + 0;
                // No change to character
                
            } else {
                $distance[$row][$col] = min($distance[$row - 1][$col] + $deletion_weight, // Character deletion
                    $distance[$row][$col - 1] + $deletion_weight, // Character insertion
                    $distance[$row - 1][$col - 1] + $substitution_weight);
                // Character substitutions
            }
        }
    }
    $similar_pct = edit_similarity($str1, $str2, $distance[$str1_length][$str2_length]);
    return $similar_pct;
}
function generate_related_CRNs($crn_list)
{
    $related_CRNs = array();
    
    $sql = "SELECT CRN,Title FROM `Course` WHERE CRN = 99999 ";
    foreach ($crn_list as $crn) {
        $sql .= " OR CRN='$crn' ";
    }

    $result = mysql_query($sql);
    while ($input_row = mysql_fetch_array($result)) {
        $original_title = $input_row['Title'];
        
        $sql            = "SELECT CRN,Title FROM `Course`";
        $related_result = mysql_query($sql);
        $title          = strtolower($title);
        echo "Showing tutors who offer courses with similar title to <b>" . $original_title . "</b>:<BR>";
        echo "<TEXTAREA rows=5 cols=60>\n";
        while ($row = mysql_fetch_array($related_result)) {
            $lev = editdistance(remove_string(strtolower($original_title)), remove_string(strtolower($row['Title'])));
            if ($lev < RELATEDNESS_TOLERANCE) {
                array_push($related_CRNs, $row['CRN']);
                echo $row["Title"] . "\n";
            }
        }
        echo "</TEXTAREA><BR>\n";
        
    }
    return $related_CRNs;
    
}

/******************************************/
/*** Fuzzy criteria parameter expansion ***/
function expand_price($price_range)
{
    if (var_set($price_range[0]) && var_set($price_range[1])) {
        $min = $price_range[0] * PRICE_MIN_SCALE_FACTOR;
        $max = $price_range[1] * PRICE_MAX_SCALE_FACTOR;
        return array(
            $min,
            $max
        );
    }
    return array(
        "",
        ""
    );
}
function expand_time_wanted($time)
{
    if (var_set($time)) {
        return $time * TIME_SCALE_FACTOR;
    }
    return "";
}

function expand_education_level($in_level)
{
    $level = array(
        "",
        ""
    );
    if ($in_level == "Master") {
        $level = array(
            "Bachelor",
            "Master"
        );
    } else if ($in_level == "Bachelor") {
        $level = array(
            "Bachelor"
        );
    } else if ($in_level == "PhD") {
        $level = array(
            "Master",
            "PhD"
        );
    }
    
    return $level;
}
function expand_rating($rating_range)
{
    $RATING_SCALE_FACTOR = 0.500;
    if (var_set($rating_range[0]) && var_set($rating_range[1])) {
        $min = max(0, $rating_range[0] * $RATING_SCALE_FACTOR);
        $max = min($rating_range[1] / $RATING_SCALE_FACTOR, 9);
        
        return array(
            $min,
            $max
        );
    }
    return array(
        "",
        ""
    );
}

/*************************/
/*** Utility functions ***/
function var_set($var)
{
    return isset($var) && $var != "" && $var != "-1";
}
function time_pref_to_range($pref_code)
{
    $range = array(
        "",
        ""
    );
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

function show_html_table($matrix)
{
    echo "<TABLE border=1>";
    echo "<TH>ID</TH>";
    echo "<TH>Name</TH>";
    echo "<TH>Education</TH>";
    echo "<TH>Language</TH>";
    echo "<TH>Price</TH>";
    echo "<TH>Avg Rating</TH>";
    echo "<TH>EMail</TH>";
    echo "<TH>CRN</TH>";
    echo "<TH>Major</TH>";
    echo "<TH>Number</TH>";
    echo "<TH>Title</TH>";
    echo "<TH>Choose</TH>";
    
    
    
    foreach ($matrix as $row) {
        if (var_set($row[0])) {
            echo "<TR>";
            echo "<TD>" . $row["TutorID"] . "</TD>";
            echo "<TD>" . $row["name"] . "</TD>";
            echo "<TD>" . $row["education"] . ", " . $row["Name"] . "</TD>";
            echo "<TD>" . $row["language"] . "</TD>";
            echo "<TD>" . $row["price"] . "</TD>";
            echo "<TD>" . $row["avgRating"] . "</TD>";
            echo "<TD>" . $row["email"] . "</TD>";
            echo "<TD>" . $row["CRN"] . "</TD>";
            echo "<TD>" . $row["Major"] . "</TD>";
            echo "<TD>" . $row["Number"] . "</TD>";
            echo "<TD>" . $row["Title"] . "</TD>";
            if (var_set($row["minutes"])) {
                echo "<TD>" . round($row["minutes"]) . " min.</TD>";
            }
            if (var_set($row["Time_Start"]) && var_set($row["Time_End"])) {
                echo "<TD>" . $row["Time_Start"] . " to " . $row["Time_End"] . "</TD>";
            }
			echo "<TD>". "<a href='profile.php?tutorid=".$row["TutorID"] . "'>Select Tutor</a></TD>\n";
            echo "</TR>";
        }
    }
    
    echo "</TABLE>";
    
}

function search_results($price_min, $price_max, $timewanted, $period_pref1, $range, $language, $name, $ctlDept, $ctlCourse, $educationlevel, $ratingMin, $ratingMax)
{
    $criteria_set = false;
    include('auth.php');
    
    $where_clause = "";
    $from_clause  = "`Department`,`offers`,`Tutor`,`User`,`Course`";
    $join_clause  = " User.id=Tutor.id AND Course.CRN = offers.CRN AND offers.TutorID = Tutor.id AND Department.Code = Tutor.EducationCode";
    
    if (var_set($period_pref1) || var_set($timewanted)) {
        $from_clause .= ", TotalTutorTimeVIEW time ";
        $where_clause .= " time.id=Tutor.id ";
        $where_clause .= " AND ";
    }
    // Set meeting time preference
    if (var_set($period_pref1)) {
        $criteria_set = true;
        
        $period = time_pref_to_range($period_pref1);
        echo "period:" . $period[0] . "period";
        echo " time.Time_End <= '" . $period[1] . "' AND time.Time_Start >= '" . $period[0] . "'";
        $where_clause .= " time.Time_End <= '" . $period[1] . "' AND time.Time_Start >= '" . $period[0] . "'";
        $where_clause .= " AND ";
    }
    
    // Set minutes wanted with tutor
    if (var_set($timewanted)) {
        $criteria_set = true;
        
        $where_clause .= " minutes >= $timewanted ";
        $where_clause .= " AND ";
    }
    
    if (sizeof($ctlCourse) != 0 && $ctlCourse[0] != "" && $ctlCourse[0] != -1) {
        $criteria_set = true;
        $where_clause .= "( offers.CRN = 99999 ";
        foreach ($ctlCourse as $crn) {
            $where_clause .= " OR offers.CRN = $crn ";
            
        }
        $where_clause .= ") AND ";
    }
    
    // Set dept of course for tutoring
    if (var_set($ctlDept)) {
        $criteria_set = true;
        $where_clause .= "Course.Major = '$ctlDept'";
        $where_clause .= " AND ";
    }
    
    // Set price range.
    if (var_set($price_min) && var_set($price_max)) {
        $criteria_set = true;
        $where_clause .= " price BETWEEN $price_min AND $price_max ";
        $where_clause .= " AND ";
    }
    
    // Set name substring.
    if (var_set($name)) {
        $criteria_set = true;
        $where_clause .= " User.name LIKE '%$name%'";
        $where_clause .= " AND ";
    }
    
    // Set language.
    if (var_set($language)) {
        $criteria_set = true;
        $where_clause .= " language = '$language' ";
        $where_clause .= " AND ";
    }
    
    // Set educationlevel.
    if ($educationlevel[0] != -1 && $educationlevel[0] != "") {
        $criteria_set = true;
        $where_clause .= "( education = 'NONE' ";
        foreach ($educationlevel as $level) {
            $where_clause .= " OR education = '$level' ";
            
        }
        $where_clause .= ") AND ";
    }
    
    // Set average rating.
    if (var_set($ratingMin) && var_set($ratingMax)) {
        $criteria_set = true;
        $where_clause .= " avgRating BETWEEN $ratingMin AND $ratingMax ";
        $where_clause .= " AND ";
    }
    
    // Set tutor's firstname/surname
    if (var_set($name)) {
        $criteria_set = true;
        $where_clause .= " User.name LIKE '%$name%' ";
        $where_clause .= " AND ";
    }
    
    if ($criteria_set) {
        $sql = "SELECT * FROM " . $from_clause . " WHERE  " . $where_clause . $join_clause;
    } else {
        return array();
    }
    
    
    

    
    $result = mysql_query($sql);
    if (!$result) {
        die('Could not connect: ' . mysql_error());
    }
    
    while ($r[] = mysql_fetch_array($result)) {
        ;
    }
    
    return $r;
}

function show_search_widget()
{
?>
    <form action="" method="post">
      <table>
        <tr>
          <td>
            <h3>
              Basic Information
            </h3>Cost between($):
          </td>
          <td>
            <input type="text" value="" name="minprice">
          </td>
          <td>
            and
          </td>
          <td>
            <input type="text" value="" name="maxprice">
          </td>
        </tr>
        <tr>
          <td>
            Department / Course:
          </td>
          <td colspan="3">
            <script type="text/javascript" src=
            "http://code.jquery.com/jquery.min.js">
</script> <script type="text/javascript" charset="utf-8">
//based on http://remysharp.com/2007/01/20/auto-populating-select-boxes-using-jquery-ajax/
            $(function(){
            $("select#ctlDept").change(function(){
            $.getJSON("coursesjson.php",{
            id: $(this).val()}
            , function(j){
                var options = '';
                for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '<\/option>';
                }
                $("#ctlCourse").html(options);
                $('#ctlCourse option:first').attr('selected', 'selected');
            }
            )
            }
            )
            }
            )
            </script> <select id="ctlDept" name="ctlDept">
              <option value="-1">
                Select Department
              </option>
              <option value="AAS">
                AAS - Asian American Studies
              </option>
              <option value="ABE">
                ABE - Agricultural and Biological Engineering
              </option>
              <option value="ACCY">
                ACCY - Accountancy
              </option>
              <option value="ACE">
                ACE - Agricultural and Consumer Economics
              </option>
              <option value="ACES">
                ACES - Agricultural Consumer and Environmental Sciences
              </option>
              <option value="ADV">
                ADV - Advertising
              </option>
              <option value="AE">
                AE - Aerospace Engineering
              </option>
              <option value="AFAS">
                AFAS - Air Force Aerospace Studies
              </option>
              <option value="AFRO">
                AFRO - African American Studies
              </option>
              <option value="AFST">
                AFST - African Studies
              </option>
              <option value="AGCM">
                AGCM - Agricultural Communications
              </option>
              <option value="AGED">
                AGED - Agricultural Education
              </option>
              <option value="AHS">
                AHS - Applied Health Sciences Courses
              </option>
              <option value="AIS">
                AIS - American Indian Studies
              </option>
              <option value="ANSC">
                ANSC - Animal Sciences
              </option>
              <option value="ANTH">
                ANTH - Anthropology
              </option>
              <option value="ARAB">
                ARAB - Arabic
              </option>
              <option value="ARCH">
                ARCH - Architecture
              </option>
              <option value="ART">
                ART - Art
              </option>
              <option value="ARTD">
                ARTD - Art--Design
              </option>
              <option value="ARTE">
                ARTE - Art--Education
              </option>
              <option value="ARTF">
                ARTF - Art--Foundation
              </option>
              <option value="ARTH">
                ARTH - Art--History
              </option>
              <option value="ARTS">
                ARTS - Art--Studio
              </option>
              <option value="ASST">
                ASST - Asian Studies
              </option>
              <option value="ASTR">
                ASTR - Astronomy
              </option>
              <option value="ATMS">
                ATMS - Atmospheric Sciences
              </option>
              <option value="AVI">
                AVI - Aviation
              </option>
              <option value="BADM">
                BADM - Business Administration
              </option>
              <option value="BASQ">
                BASQ - Basque
              </option>
              <option value="BIOC">
                BIOC - Biochemistry
              </option>
              <option value="BIOE">
                BIOE - Bioengineering
              </option>
              <option value="BIOL">
                BIOL - Biology
              </option>
              <option value="BIOP">
                BIOP - Biophysics
              </option>
              <option value="BMNA">
                BMNA - Bamana
              </option>
              <option value="BTW">
                BTW - Business and Technical Writing
              </option>
              <option value="BULG">
                BULG - Bulgarian
              </option>
              <option value="BUS">
                BUS - Business
              </option>
              <option value="CAS">
                CAS - Center for Advanced Study
              </option>
              <option value="CATL">
                CATL - Catalan
              </option>
              <option value="CB">
                CB - Comparative Biosciences
              </option>
              <option value="CDB">
                CDB - Cell and Developmental Biology
              </option>
              <option value="CEE">
                CEE - Civil and Environmental Engineering
              </option>
              <option value="CHBE">
                CHBE - Chemical and Biomolecular Engineering
              </option>
              <option value="CHEM">
                CHEM - Chemistry
              </option>
              <option value="CHIN">
                CHIN - Chinese
              </option>
              <option value="CHLH">
                CHLH - Community Health
              </option>
              <option value="CHP">
                CHP - Campus Honors Program Courses
              </option>
              <option value="CI">
                CI - Curriculum and Instruction
              </option>
              <option value="CIC">
                CIC - Committee on Inst Cooperation
              </option>
              <option value="CLCV">
                CLCV - Classical Civilization
              </option>
              <option value="CMN">
                CMN - Communication
              </option>
              <option value="CPSC">
                CPSC - Crop Sciences
              </option>
              <option value="CS">
                CS - Computer Science
              </option>
              <option value="CSE">
                CSE - Computational Science and Engineering
              </option>
              <option value="CW">
                CW - Creative Writing
              </option>
              <option value="CWL">
                CWL - Comparative and World Literature
              </option>
              <option value="CZCH">
                CZCH - Czech
              </option>
              <option value="DANC">
                DANC - Dance
              </option>
              <option value="EALC">
                EALC - East Asian Language and Culture
              </option>
              <option value="ECE">
                ECE - Electrical and Computer Engineering
              </option>
              <option value="ECON">
                ECON - Economics
              </option>
              <option value="EDPR">
                EDPR - Educational Practice
              </option>
              <option value="EDUC">
                EDUC - Education
              </option>
              <option value="EIL">
                EIL - English as an International Language
              </option>
              <option value="ENG">
                ENG - Engineering
              </option>
              <option value="ENGH">
                ENGH - Engineering Honors
              </option>
              <option value="ENGL">
                ENGL - English
              </option>
              <option value="ENSU">
                ENSU - Environmental Sustainability
              </option>
              <option value="ENT">
                ENT - Entomology
              </option>
              <option value="ENVS">
                ENVS - Environmental Studies
              </option>
              <option value="EOL">
                EOL - Educational Organization and Leadership
              </option>
              <option value="EPS">
                EPS - Educational Policy Studies
              </option>
              <option value="EPSY">
                EPSY - Educational Psychology
              </option>
              <option value="ESE">
                ESE - Earth Society and Environment
              </option>
              <option value="ESL">
                ESL - English as a Second Language
              </option>
              <option value="EURO">
                EURO - European Union Studies
              </option>
              <option value="FAA">
                FAA - Fine and Applied Arts
              </option>
              <option value="FIN">
                FIN - Finance
              </option>
              <option value="FR">
                FR - French
              </option>
              <option value="FSHN">
                FSHN - Food Science and Human Nutrition
              </option>
              <option value="GC">
                GC - Graduate College
              </option>
              <option value="GE">
                GE - General Engineering
              </option>
              <option value="GEOG">
                GEOG - Geography
              </option>
              <option value="GEOL">
                GEOL - Geology
              </option>
              <option value="GER">
                GER - German
              </option>
              <option value="GLBL">
                GLBL - Global Studies
              </option>
              <option value="GMC">
                GMC - Germanic
              </option>
              <option value="GRK">
                GRK - Greek
              </option>
              <option value="GRKM">
                GRKM - Modern Greek
              </option>
              <option value="GS">
                GS - General Studies
              </option>
              <option value="GWS">
                GWS - Gender and Women's Studies
              </option>
              <option value="HCD">
                HCD - Human and Community Development
              </option>
              <option value="HDES">
                HDES - Human Dimensions of Environmental Systems
              </option>
              <option value="HDFS">
                HDFS - Human Development and Family Studies
              </option>
              <option value="HEBR">
                HEBR - Hebrew Modern and Classical
              </option>
              <option value="HIST">
                HIST - History
              </option>
              <option value="HNDI">
                HNDI - Hindi
              </option>
              <option value="HORT">
                HORT - Horticulture
              </option>
              <option value="HRE">
                HRE - Human Resource Education
              </option>
              <option value="HUM">
                HUM - Humanities Courses
              </option>
              <option value="IB">
                IB - Integrative Biology
              </option>
              <option value="IE">
                IE - Industrial Engineering
              </option>
              <option value="IHLT">
                IHLT - i-Health Program
              </option>
              <option value="INFO">
                INFO - Informatics
              </option>
              <option value="ITAL">
                ITAL - Italian
              </option>
              <option value="JAPN">
                JAPN - Japanese
              </option>
              <option value="JOUR">
                JOUR - Journalism
              </option>
              <option value="JS">
                JS - Jewish Studies
              </option>
              <option value="KIN">
                KIN - Kinesiology
              </option>
              <option value="KOR">
                KOR - Korean
              </option>
              <option value="LA">
                LA - Landscape Architecture
              </option>
              <option value="LAS">
                LAS - Liberal Arts and Sciences
              </option>
              <option value="LAST">
                LAST - Latin American and Caribbean Studies
              </option>
              <option value="LAT">
                LAT - Latin
              </option>
              <option value="LAW">
                LAW - Law
              </option>
              <option value="LER">
                LER - Labor and Employment Relations
              </option>
              <option value="LGLA">
                LGLA - Lingala
              </option>
              <option value="LING">
                LING - Linguistics
              </option>
              <option value="LIS">
                LIS - Library and Information Science
              </option>
              <option value="LLS">
                LLS - Latina/Latino Studies
              </option>
              <option value="MACS">
                MACS - Media and Cinema Studies
              </option>
              <option value="MATH">
                MATH - Mathematics
              </option>
              <option value="MBA">
                MBA - Regular MBA Program Administration
              </option>
              <option value="MCB">
                MCB - Molecular and Cell Biology
              </option>
              <option value="MDIA">
                MDIA - Media
              </option>
              <option value="MDVL">
                MDVL - Medieval Studies
              </option>
              <option value="ME">
                ME - Mechanical Engineering
              </option>
              <option value="MICR">
                MICR - Microbiology
              </option>
              <option value="MILS">
                MILS - Military Science
              </option>
              <option value="MIP">
                MIP - Molecular and Integrative Physiology
              </option>
              <option value="MSE">
                MSE - Materials Science and Engineering
              </option>
              <option value="MSP">
                MSP - Medical Scholars Program
              </option>
              <option value="MUS">
                MUS - Music
              </option>
              <option value="MUSE">
                MUSE - Museum Studies
              </option>
              <option value="NEUR">
                NEUR - Neuroscience
              </option>
              <option value="NPRE">
                NPRE - Nuclear Plasma and Radiological Engineering
              </option>
              <option value="NRES">
                NRES - Natural Resources and Environmental Sciences
              </option>
              <option value="NS">
                NS - Naval Science
              </option>
              <option value="NUTR">
                NUTR - Nutritional Sciences
              </option>
              <option value="PATH">
                PATH - Pathobiology
              </option>
              <option value="PBIO">
                PBIO - Plant Biology
              </option>
              <option value="PERS">
                PERS - Persian
              </option>
              <option value="PHIL">
                PHIL - Philosophy
              </option>
              <option value="PHYS">
                PHYS - Physics
              </option>
              <option value="PLPA">
                PLPA - Plant Pathology
              </option>
              <option value="POL">
                POL - Polish
              </option>
              <option value="PORT">
                PORT - Portuguese
              </option>
              <option value="PS">
                PS - Political Science
              </option>
              <option value="PSM">
                PSM - Professional Science Master's Program
              </option>
              <option value="PSYC">
                PSYC - Psychology
              </option>
              <option value="REES">
                REES - Russian East European and Eurasian Studies
              </option>
              <option value="REHB">
                REHB - Rehabilitation Counseling
              </option>
              <option value="RHET">
                RHET - Rhetoric and Composition
              </option>
              <option value="RLST">
                RLST - Religious Studies
              </option>
              <option value="RMLG">
                RMLG - Romance Linguistics
              </option>
              <option value="RSOC">
                RSOC - Rural Sociology
              </option>
              <option value="RST">
                RST - Recreation Sport and Tourism
              </option>
              <option value="RUSS">
                RUSS - Russian
              </option>
              <option value="SAME">
                SAME - South Asian and Middle Eastern Studies
              </option>
              <option value="SCAN">
                SCAN - Scandinavian
              </option>
              <option value="SCR">
                SCR - Serbo-Croatian
              </option>
              <option value="SHS">
                SHS - Speech and Hearing Science
              </option>
              <option value="SLAV">
                SLAV - Slavic
              </option>
              <option value="SLS">
                SLS - Second Language Studies
              </option>
              <option value="SNSK">
                SNSK - Sanskrit
              </option>
              <option value="SOC">
                SOC - Sociology
              </option>
              <option value="SOCW">
                SOCW - Social Work
              </option>
              <option value="SPAN">
                SPAN - Spanish
              </option>
              <option value="SPED">
                SPED - Special Education
              </option>
              <option value="STAT">
                STAT - Statistics
              </option>
              <option value="SWAH">
                SWAH - Swahili
              </option>
              <option value="TAM">
                TAM - Theoretical and Applied Mechanics
              </option>
              <option value="TE">
                TE - Technology Entrepreneurship
              </option>
              <option value="THEA">
                THEA - Theatre
              </option>
              <option value="TMGT">
                TMGT - Technology and Management Courses
              </option>
              <option value="TRST">
                TRST - Translation Studies
              </option>
              <option value="TSM">
                TSM - Technical Systems Management
              </option>
              <option value="TURK">
                TURK - Turkish
              </option>
              <option value="UKR">
                UKR - Ukrainian
              </option>
              <option value="UP">
                UP - Urban and Regional Planning
              </option>
              <option value="VCM">
                VCM - Veterinary Clinical Medicine
              </option>
              <option value="VM">
                VM - Veterinary Medicine Courses
              </option>
              <option value="WLOF">
                WLOF - Wolof
              </option>
              <option value="WRIT">
                WRIT - Writing Studies
              </option>
              <option value="YDSH">
                YDSH - Yiddish
              </option>
              <option value="ZULU">
                ZULU - Zulu
              </option>
            </select> <select id="ctlCourse" name="ctlCourse">
              <option value="-1">
                &lt; - Choose a Dept.
              </option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            Tutor Education:
          </td>
          <td>
            <select name="educationlevel">
              <option value="-1">
                Select Degree:
              </option>
              <option value="Master">
                Master
              </option>
              <option value="PhD">
                PhD
              </option>
              <option value="Bachelor">
                Bachelor
              </option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <h3>
              Advanced Information
            </h3>Language
          </td>
          <td>
            <input type="text" value="English" name="language">
          </td>
        </tr>
        <tr>
          <td>
            Name Contains
          </td>
          <td>
            <input type="text" value="" name="name">
          </td>
        </tr>
        <tr>
          <td>
            Rating between
          </td>
          <td>
            <input type="text" value="" name="ratingMin">
          </td>
          <td>
            And
          </td>
          <td>
            <input type="text" value="" name="ratingMax">
          </td>
        </tr>
        <tr>
          <td>
            <h3>
              Time
            </h3>I need minutes of time with tutor:
          </td>
          <td colspan="3">
            <input type="text" name="timewanted"> minutes
          </td>
        </tr>
        <tr>
          <td>
            My first time preference is in the
          </td>
          <td colspan="3">
            <select name="period_pref1">
              <option value="-1">
                Select Time of Day
              </option>
              <option value="1">
                Morning
              </option>
              <option value="2">
                Afternoon
              </option>
              <option value="3">
                Evening
              </option>
              <option value="4">
                Night
              </option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <input type="submit">
          </td>
        </tr>
      </table>
    </form>


    <?php
}

$MINPRICE       = $_POST["minprice"];
$MAXPRICE       = $_POST["maxprice"];
$CTLDEPT        = $_POST["ctlDept"];
$CTLCOURSE      = $_POST["ctlCourse"];
$EDUCATIONLEVEL = $_POST["educationlevel"];
$LANGUAGE       = $_POST["language"];
$NAME           = $_POST["name"];
$RATINGMIN      = $_POST["ratingMin"];
$RATINGMAX      = $_POST["ratingMax"];
$RANGE          = $_POST["range"];
$FROM_ADDR      = $_POST["from_addr"];
$FROM_BLDG      = $_POST["from_bldg"];
$TIMEWANTED     = $_POST["timewanted"];
$PERIOD_PREF1   = $_POST["period_pref1"];
$PERIOD_PREF2   = $_POST["period_pref2"];
flush();
?>

<BODY onLoad="init()">
<div id="loading" style="position:absolute; text-align:center; background-color:#F1FCFF; width:50%; border-style:solid; text-decoration:blink"><p><b>Loading...</b><p></div>
  <script>
  // from http://www.reconn.us/content/view/37/47/
 var ld=(document.all);
  var ns4=document.layers;
 var ns6=document.getElementById&&!document.all;
 var ie4=document.all;
  if (ns4)
 	ld=document.loading;
 else if (ns6)
 	ld=document.getElementById("loading").style;
 else if (ie4)
 	ld=document.all.loading.style;
  function init()
 {
 if(ns4){ld.visibility="hidden";}
 else if (ns6||ie4) ld.display="none";
 }
 </script>
 <h2>Related Search - Advanced Function</h2>
<?php
show_search_widget();

//Start body

if (count($_POST)) {
    flush();
    echo "<h3>Results</h3>";

    $plain_results = search_results($MINPRICE, 
									$MAXPRICE, 
									$TIMEWANTED, 
									$PERIOD_PREF1, 
									$RANGE, 
									$LANGUAGE, 
									$NAME, 
									$CTLDEPT, 
									array($CTLCOURSE), 
									array($EDUCATIONLEVEL), 
									$RATINGMIN, 
									$RATINGMAX);
	
    flush();
	
    show_html_table($plain_results);
	
    $plain_CRNs = array();
    array_push($plain_CRNs, $CTLCOURSE);
    
    foreach ($plain_results as $row) {
        $crn = $row["CRN"];
        array_push($plain_CRNs, $crn);
    }
    
    
    echo "<h3>Similar results</h3> You may also be interested in the following tutors:";
    
    if (DEBUG) {
        echo "Also showing results with similar parameters to <BR>";
        echo "MINPRICE<B>" . $MINPRICE . "</B><BR>";
        echo "MAXPRICE<B>" . $MAXPRICE . "</B><BR>";
        echo "TIMEWANTED<B>" . $TIMEWANTED . "</B><BR>";
        echo "PERIOD_PREF1<B>" . $PERIOD_PREF1 . "</B><BR>";
        echo "PERIOD_PREF2<B>" . $PERIOD_PREF2 . "</B><BR>";
        echo "LANGUAGE<B>" . $LANGUAGE . "</B><BR>";
        echo "NAME<B>" . $NAME . "</B><BR>";
        echo "EDUCATIONLEVEL<B>" . $EDUCATIONLEVEL . "</B><BR>";
        echo "RATINGMIN<B>" . $RATINGMIN . "</B><BR>";
        echo "RATINGMAX<B>" . $RATINGMAX . "</B><BR>";
    }
    
    
    $new_price_range     = expand_price(array(
        $MINPRICE,
        $MAXPRICE
    ));
    $new_rating_range    = expand_rating(array(
        $RATINGMIN,
        $RATINGMAX
    ));
    $new_education_level = expand_education_level($EDUCATIONLEVEL);
    
    echo "<TABLE border=1><TR><TD>Price between</TD><TD>";
    echo $new_price_range[0] . " - " . $new_price_range[1];
    echo "</TD></TR><TR><TD>Rating Between</TD><TD>";
    echo $new_rating_range[0] . " - " . $new_rating_range[1];
    echo "</TD></TR><TR><TD>Education level</TD><TD>";
    echo $new_education_level[0] . " " . $new_education_level[1] . "</TD>";
    echo "<TR><TD>Minutes with tutor</TD><TD>";
    echo expand_time_wanted($TIMEWANTED);
    echo "</TD></TR></TABLE>";
    
    $similar_param_results = search_results($new_price_range[0],
											$new_price_range[1],
											expand_time_wanted($TIMEWANTED),
											"",
											"",
											"",
											"",
											"",
											generate_related_CRNs($plain_CRNs),
											$new_education_level,
											$new_rating_range[0],
											$new_rating_range[1]);
    
    show_html_table($similar_param_results);
}

mysql_close($con);
?>