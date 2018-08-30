<!DOCTYPE html>
<html>

<body>

<div id="map" style="width:100%;height:500px"></div>

<?php


/*najprv nacitame potencionalne body ktore mozu bit spolocne pre viacere cesty najprv tereba naist priesecniky  potom ich treba spojit cesta je dana n bodmy preto hladam priesecnik medzi dvoma bodmi na ceste cn a dvoma na ceste cn1 priesecniky tie treba spojit medzi sebou vieme prepojit priesecniky cesty cn z inimy cestamy */
//treba ich potom vyklustrovat naist nablisie body na stranach
$link = mysqli_connect("localhost", "id1179754_drummer", "tymplsps", "id1179754_navigation");
$sql = "select journey.id as journey_id,choords.id as choords_id,longitude,latitude,speed,heading,accuracy,filtrovane,	time,majupriesecnik from journey join journey_points on journey.id =journey_points.journey_id join choords on journey_points.choords_id=choords.id where choords.filtrovane=0 and speed<15 order by journey.id ,choords.id";
 $result= $result=mysqli_query($link,$sql)or die(mysqli_error($link));
$objchoords=[];
 while($row=mysqli_fetch_assoc($result)){
    
   $jid=$row['journey_id'];
    $id=$row['choords_id'];
     $latitude =$row['latitude'];
     $longitude =$row['longitude'];
     $speed =$row['speed'];
     $heading =$row['heading'];
     $accuracy =$row['accuracy'];
     $filtrovany =$row['filtrovane'];
    $time =$row['time'];
    $majupriesecnik=$row['majupriesecnik'];

 $obj = new stdClass();
 $obj->id= $id;
 $obj->x= $latitude;
 $obj->y= $longitude;
 $obj->speed= $speed;
 $obj->heading= $heading;
 $obj->accuracy= $accuracy;
 $obj->filtrovany= $filtrovany;
 $obj->time= $time;
 $obj->majupriesecnik= $majupriesecnik;
if (array_key_exists($jid, $objchoords)) {
    array_push($objchoords[$jid], $obj);
} else{
$objchoords[$jid] = [$obj]; 
 //print_r($obj);   
    
}

}
$sql="SELECT MAX(id) as id FROM intersects";
$result=mysqli_query($link,$sql);
$x=substr(strval($latitude),3);
$y=substr(strval($longitude),3);
$choords[] = [$x,$y];
$x=substr(strval($latitude),0,3);
$y=substr(strval($longitude),0,3);
$choordspred[] = [$x,$y];


$bodid=0;
 while($row=mysqli_fetch_assoc($result)){
    // $bodid=$row['id'];
 }
 //print_r($bodid);
 $intersects=[];
    $sql = "select * from intersects";
 $result= $result=mysqli_query($link,$sql)or die(mysqli_error($link));

$id=$row['id'];
 while($row=mysqli_fetch_assoc($result)){
    $sql1 = "select journey_id from journey_intersects where intersect_id='$id'";
 $result1=mysqli_query($link,$sql1)or die(mysqli_error($link));
$ids=[];
 while($row1=mysqli_fetch_assoc($result1)){
// print_r($row1);
     $ids[]=$row1['journey_id'];
 }
 
 
$latitude =$row['lattitude'];
     $longitude =$row['longitude'];
     

 $obj = new stdClass();
 $obj->id= intval($row['id']);
 $obj->x= floatval($latitude);
 $obj->y= floatval($longitude);
 $obj->cesty= $ids;
$intersects[]=$obj;     
 }    

$choords=json_encode($choords);
$choordspred=json_encode($choordspred);
$objchoords=json_encode($objchoords);
$intersects=json_encode($intersects);
$stops=[];
$sql = "select* from stops";
while($row=mysqli_fetch_assoc($result)){
    $obj = new stdClass();
 $obj->busstop1= $row['busstop1'];
    $obj->busstop2= $row['busstop2'];
    $obj->time= $row['time'];

    array_push($stops, [$obj]);
}
$stops=json_encode($stops);
?>
<script
src="https://cdn.rawgit.com/nicolewhite/algebra.js/gh-pages/javascripts/algebra-0.2.6.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="geocluster.js"></script>
<script type="text/javascript">

