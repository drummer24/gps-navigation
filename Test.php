<?php

$link = mysqli_connect("localhost", "id1179754_drummer", "tymplsps", "id1179754_navigation");
$sql = "select * from choords where filtrovane=0 ";
 $result=mysqli_query($link,$sql);

 while($row=mysqli_fetch_assoc($result)){
     $id=$row['id'];
     $latitude =$row['latitude'];
     $longitude =$row['longitude'];
     $speed =$row['speed'];
     $heading =$row['heading'];
     $accuracy =$row['accuracy'];
     $filtrovany =$row['filtrovane'];
    $time =$row['time'];

 $obj = new stdClass();
 $obj->id= $id;
 $obj->x= $latitude;
 $obj->y= $longitude;
 $obj->speed= $speed;
 $obj->heading= $heading;
 $obj->accuracy= $accuracy;
 $obj->filtrovany= $filtrovany;
 $obj->time= $time;
$objchoords[] = $obj; 
$x=intval(substr(strval($latitude),3));
$y=intval(substr(strval($longitude),3));
$choords[] = [$x,$y];
$ids[]=$id;
}
$choords=json_encode($choords);
$objchoords=json_encode($objchoords);
$ids=json_encode($ids);
?>
<script type="text/javascript" src="//code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="../dist/clustering.js"></script>
<script type="text/javascript">

var dbscan= new DBSCAN()
          
              
              
var choords = JSON.parse('<?= $choords; ?>'); 
              
var choordsobj = JSON.parse('<?= $objchoords; ?>');
var ids = JSON.parse('<?= $ids; ?>');
console.log(ids)
var r= dbscan.run(choords,3,1);

              console.log(r)
var suradnice=[r,choordsobj,choords,ids]
alert(r)
$.ajax({
                    type: "POST",
                    url: 'file.php',
                    data: { suradnice : JSON.stringify(suradnice) },
                    success: function(data)
                    {
                        alert(data);
                    }
                });
</script>

