<?php
header("Access-Control-Allow-Methods: POST"); // Permitir apenas métodos POST
header("Access-Control-Allow-Headers: Content-Type"); // Permitir apenas o cabeçalho Content-Type

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