//var dbscan= new DBSCAN()
var surintersect =[]        
  var vyzited=[]        
 var bodid=parseInt("<?php echo $bodid ?>")

 console.log(bodid)
 var graf=JSON.parse('<?= $stops; ?>');
 var existingintersects=JSON.parse('<?= $intersects; ?>');
 var points=[]
//var choords = JSON.parse('<?= $choords; ?>'); 
//var choordspred=JSON.parse('<?= $choordspred; ?>')               
var choordsobj = JSON.parse('<?= $objchoords; ?>');
function compare(a, b) {
    if (a[0] === b[0]) {
        return 0;
    }
    else {
        return (a[0] < b[0]) ? -1 : 1;
    }
}
Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

function lineIntersect(x1,y1,x2,y2, x3,y3,x4,y4) {
    var x=((x1*y2-y1*x2)*(x3-x4)-(x1-x2)*(x3*y4-y3*x4))/((x1-x2)*(y3-y4)-(y1-y2)*(x3-x4));
    var y=((x1*y2-y1*x2)*(y3-y4)-(y1-y2)*(x3*y4-y3*x4))/((x1-x2)*(y3-y4)-(y1-y2)*(x3-x4));
    if (isNaN(x)||isNaN(y)) {
        return false;
    } else {
       if (x1>=x2) {
            if (!(x2<=x&&x<=x1)) {return false;}
        } else {
            if (!(x1<=x&&x<=x2)) {return false;}
        }
        if (y1>=y2) {
            if (!(y2<=y&&y<=y1)) {return false;}
        } else {
            if (!(y1<=y&&y<=y2)) {return false;}
        }
        if (x3>=x4) {
            if (!(x4<=x&&x<=x3)) {return false;}
        } else {
            if (!(x3<=x&&x<=x4)) {return false;}
        }
        if (y3>=y4) {
            if (!(y4<=y&&y<=y3)) {return false;}
        } else {
            if (!(y3<=y&&y<=y4)) {return false;}
        }
    }
    return [x,y];
};
function search(pointsobj,id){
    var sus=[]
     for(var i=0; i<pointsobj.length; i++){
         
         if(pointsobj[i][4]==id){
             
             return [i,id]
         }
         
     }

    
}

function searchexisting(pointsobj,id){
    var sus=[]
     for(var i=0; i<pointsobj.length; i++){
         
         if(pointsobj[i].id==id){
             
             return [i,id]
         }
         
     }

    
}
function deg2rad(deg) {
    return deg * (Math.PI/180)
}
function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
    var dLat =0
    var dLon = 0
    var R = 6371; // Radius of the earth in km
    if(lat2>lat1){
        dLat = deg2rad(lat2-lat1);  // deg2rad below
    }else{
        dLat = deg2rad(lat1-lat2);
    }
    if(lon2>lon1){
        dLon = deg2rad(lon2-lon1); 
    }else{
        dLon = deg2rad(lon1-lon2); 
    }
    
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
            Math.sin(dLon/2) * Math.sin(dLon/2)
    ; 
    console.log(a,'a')
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
    var d = R * c; // Distance in km
    return d*1000;
}
function gettime(lat1,lon1,lat2,lon2,speed){
    dist=getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2)/1000
    return dist/speed
}


