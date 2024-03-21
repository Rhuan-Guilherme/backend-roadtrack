<?php

class Token{
  private $token;
  private $chave = 'teste';


  public function generateToken($id ,$nome, $email){
    $header = [
      'alg' => 'HS256',
      'type' => 'JWT'
    ];
    $header = json_encode($header);
    $header = base64_encode($header);

    $duracao = time() + (7 * 24 * 60* 60);
    $payload = [
      // 'iss' => 'localhost',
      // 'aud' => 'localhost',
      'exp' => $duracao,
      'id' => $id,
      'nome' => $nome,
      'email' => $email,
    ];
    $payload = json_encode($payload);
    $payload = base64_encode($payload);

    $assinatura = hash_hmac('sha256', "$header.$payload", $this->chave, true);
    $assinatura = base64_encode($assinatura);
    $this->token = $header.$payload.$assinatura;
    return "$header.$payload.$assinatura";
  }

  public function validaToken($token) {
    $tokenArray = explode('.', $token);
  
    $header = $tokenArray[0];
    $payload = $tokenArray[1];
    $assinatura = $tokenArray[2];
  
    $validarAssinatura = hash_hmac('sha256', "$header.$payload", $this->chave, true);
    $validarAssinatura = base64_encode($validarAssinatura);
    
  
    if($assinatura == $validarAssinatura){
      $dadosToken = json_decode(base64_decode($payload)); // Decodificar o payload como JSON
  
      if($dadosToken->exp > time()){
        return true;
      } else{
        return false;
      }
    } else{
      return false;
    }
  }

  public function returnData($token){
    $tokenArray = explode('.', $token);
    $payload = $tokenArray[1];
    $dadosToken = json_decode(base64_decode($payload));
    return json_encode($dadosToken);
  }
  
}