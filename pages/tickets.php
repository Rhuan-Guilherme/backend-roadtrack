<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

include('./Classes/Database.php');
include('./Classes/Ticket.php');

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $id = $_GET["id"];
  $tikect = new Ticket($db);
  $json = $tikect->ticketsById($id);
  echo $json;
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
  $id = $_GET["id"];
  $tikect = new Ticket($db);
  $json = $tikect->deleteTicketsById($id);
  echo $json;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $dataJson = file_get_contents("php://input");
  $tikect = new Ticket($db);
  $json = $tikect->createTicketN1($dataJson);
  echo $json;
}

if ($_SERVER["REQUEST_METHOD"] === "PUT") {
  $dataJson = file_get_contents("php://input");
  $tikect = new Ticket($db);
  $json = $tikect->updateTicketN1($dataJson);
  echo $json;
}