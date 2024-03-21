<?php
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

  if(!$valida){
    echo 'token Invalido!';
  } else{
    $dadosToken = returnData($token);
    echo 'Token valido!';
    echo $dadosToken;
  }

}

if($_SERVER["REQUEST_METHOD"] === "GET"){
  $tokenJson = $_SERVER["HTTP_AUTHORIZATION"];

  if($tokenJson){
    $token = new Token();
    $valida = $token->validaToken($tokenJson);

    if(!$valida){
      echo 'Token Inválido!';
  } else {
      $dadosToken = $token->returnData($tokenJson);
      echo 'Token Válido!';
      echo $dadosToken;
  }
  } else {
    echo 'Token não fornecido!';
  }
}