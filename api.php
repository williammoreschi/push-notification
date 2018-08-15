<?php
include('config.php');
function getNotification(){
 $db = getDB();
 $sql1 = "SELECT title, msg, logo, url, name FROM notifications ORDER BY nid DESC LIMIT 1";
 $stmt1 = $db->prepare($sql1);
 $stmt1->execute();
 $notification = $stmt1->fetch(PDO::FETCH_OBJ); 
 $notification->action = $notification->url;
 $notification->click_action = $notification->url;
 if($notification){
   $notification = json_encode($notification);
   echo '{"data": ' .$notification . '}';
 }
}

function deleteGCM($rid) {
  $check = false;
  if($_SERVER['HTTP_ORIGIN'] && $_SERVER['HTTP_ORIGIN'] == "https://push.yourwesbite.info"){
    $check = true;
  }

  if($check){
   $db = getDB();
   $sql = "DELETE FROM GMC WHERE rid=:rid";
   $stmt = $db->prepare($sql);
   $stmt->bindParam("rid", $rid,PDO::PARAM_STR);
   $stmt->execute();
   echo '{"success":{"text":"done"}}';
 }
 else{
   echo '{"error":{"text":"No access"}}';
 }
}

function insertGCM($rid) {
  $check = false;
  if(!empty($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] == "http://yourwesbite.com"){
    $check = true;
  }
    $check = true;
  if($check){
   $db = getDB();
   $sql1 = "SELECT * FROM GMC WHERE rid=:rid";
   $stmt1 = $db->prepare($sql1);
   $stmt1->bindParam("rid", $rid,PDO::PARAM_STR);
   $stmt1->execute();
   $mainCount=$stmt1->rowCount();
   if($mainCount < 1){
    $sql = "INSERT INTO GMC(rid) VALUES (:rid)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam("rid", $rid,PDO::PARAM_STR);
    $stmt->execute();
    echo '{"success":{"text":"done"}}';
  }
  else{
    echo '{"success":{"text":"already users"}}';
  }
}
else{
 echo '{"error":{"text":"No access"}}';
}
}


if(!empty($_GET['funcao'])){
 switch ($_GET['funcao']) {
  case 'insertGCM':
  insertGCM($_GET['dado']);
  break;
  case 'deleteGCM':
  deleteGCM($_GET['dado']);
  break;
  case 'getNotification':
  getNotification();
  break;
} 
}
