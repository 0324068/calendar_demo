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
$sql = 'INSERT INTO events (title, year, mon, `date`, start_time, end_time, content)
				VALUES (:title, :year, :mon, :date, :start_time, :end_time, :content)';
$statement = $pdo->prepare($sql);
$statement->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
$statement->bindValue(':year', $_POST['year'], PDO::PARAM_INT);
$statement->bindValue(':mon', $_POST['mon'], PDO::PARAM_INT);
$statement->bindValue(':date', $_POST['date'], PDO::PARAM_INT);
$statement->bindValue(':start_time', $_POST['start_time'], PDO::PARAM_STR);	
$statement->bindValue(':end_time', $_POST['end_time'], PDO::PARAM_STR);
$statement->bindValue(':content', $_POST['content'], PDO::PARAM_STR);  

if($statement->execute()){
    $id = $pdo->lastInsertId();
    $sql='SELECT id,title,start_time FROM events WHERE id = :id';
    $statement = $pdo->prepare($sql);
    $statement ->bindValue(':id',$id,PDO::PARAM_INT);
    $statement->execute();
    $event=$statement->fetch(PDO::FETCH_ASSOC);
    $event['start_time']=substr($event['start_time'],0,5);
    echo json_encode($event);
    
}
else {
    echo 'no';  
}