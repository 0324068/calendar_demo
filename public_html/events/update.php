<?php
header('Content-Type:Application/json;charset:utf-8');
include('../../db.php');
include('../httpcode.php');
try{
    $pdo = new PDO("mysql:host=$db[host];dbname=$db[dbname];port=$db[port];charset=$db[charset]",$db['username'],$db['password']);
}catch(PDOException $e){
    echo"connection failed";
    exit;
};

if(empty($_POST['title'])){
    new httpcode(400,'title cant be blank');
}
else if(empty($_POST['start_time'])||empty($_POST['end_time'])){
    new httpcode(400,'time cant be blank');
}
$starttime[]=explode(':',$_POST['start_time']);
$endtime[]=explode(':',$_POST['end_time']);
if($starttime[0]>$endtime[0]||($starttime[0]==$endtime[0]&&$starttime[1]>$endtime[1])){
    new httpcode(400,'time error');
}
$id=$_POST['id'];
$sql = 'UPDATE events SET title=:title, start_time=:start_time, end_time=:end_time,content=:content WHERE id=:id';
$statement = $pdo->prepare($sql);
$statement ->bindValue(':id',$id,PDO::PARAM_INT);
$statement->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
$statement->bindValue(':start_time', $_POST['start_time'], PDO::PARAM_STR);	
$statement->bindValue(':end_time', $_POST['end_time'], PDO::PARAM_STR);
$statement->bindValue(':content', $_POST['content'], PDO::PARAM_STR);  
$statement->execute();
if($statement->execute()){
$sql='SELECT * FROM events WHERE id = :id';
$statement = $pdo->prepare($sql);
$statement ->bindValue(':id',$id,PDO::PARAM_INT);
$statement->execute();
$event=$statement->fetch(PDO::FETCH_ASSOC);
$event['start_time']=substr($event['start_time'],0,5);
echo json_encode($event);
};
