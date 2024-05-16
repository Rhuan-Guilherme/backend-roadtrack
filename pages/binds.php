<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

include('./Classes/Database.php');
include('./Classes/Binds.php');

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $user = new Binds($db);
  $json = $user->getData();
  echo $json;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $dataJson = file_get_contents("php://input");
  $data = json_decode($dataJson, true); 
  $user = new Binds($db);
  if($data["auto"] === true){
    $json = $user->returnAutoComplete($data["termo"]);
  } else{
    $json = $user->registerBindInfo($data);
  }
  echo $json;
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
  $id = $_GET["id"];
  $user = new Binds($db);
  $json = $user->deleteBindById($id);
  echo $json;
}

