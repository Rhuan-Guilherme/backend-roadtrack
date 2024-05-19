<?php

class Ticket {
  private $conn;

  public function __construct(Database $connect) {
    $this->conn = $connect->getConnetion();
  }

  public function ticketsById($userId, $limit, $status){
    try {
      if($status === 'all' || $status == false){
        $stmt = $this->conn->prepare("SELECT * FROM tickets WHERE user_id = ? ORDER BY id DESC LIMIT $limit");
        $stmt->bindParam(1, $userId, PDO::PARAM_STR);
      } else{
        $stmt = $this->conn->prepare("SELECT * FROM tickets WHERE user_id = ? AND status = ? ORDER BY id DESC LIMIT $limit");    
        $stmt->bindParam(1, $userId, PDO::PARAM_STR);
        $stmt->bindParam(2, $status, PDO::PARAM_STR);    
      }
      
      $stmt->execute();

      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return json_encode($resultado);
    } catch (PDOException $e) {
      echo "Erro ao obter os usuários: " . $e->getMessage();
      return false;
    }
  }
  
  public function deleteTicketsById($id){
    try{
      $stmt = $this->conn->prepare("DELETE FROM tickets WHERE id = ?");
      $stmt->bindParam(1, $id, PDO::PARAM_INT);
      $stmt->execute();
      return json_encode(["success" => true]);
    } catch(PDOException $e) {
      return json_encode(["success" => false]);
    }
  }

  public function createTicketN1($json) {
    $body = json_decode($json);
    try {
        $userId = isset($body->user_id) ? $body->user_id : '';
        $criador = isset($body->criador) ? $body->criador : '';
        $nome = isset($body->nome) ? $body->nome : '';
        $login = isset($body->login) ? $body->login : '';
        $ramal = isset($body->ramal) ? $body->ramal : '';
        $area = isset($body->area) ? $body->area : '';
        $patrimonio = isset($body->patrimonio) ? $body->patrimonio : '';
        $informacao = isset($body->informacao) ? $body->informacao : '';
        $local = isset($body->local) ? $body->local : '';
        $chamado = isset($body->chamado) ? $body->chamado : '';
        $destinatario = isset($body->destinatario) ? $body->destinatario : '';
        $created_at = isset($body->created_at) ? $body->created_at : ''; 
        $tipo = isset($body->tipo) ? $body->tipo : '';
        $vip = isset($body->vip) ? $body->vip : '';

        $stmt = $this->conn->prepare("INSERT INTO tickets (user_id, criador, nome, login, ramal, area, patrimonio, informacao, local, chamado, destinatario, status, created_at, tipo, vip) VALUES (:userId, :criador, :nome, :login, :ramal, :area, :patrimonio, :informacao, :local, :chamado, :destinatario, 'Aberto', :created_at, :tipo, :vip)");

        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':criador', $criador, PDO::PARAM_STR);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->bindParam(':ramal', $ramal, PDO::PARAM_STR);
        $stmt->bindParam(':area', $area, PDO::PARAM_STR);
        $stmt->bindParam(':patrimonio', $patrimonio, PDO::PARAM_STR);
        $stmt->bindParam(':informacao', $informacao, PDO::PARAM_STR);
        $stmt->bindParam(':local', $local, PDO::PARAM_STR);
        $stmt->bindParam(':chamado', $chamado, PDO::PARAM_STR);
        $stmt->bindParam(':destinatario', $destinatario, PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':vip', $vip, PDO::PARAM_STR);

        $stmt->execute();

        return json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo "Erro ao criar ticket: " . $e->getMessage();
        return json_encode(["success" => false]);
    }
}

public function concluiTicket($id) {
  try {
      $stmt = $this->conn->prepare("UPDATE tickets SET status = 'Fechado' WHERE id = :ticketId");
      $stmt->bindParam(':ticketId', $id, PDO::PARAM_INT);
      $stmt->execute();

      return json_encode(["success" => true]);
  } catch (PDOException $e) {
      echo "Erro ao atualizar ticket: " . $e->getMessage();
      return json_encode(["success" => false]);
  }
}

public function reabreTicket($id) {
  try {
      $stmt = $this->conn->prepare("UPDATE tickets SET status = 'Aberto' WHERE id = :ticketId");
      $stmt->bindParam(':ticketId', $id, PDO::PARAM_INT);
      $stmt->execute();

      return json_encode(["success" => true]);
  } catch (PDOException $e) {
      echo "Erro ao atualizar ticket: " . $e->getMessage();
      return json_encode(["success" => false]);
  }
}

public function updateTicketN1($json) {
  $body = json_decode($json);
  try {
      $ticketId = isset($body->ticket_id) ? $body->ticket_id : '';
      $nome = isset($body->nome) ? $body->nome : '';
      $login = isset($body->login) ? $body->login : '';
      $ramal = isset($body->ramal) ? $body->ramal : '';
      $area = isset($body->area) ? $body->area : '';
      $patrimonio = isset($body->patrimonio) ? $body->patrimonio : '';
      $informacao = isset($body->informacao) ? $body->informacao : '';
      $local = isset($body->local) ? $body->local : '';
      $chamado = isset($body->chamado) ? $body->chamado : '';
      $destinatario = isset($body->destinatario) ? $body->destinatario : '';

      $stmt = $this->conn->prepare("UPDATE tickets SET nome = :nome, login = :login, ramal = :ramal, area = :area, patrimonio = :patrimonio, informacao = :informacao, local = :local, chamado = :chamado, destinatario = :destinatario WHERE id = :ticketId");

      $stmt->bindParam(':ticketId', $ticketId, PDO::PARAM_INT);
      $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
      $stmt->bindParam(':login', $login, PDO::PARAM_STR);
      $stmt->bindParam(':ramal', $ramal, PDO::PARAM_STR);
      $stmt->bindParam(':area', $area, PDO::PARAM_STR);
      $stmt->bindParam(':patrimonio', $patrimonio, PDO::PARAM_STR);
      $stmt->bindParam(':informacao', $informacao, PDO::PARAM_STR);
      $stmt->bindParam(':local', $local, PDO::PARAM_STR);
      $stmt->bindParam(':chamado', $chamado, PDO::PARAM_STR);
      $stmt->bindParam(':destinatario', $destinatario, PDO::PARAM_STR);

      $stmt->execute();

      return json_encode(["success" => true]);
  } catch (PDOException $e) {
      echo "Erro ao atualizar ticket: " . $e->getMessage();
      return json_encode(["success" => false]);
  }
}

public function countTicketsByUser(){
  try {
      $stmt = $this->conn->prepare("SELECT u.nome AS nome_usuario, COUNT(c.id) AS total_chamados 
      FROM usuarios u 
      LEFT JOIN tickets c ON u.id = c.user_id 
      GROUP BY u.id");
      $stmt->execute();

      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return json_encode($resultado);
  } catch (PDOException $e) {
      echo "Erro ao obter os tickets: " . $e->getMessage();
      return false;
  }
}
  
}

  
