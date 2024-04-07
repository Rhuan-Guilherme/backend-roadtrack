<?php

class Ticket {
  private $conn;

  public function __construct(Database $connect) {
    $this->conn = $connect->getConnetion();
  }

  public function ticketsById($userId){
    try {
      $stmt = $this->conn->prepare("SELECT * FROM tickets WHERE user_id = ? ORDER BY id DESC LIMIT 50");
      $stmt->bindParam(1, $userId, PDO::PARAM_STR);
      $stmt->execute();

      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return json_encode($resultado);
    } catch (PDOException $e) {
      echo "Erro ao obter os usuários: " . $e->getMessage();
      return false;
    }
  }
}