// vytvorenie priesecnikov treba prejist vsetky cesty a naist priesecnik tie sa budu hladat medzy dvome cestamy kde v jedna cesta nema priesecnik
for (choord in choordsobj){
    vyzited.push(choord)
    for(var i =0; i<choordsobj[choord].length-1;i++){
        for (choord1 in choordsobj){
            if (parseInt(choordsobj[choord][i].majupriesecnik)==0){
            if(choord1!=choord && vyzited.includes(choord1)){
                for(var j =0; j<choordsobj[choord1].length-1;j++){
                    //if (choordsobj[choord1][j].majupriesecnik==0){
                        intersect=lineIntersect( choordsobj[choord][i].x,choordsobj[choord][i].y,choordsobj[choord][i+1].x,choordsobj[choord][i+1].y,
                    choordsobj[choord1][j].x,choordsobj[choord1][j].y,choordsobj[choord1][j+1].x, choordsobj[choord1][j+1].y);
                    
                        //console.log(i)
                   
                        if (intersect!== false){
                            //bodid+=1
                            
                            intersect.push([],(parseFloat(choordsobj[choord1][j].speed
)+parseFloat(choordsobj[choord1][j+1].speed))/2)
                            intersect[2].push(choord1,choord)
                            surintersect.push(intersect)
                        }   
                        }
               }
            }       
        }
    }
}

function isArrayInArray(arr, item){
  var item_as_string = JSON.stringify(item);

  var contains = arr.some(function(ele){
    return JSON.stringify(ele) === item_as_string;
  });
  return contains;
}


function get_journey(x1,y1){
    cesty=[]
   // console.log(x1,y1)
    for (s in choordsobj){
        
        for(var j=0; j<choordsobj[s].length;j++){
      
         if (choordsobj[s][j].x==x1 && choordsobj[s][j].y==y1 && !cesty.includes(s)){
        
            cesty.push(s)
         }
        }
    }
    return  cesty
}
var cesty=[]
bias=0.5
result = geocluster(surintersect, bias);
for(var i=0;i<result.length;i++){
    for (var j=1;j<result[i].elements.length;j++){
        result[i].elements[0][2].push(result[i].elements[j][2][0],result[i].elements[j][2][1])
        
    }
    
}
clusteredchoords=[]
console.log(result)
for (var i=0;i<result.length;i++){
    clusteredchoords.push(result[i].elements[0])
    
}

for (var i=0;i<clusteredchoords.length;i++){
    clusteredchoords[i].push(bodid)
    bodid+=1;
}

