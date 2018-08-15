<?php
include('config.php');
include('sendGMC.php');
if(!empty($_POST['notificationSubmit'])){
   $title=$_POST['title'];
   $msg=$_POST['msg'];
   $logo=$_POST['logo'];
   $name=$_POST['name']; 
   $url=$_POST['url'];
   if(strlen(trim($title))>1 && strlen(trim($msg))>1 && strlen(trim($logo))>1 && strlen(trim($name))>1 && strlen(trim($url))>1 )
   {
     if($gcmClass->insertNotification($title, $msg, $logo, $name, $url)){
       $registrationId = $gcmClass->getIDs();
       $total_rids=[];
       foreach($registrationId as $r){
          array_push($total_rids, $r->rid);
       }
    $fields = array('registration_ids'  => $total_rids);
    
    sendGCM($fields);
    echo "Done";
   }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
   <title></title>
</head>
<body>
   <form autocomplete="off" method="post" action="">
      <div>
         <label>Title</label>
         <input type="text" placeholder="Title" name="title">
      </div>
      <div >
         <label>Message</label>
         <textarea placeholder="Message" name="msg"></textarea>
      </div>
      <div >
         <label>Logo</label>
         <input type="text" placeholder="Logo" name="logo" value="">
      </div>
      <div >
         <label>Name</label>
         <input type="text" placeholder="Name" name="name">
      </div>
      <div >
         <label>URL</label>
         <input type="text" placeholder="URL" name="url">
      </div>
      <div >
         <input type="submit" value="Push Notification" name="notificationSubmit" />
      </div>
   </form>
</body>
</html>