<?php
$link = mysqli_connect("localhost", "id1179754_drummer", "tymplsps", "id1179754_navigation");
 

function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}

$suradnice=json_decode( $_POST['suradnice']);
for($i=0;$i< count($suradnice[0]);$i++){
    $index=$suradnice[0][$i][rand(0,count($suradnice[0][$i])-1)];
    $suradnice[0][$i]=$suradnice[1][$index];
    $suradnice[0][$i]->x=$suradnice[2][$index][0];
    $suradnice[0][$i]->y=$suradnice[2][$index][1];
    $suradnice[0][$i]->id=$suradnice[3][$index];
    echo $suradnice[3][$index];
    //print_r($suradnice[0][$i]);
    
}
for($i=0;$i< count($suradnice[0])-1;$i++){
    if($i>0 ){
            $i1=$suradnice[0][$i-1]->id;
            $time=$suradnice[1][$i]->time-$suradnice[1][$i-1]->time;
            echo $i; 
          $sql = "INSERT INTO stops (busstop1,busstop2,time) VALUES ( '".$suradnice[0][$i]->id."','".$i1."','".$time."')";
           $result = mysqli_query($link, $sql);
        
    }
    if($i<count($suradnice[0])-1){
        
        
            $i1=$suradnice[0][$i+1]->id;
           
            $time=$suradnice[1][$i+1]->time-$suradnice[1][$i]->time;
            $sql = "INSERT INTO stops (busstop1,busstop2,time) VALUES ( '".$suradnice[0][$i]->id."','".$i1."','".$time."')";
            $result = mysqli_query($link, $sql)or die(mysqli_error($link));
            
        
    }
    
}

//print_r($graf);

/*function calculateSpeed(t1, lat1, lon1, t2, lat2, lon2) {
  var d = Math.sqrt(Math.pow(lat2-lat1,2)+Math.pow(lon2-lon1,2))*1000
  console.log(d)
  
  return (d/((t2-t1)*0.01))*3600;
}*/
?>