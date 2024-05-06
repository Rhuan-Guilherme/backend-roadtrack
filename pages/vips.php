<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET");
header("Access-Control-Allow-Headers: Content-Type");

include('./Classes/Database.php');
include('./Classes/StfUsers.php');

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] === "PUT") {
  $dataJson = file_get_contents("php://input");
  $data = json_decode($dataJson, true); 
  $user = new StfUsers($db);
  if($data["remove"] == true){
    $json = $user->removeVip($data); 
  } else{
    $json = $user->atualizaVip($data["login"]); 
  }
  echo $json;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $user = new StfUsers($db);
  $json = $user->returnVips();
  echo $json;
}
