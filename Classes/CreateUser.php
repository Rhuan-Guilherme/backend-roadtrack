<?php

include('./Database.php');

class CreateUser
{
  private $conn;

  public function __construct(Database $connect)
  {
    $this->conn = $connect->getConnetion();
  }

  public function registerUser($dadosJson)
  {
    $dados = json_decode($dadosJson);

    if ($dados === null) {
      return false;
    }

    $nome = $dados->nome;
    $email = $dados->email;
    $senha = $dados->senha;

    $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bindParam(1, $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      echo "Email ja cadastrado!";
      return false; //Email ja cadastrado
    }

    $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?,?)");
    $stmt->bindParam(1, $nome);
    $stmt->bindParam(2, $email);
    $stmt->bindParam(3, $senha);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function getUsers()
  {
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
}
