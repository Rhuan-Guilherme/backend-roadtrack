<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

include('./Classes/Database.php');
include('./Classes/Ticket.php');

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $id = $_GET["id"];
  $user = new Ticket($db);
  $json = $user->ticketsById($id);
  echo $json;
}