najkratsia=10000
n=0
avg_speed=45
vzdialenosti=[]
journey_intersect=[]
for (var i=0;i<clusteredchoords.length;i++){
  
    vzdialenosti=[]
    
    for (var j=0;j<clusteredchoords.length;j++){
        if (i!=j){
            vzd=getDistanceFromLatLonInKm(clusteredchoords[i][0],clusteredchoords[i][1],clusteredchoords[j][0],clusteredchoords[j][1])
                    najkratsia=vzd
                    time=gettime(clusteredchoords[i][0],clusteredchoords[i][1],clusteredchoords[j][0],clusteredchoords[j][1],avg_speed) 
                    n=j
                   for (var k=0;k<clusteredchoords[n][2].length;k++){
                      if (clusteredchoords[i][2].includes(clusteredchoords[n][2][k])){
                    vzdialenosti.push([najkratsia,clusteredchoords[i][4],clusteredchoords[n][4],time])
                    break
                      }
                }
           
        }
    }
    vzdialenosti.sort(compare)
    var mozepridat=false
    var indexsuseda=0
    console.log(vzdialenosti,' vzd ')
    index=search(clusteredchoords,vzdialenosti[0][2])
        graf.push([vzdialenosti[0][1],vzdialenosti[0][2],vzdialenosti[0][3]])
        console.log(index)
        for (var j=1;j<vzdialenosti.length;j++){
           
           
           index2=search(clusteredchoords,vzdialenosti[j][2])
           currdist=getDistanceFromLatLonInKm(clusteredchoords[i][0],clusteredchoords[i][1],clusteredchoords[index2[0]][0],clusteredchoords[index2[0]][1])
        
        neighbourdist=getDistanceFromLatLonInKm(clusteredchoords[index[0]][0],clusteredchoords[index[0]][1],clusteredchoords[index2[0]][0],clusteredchoords[index2[0]][1])
           
            if(currdist<neighbourdist){
            mozepridat=true
            indexsuseda=j
            break;
        }
        }
        if (mozepridat){
            graf.push([vzdialenosti[indexsuseda][1],vzdialenosti[indexsuseda][2],vzdialenosti[indexsuseda][3]])
        }
    najkratsia=10000
    
}
for(var i=0;i<clusteredchoords.length;i++){
    for(var j=0;j<clusteredchoords[i][2].length;j++){
        
        if (isArrayInArray(journey_intersect, [clusteredchoords[i][2][j],clusteredchoords[i][4]])==false){
            journey_intersect.push([clusteredchoords[i][2][j],clusteredchoords[i][4]])
        }
    
    }   
}
for (var i=0;i<clusteredchoords.length;i++){
  
    vzdialenosti=[]
    
    for (var j=0;j<existingintersects.length;j++){
            vzd=getDistanceFromLatLonInKm(clusteredchoords[i][0],clusteredchoords[i][1],existingintersects[j].x,existingintersects[j].y)
                    najkratsia=vzd
                    time=gettime(clusteredchoords[i][0],clusteredchoords[i][1],existingintersects[j].x,existingintersects[j].y,avg_speed) 
                    n=j
                   for (var k=0;k<existingintersects[n].cesty.length;k++){
                      console.log(clusteredchoords[i][2],'ttttttt',existingintersects[n].cesty[k])
                      if (clusteredchoords[i][2].includes(existingintersects[n].cesty[k])){
                    vzdialenosti.push([najkratsia,clusteredchoords[i][4],existingintersects[n].id,time])
                    break
                      }
                
           
        }
    }
    vzdialenosti.sort(compare)
    var mozepridat=false
    var indexsuseda=0
    console.log(vzdialenosti,' vzd ')
    index=search(clusteredchoords,vzdialenosti[0][2])
        graf.push([vzdialenosti[0][1],vzdialenosti[0][2],vzdialenosti[0][3]])
        console.log(vzdialenosti,'index')
        console.log(index)
        for (var j=1;j<vzdialenosti.length;j++){
           
           
           index2=searchexisting(existingintersects,vzdialenosti[j][2])
           console.log(index2,vzdialenosti)
           currdist=getDistanceFromLatLonInKm(clusteredchoords[i][0],clusteredchoords[i][1],existingintersects[index2[0]].x,existingintersects[index2[0]].y)
       
        neighbourdist=getDistanceFromLatLonInKm(clusteredchoords[index[0]][0],clusteredchoords[index[0]][1],existingintersects[index2[0]].x,existingintersects[index2[0]].y)
           
            if(currdist<neighbourdist){
            mozepridat=true
            indexsuseda=j
            break;
        }
        }
        if (mozepridat){
            graf.push([vzdialenosti[indexsuseda][1],vzdialenosti[indexsuseda][2],vzdialenosti[indexsuseda][3]])
        }
    najkratsia=10000
    
}
for(var i=0;i<clusteredchoords.length;i++){
    for(var j=0;j<clusteredchoords[i][2].length;j++){
        
        if (isArrayInArray(journey_intersect, [clusteredchoords[i][2][j],clusteredchoords[i][4]])==false){
            journey_intersect.push([clusteredchoords[i][2][j],clusteredchoords[i][4]])
        }
    
    }   
}

for (var i=0;i<graf.length;i++){
    
    if(graf[i][0] in cesty){
       
                cesty[graf[i][0]].push(graf[i][1])
                            
        
    }else{
        cesty[graf[i][0]]=[graf[i][1]]
    }
    
}
//AStarPathFinder(surintersect,0, 2,cesty)
      
var suradnice=[clusteredchoords,graf,journey_intersect]
$.ajax({
                    type: "POST",
                    url: 'updategraf.php',//
                    data: { suradnice : JSON.stringify(suradnice) },
                    success: function(data)
                    {
                        //console.log(data);
                    }
                });
                // to do vytvorit graf  spojit body uzly spolocne body cez priesecnik priamok*/

</script>


</body>
</html>