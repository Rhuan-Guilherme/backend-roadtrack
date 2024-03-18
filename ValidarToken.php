<?php

function validaToken($token) {
  $tokenArray = explode('.', $token);

  $header = $tokenArray[0];
  $payload = $tokenArray[1];
  $assinatura = $tokenArray[2];

  $chave = 'teste';

  $validarAssinatura = hash_hmac('sha256', "$header.$payload", $chave, true);
  $validarAssinatura = base64_encode($validarAssinatura);
  

  if($assinatura == $validarAssinatura){
    $dadosToken = json_decode(base64_decode($payload)); // Decodificar o payload como JSON

    if($dadosToken->exp > time()){
      return true;
    } else{
      return false;
    }
  } else{
    echo 'nao';
    return false;
  }
}

function returnData($token){
  $tokenArray = explode('.', $token);
  $payload = $tokenArray[1];
  $dadosToken = json_decode(base64_decode($payload));
  return json_encode($dadosToken);
}
