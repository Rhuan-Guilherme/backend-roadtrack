<?php

class Database
{
  private $username = "root";
  private $password = "";
  private $conn;

  public function __construct()
  {
    try {
      $this->conn = new PDO('mysql:host=localhost;dbname=roadtrack', $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $error) {
      echo "Erro na conexão: " . $error->getMessage();
    }
  }

  public function getConnetion()
  {
    return $this->conn;
  }
}
