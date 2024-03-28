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
        echo "Email ja cadastrado!";
        return false; //Email ja cadastrado
      }
  
      $senhaCriptografada = password_hash($this->senha, PASSWORD_DEFAULT);
      $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?,?)");
      $stmt->bindParam(1, $this->nome);
      $stmt->bindParam(2, $this->email);
      $stmt->bindParam(3, $senhaCriptografada);
  
      if ($stmt->execute()) {
        return true;
      } else {
        return false;
      }
    } else{
      echo "Preencha todos os campos corretamente";
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
      $stmt =  $this->conn->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = ?"); 
      $stmt->bindParam(1, $this->email); 
      $stmt->execute();
      
      if($stmt && $stmt->rowCount() != 0){
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if(password_verify($this->senha, $resultado['senha'])){
          $token = new Token();
          $result = $token->generateToken($resultado['id'], $resultado['nome'], $resultado['email']);
          echo json_encode(['token' => $result]);
        } else {
          echo json_encode(['error' => 'Usuario ou senha invalidos!']);
        }
      } else{
        echo json_encode(['error' => 'Usuario ou senha invalidos!']);
      }
    } else{
      echo json_encode(['error' => 'Preencha todos os campos']);
    }
  }
}
