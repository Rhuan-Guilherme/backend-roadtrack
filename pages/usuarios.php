<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

include('./Classes/Database.php');
include('./Classes/StfUsers.php');

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $user = new StfUsers($db);
  $json = $user->getData();
  echo $json;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $dataJson = file_get_contents("php://input");
  $data = json_decode($dataJson, true); 
  $user = new StfUsers($db);
  if($data["add"] === true){
    $json = $user->registerUser($data); 
  } else{
    $json = $user->returnAutoComplete($data["termo"]);
  }
  echo $json;
}

// if ($_SERVER["REQUEST_METHOD"] === "POST") {

//   $dataJson = file_get_contents("php://input");
//   $data = json_decode($dataJson, true); 
//   $user = new StfUsers($db);
//   $json = $user->registerUser($data); 
//   echo $json;
// }

