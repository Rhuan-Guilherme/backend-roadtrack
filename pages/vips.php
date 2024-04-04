<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

include('./Classes/Database.php');
include('./Classes/StfUsers.php');

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] === "PUT") {
  $dataJson = file_get_contents("php://input");
  $id = json_decode($dataJson, true); 
  $user = new StfUsers($db);
  var_dump($id);
  if($id["remove"] == true){
    $json = $user->removeVip($id); 
  } else{
    $json = $user->atualizaVip($id); 
  }
  echo $json;
}


if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $user = new StfUsers($db);
  $json = $user->returnVips();
  echo $json;
}
