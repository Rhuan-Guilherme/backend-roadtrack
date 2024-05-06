<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

include('./Classes/Database.php');
include('./Classes/Ticket.php');

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] === "PUT") {
  $dataJson = file_get_contents("php://input");
  $value = json_decode($dataJson, true); 
  $id = $_GET["id"];
  $tikect = new Ticket($db);
  if($value["remove"] == true){
    $json = $tikect->reabreTicket($id);
     echo $json;
  } else{
    $json = $tikect->concluiTicket($id);
     echo $json;
  }
}