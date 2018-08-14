<?php
class gcmClass
{
   public function getIDs()
  {
   try{
     $db = getDB();
     $stmt = $db->prepare("SELECT rid FROM GMC");
     $stmt->execute();
     $data=$stmt->fetchALL(PDO::FETCH_OBJ);
     return $data;
   }
   catch(PDOException $e) {
   echo '{"error":{"text":'. $e->getMessage() .'}}';
   }
 }

public function insertNotification($a, $b, $c, $d, $e)
{
  try{
   $db = getDB();
   $stmt = $db->prepare("INSERT INTO notifications(title, msg, logo, name,url) VALUES(:title,:msg,:logo,:name,:url)");
   $stmt->bindParam("title", $a,PDO::PARAM_STR) ;
   $stmt->bindParam("msg", $b,PDO::PARAM_STR) ;
   $stmt->bindParam("logo", $c,PDO::PARAM_STR) ;
   $stmt->bindParam("name", $d,PDO::PARAM_STR) ;
   $stmt->bindParam("url", $e,PDO::PARAM_STR) ;
   $stmt->execute();
   return true;
  }
  catch(PDOException $e) {
   echo '{"error":{"text":'. $e->getMessage() .'}}';
   }
 }
}
?>