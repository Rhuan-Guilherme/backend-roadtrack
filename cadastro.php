<?php
header("Access-Control-Allow-Methods: POST"); // Permitir apenas métodos POST
header("Access-Control-Allow-Headers: Content-Type"); // Permitir apenas o cabeçalho Content-Type

include('./Classes/Database.php');
include('./Classes/User.php');

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $dataJson = file_get_contents("php://input");
  $registerUser = new Users($db);
  $reseult = $registerUser->registerUser($dataJson);
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $registerUser = new Users($db);
  $usuariosJson = $registerUser->getUsers();
  echo $usuariosJson;
}
