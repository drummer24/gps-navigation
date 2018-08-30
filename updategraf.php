<?php
$link = mysqli_connect("localhost", "id1179754_drummer", "tymplsps", "id1179754_navigation");



$suradnice=json_decode( $_POST['suradnice']);
/*print_r($suradnice[1]) ;
print_r($suradnice[0]) ;
*/
for($i=0;$i< count($suradnice[0])-1;$i++){
   print_r($suradnice[1][$i]);

             
          $sql = "INSERT INTO  intersects(id,lattitude,longitude) VALUES ( '".$suradnice[0][$i][4]."','".$suradnice[0][$i][0]."','".$suradnice[0][$i][1]."')";
           $result = mysqli_query($link, $sql);
           
    
    
}
for($i=0;$i< count($suradnice[1])-1;$i++){
   print_r($suradnice[1][$i]);
   
             
          $sql = "INSERT INTO stops (busstop1,busstop2,time) VALUES ( '".$suradnice[1][$i][0]."','".$suradnice[1][$i][1]."','".$suradnice[1][$i][2]."')";
           $result = mysqli_query($link, $sql);
        
    
    
}
for($i=0;$i< count($suradnice[2])-1;$i++){
   print_r($suradnice[1][$i]);

             
          $sql = "INSERT INTO  journey_intersects (journey_id,intersect_id) VALUES ( '".$suradnice[2][$i][0]."','".$suradnice[2][$i][1]."')";
           $result = mysqli_query($link, $sql);
           
    
    
}
$sql = "UPDATE choords SET majupriesecnik = 1";
$result = mysqli_query($link, $sql);
//print_r($graf);

/*function calculateSpeed(t1, lat1, lon1, t2, lat2, lon2) {
  var d = Math.sqrt(Math.pow(lat2-lat1,2)+Math.pow(lon2-lon1,2))*1000
  console.log(d)
  
  return (d/((t2-t1)*0.01))*3600;
}*/
?>