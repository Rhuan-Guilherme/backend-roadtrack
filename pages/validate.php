<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST"); // Permitir apenas métodos POST
header("Access-Control-Allow-Headers: Content-Type, Authorization");

chdir(__DIR__ . '/../Classes');
include('Token.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $dataJson = file_get_contents("php://input");
  $dados = json_decode($dataJson);
  $tokenJson = $dados->token;
  $token = new Token();
  $valida = $token->validaToken($tokenJson);
  $jsonValidate = json_decode($valida);
  if($jsonValidate->check === true) {
    echo json_encode(['message' => 'Token validado', 'check' => true] );
  } else{
    echo json_encode(['message' => 'Token validado', 'check' => false] );
  }
}

if($_SERVER["REQUEST_METHOD"] === "GET"){
  $tokenJson = $_SERVER["HTTP_AUTHORIZATION"];
  if($tokenJson){
    $token = new Token();
    $valida = $token->validaToken($tokenJson);
    echo $valida;
  }
}