<?php


function get_map_url($coords)
{
    $width=640;
    $height=480;
    $url="http://open.mapquestapi.com/staticmap/v3/getmap?size=$width,$height&type=map&imagetype=jpeg&pois=";
    $url .= "green,".$coords[0][0].",".$coords[0][1]."|";
    for ($i = 1, $size = sizeof($coords); $i < $size; $i++) {
        $url .= ($i).",".($coords[$i][0]).",".($coords[$i][1])."|";
    }
    
    return $url;
}

function get_coords($address)
{
    $base_coord_url="http://open.mapquestapi.com/nominatim/v1/search?format=json&routeType=shortest&q=";
    $src = $base_coord_url.str_replace(" ","+",$address);
    $json = file_get_contents($src);
    $set = json_decode($json, true);
    $lat = $set[0]["lat"];
    $lon = $set[0]["lon"];
    return array($lat, $lon);
}



function get_distance($from, $to)
{
    
    include('auth.php');
    $from_coord = get_coords($from);
    $to_coord = get_coords($to);
    

    
    if ($from_coord[0]!="" && $from_coord[1]!="" && $to_coord[0]!="" && $to_coord[1]!="") {
        

        
        $connection = mysql_connect($server,$user,$pass);
        if (!$connection) {
            die('Could not connect: ' . mysql_error());
        }
        
        
        
        $query = "SELECT distance FROM `$db`.`DistanceCache` WHERE ";
        $query .= "src_lat=".$from_coord[0];
        $query .= " AND src_lon=".$from_coord[1];
        $query .= " AND dest_lat=".$to_coord[0];
        $query .= " AND dest_lon=".$to_coord[1]."LIMIT 0,1";
        
        $resource = mysql_query($query);
        if (! $resource) {
            die(mysql_error());
        }
        
        $numrows = mysql_num_rows($resource);
        if ($numrows==1) {
            
            $row=mysql_fetch_array($resource);
            return $row["distance"];
        }


        $base_distance_url="http://open.mapquestapi.com/directions/v0/route?outFormat=json";
        $dist_src = $base_distance_url."&from=".$from_coord[0].",".$from_coord[1]."&to=".$to_coord[0].",".$to_coord[1];
        $dist_json = file_get_contents($dist_src);
        $dist_set = json_decode($dist_json, true);
        $distance= $dist_set["route"]["distance"];
        if (!$connection) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($db, $connection);
        
        $query = "INSERT INTO `$db`.`DistanceCache` VALUES(".$from_coord[0].",".$from_coord[1].",".$to_coord[0].",".$to_coord[1].",".$distance.")";
        $resource = mysql_query($query);
        if (! $resource) {
            die(mysql_error());
        }
    }

    return $distance;
}

function within_range($dist, $range)
{
    if ($dist > $range) {
        return false;
    }
    return true;
}
?>