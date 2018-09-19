<?php
include('../db.php');

try{
    $pdo = new PDO("mysql:host=$db[host];dbname=$db[dbname];port=$db[port];charset=$db[charset]",$db['username'],$db['password']);
}catch(PDOException $e){
    echo"connection failed";
    exit;
};
$year=date("Y");
$mon=date("m");
$sql = 'SELECT * FROM events WHERE year=:year AND mon=:mon ORDER BY `date`,start_time ASC';
$statement=$pdo->prepare($sql);
$statement->bindValue(':year',$year,PDO::PARAM_INT);
$statement->bindValue(':mon',$mon,PDO::PARAM_INT);
$statement->execute();

$events=$statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($events as $key => $event) {
    $events[$key]['start_time']=substr ($event['start_time'],0,5);
}

$days=cal_days_in_month(CAL_GREGORIAN,$mon,$year);
$firstday=new DateTime("$year-$mon-1");
$frontpadding=$firstday->format('w');
$lastday=new DateTime("$year-$mon-$days");
$lastpadding=6-$lastday->format('w');

for($i=0;$i<$frontpadding;$i++){
    $dates[]=null;
}
for($i=0;$i<$days;$i++){
    $dates[]=$i+1;
}
for($i=0;$i<$lastpadding;$i++){
    $dates[]=null;
}
?>
<script>
    var events= <?=json_encode($events,JSON_NUMERIC_CHECK)?>;
</script>