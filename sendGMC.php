<?php
function sendGCM($fields) {
  $url = 'https://fcm.googleapis.com/fcm/send';
  $fields = json_encode ($fields);
  $headers = array (
    'Authorization: key=Your_Authorization_Key',
   'Content-Type: application/json'
  );
  $ch = curl_init ();
  curl_setopt ( $ch, CURLOPT_URL, $url );
  curl_setopt ( $ch, CURLOPT_POST, true );
  curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt ( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
  curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
  $result = curl_exec ( $ch );
  echo $result;
  curl_close ( $ch );
}