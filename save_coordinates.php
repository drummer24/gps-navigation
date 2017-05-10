<?php

    $json = $_POST['coordinates'];
    $suradnice= json_decode($json, true);
    $link = mysqli_connect("localhost", "id1179754_drummer", "tymplsps", "id1179754_navigation");
    $sql = "INSERT INTO journey (price) VALUES (0)";
        	  $result = mysqli_query($link, $sql); 

  
    foreach($suradnice as $choords) { //foreach element in $arr
	echo $choords;  
          $sql = "INSERT INTO choords (latitude,longitude,speed,heading,accuracy) VALUES('".floatval(str_replace(',', '.', $choords['x']))."', '".floatval(str_replace(',', '.', $choords['y']))."','".floatval(str_replace(',', '.', $choords['speed']))."','".floatval(str_replace(',', '.', $choords['heading']))."','".floatval(str_replace(',', '.', $choords['accuracy']))."')";
    	  $result = mysqli_query($link, $sql); 
	  $sql = "SELECT id FROM journey ORDER BY id DESC LIMIT 1";
	  $journey_id = mysqli_query($link, $sql);
	  $sql = "SELECT id FROM choords ORDER BY id DESC LIMIT 1";	 
	  $choords_id = mysqli_query($link, $sql);
     $choords_id=mysqli_fetch_assoc($choords_id);
     $journey_id=mysqli_fetch_assoc($journey_id);
      echo $choords_id['id'];
   $sql = "INSERT INTO journey_points (	journey_id,	choords_id) VALUES('".$journey_id['id']."', '".$choords_id['id']."')";
     if (mysqli_query($link,$sql))
    {
        // Success
    }
    else 
    {
        die('Error on query 2: ' . mysqli_error($link));
    }
    }
  
?>