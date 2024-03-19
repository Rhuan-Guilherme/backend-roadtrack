<?php
header("Access-Control-Allow-Methods: POST"); // Permitir apenas métodos POST
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include('./ValidarToken.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $dataJson = file_get_contents("php://input");
  $dados = json_decode($dataJson);
  $token = $dados->token;
  $valida = validaToken($token);

  if(!$valida){
    echo 'token Invalido!';
  } else{
    $dadosToken = returnData($token);
    echo 'Token valido!';
    echo $dadosToken;
  }

}

if($_SERVER["REQUEST_METHOD"] === "GET"){
  echo "Cabeçalho Authorization: " . $_SERVER["HTTP_AUTHORIZATION"] . "\n";
  $token = $_SERVER["HTTP_AUTHORIZATION"];
  var_dump($token);

  if($token){
    $valida = validaToken($token);

    if(!$valida){
      echo 'Token Inválido!';
  } else {
      $dadosToken = returnData($token);
      echo 'Token Válido!';
      echo $dadosToken;
  }
  } else {
    echo 'Token não fornecido!';
  }
}