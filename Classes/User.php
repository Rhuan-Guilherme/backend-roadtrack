<?php

include('Token.php');

class Users {
  private $conn;
  private $nome;
  private $email;
  private $senha;

  public function __construct(Database $connect) {
    $this->conn = $connect->getConnetion();
  }

  public function registerUser($dadosJson){
    $dados = json_decode($dadosJson);

    if ($dados === null) {
      echo 'Nenhum dado fornecido';
      return false;
    }

    $this->nome = $dados->nome;
    $this->email = $dados->email;
    $this->senha = $dados->senha;

    if(strlen($this->nome) > 0 && strlen($this->email) && strlen($this->senha)){
      $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = ?");
      $stmt->bindParam(1, $this->email);
      $stmt->execute();
  
      if ($stmt->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(['message' => 'email ja cadastrado']);
        return false; //Email ja cadastrado
      }
  
      $senhaCriptografada = password_hash($this->senha, PASSWORD_DEFAULT);
      $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?,?)");
      $stmt->bindParam(1, $this->nome);
      $stmt->bindParam(2, $this->email);
      $stmt->bindParam(3, $senhaCriptografada);
  
      if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'usuario cadastrado com sucesso']);
        return true;
      } else {
        return false;
      }
    } else{
      http_response_code(400);
      echo json_encode(['message' => 'preencha todos os campos corretamente']);
    }

  }

  public function getUsers() {
    try {
      $stmt = $this->conn->prepare("SELECT * FROM usuarios ORDER BY id DESC");
      $stmt->execute();

      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return json_encode($resultado);
    } catch (PDOException $e) {
      echo "Erro ao obter os usuários: " . $e->getMessage();
      return false;
    }
  }

  public function authUser($dadosJson){
    $dados = json_decode($dadosJson);
  
    if ($dados === null) {
      echo 'Nenhum dado fornecido';
      return false;
    }
    
    $this->email = $dados->email;
    $this->senha = $dados->senha;
  
    if(strlen($this->email) && strlen($this->senha)){
      $stmt =  $this->conn->prepare("SELECT id, nome, email, senha, autorizado FROM usuarios WHERE email = ?"); 
      $stmt->bindParam(1, $this->email); 
      $stmt->execute();
      
      if($stmt && $stmt->rowCount() != 0){
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if(password_verify($this->senha, $resultado['senha'])){
          $token = new Token();
          $result = $token->generateToken($resultado['id'], $resultado['nome'], $resultado['email'], $resultado['autorizado']);
          echo json_encode(['token' => $result]);
        } else {
          http_response_code(400);
          echo json_encode(['error' => 'Usuario ou senha invalidos!']);
        }
      } else{
        http_response_code(400);
        echo json_encode(['error' => 'Usuario ou senha invalidos!']);
      }
    } else{
      http_response_code(400);
      echo json_encode(['error' => 'Preencha todos os campos']);
    }
  }
}
