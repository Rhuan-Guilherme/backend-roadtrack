<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

include('./Classes/Database.php');
include('./Classes/Ticket.php');

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $id = $_GET["id"];
  $tikect = new Ticket($db);
  $json = $tikect->ticketsById($id);
  echo $json;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $dataJson = file_get_contents("php://input");
  $tikect = new Ticket($db);
  $json = $tikect->createTicketN1($dataJson);
  echo $json;
}