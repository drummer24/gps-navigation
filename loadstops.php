<?php

$link = mysqli_connect("localhost", "id1179754_drummer", "tymplsps", "id1179754_navigation"); 
$sql = "SELECT 
        
        i.lattitude,
        i.longitude,
        i.id,
        s.busstop1,
        s.busstop2,
        s.time as travtime
        
         
  FROM stops s
  JOIN intersects i ON s.busstop1 = i.id";
 $result=mysqli_query($link,$sql)or die(mysqli_error($link));
 

 while($row=mysqli_fetch_assoc($result)){
 $obj = new stdClass();
 $obj->id= $row['id'];
 $obj->bsid1=$row['busstop1'];
 $obj->bsid2=$row['busstop2'];
  $obj->lattitude=$row['lattitude'];
 $obj->longitude=$row['longitude'];

 $obj->travtime=$row['travtime'];
 

$choords[] = $obj;
}
echo json_encode($choords);
?>