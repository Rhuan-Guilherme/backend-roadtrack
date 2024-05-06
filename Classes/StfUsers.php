<?php

class StfUsers {
  private $conn;

  public function __construct(Database $connect) {
    $this->conn = $connect->getConnetion();
  }

  public function getData() {
    try {
      $stmt = $this->conn->prepare("SELECT * FROM stf_users ORDER BY id DESC");
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

      http_response_code(200);
      return json_encode($resultado);
    } catch (PDOException $e) {
      echo "Erro ao obter os usuários: " . $e->getMessage();
      return false;
    }
  }

  public function returnAutoComplete($termo){
    try {
      $stmt =  $this->conn->prepare("SELECT login, name, cargo, area, vip FROM stf_users WHERE login LIKE ?");
      $term = $termo . "%";; // Adiciona o caractere de porcentagem para buscar todas as ocorrências do termo
        $stmt->bindParam(1, $term, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = array();
        
        foreach ($result as $row) {
          $userData = array(
            'login' => $row['login'],
            'name' => $row['name'],
            'cargo' => $row['cargo'],
            'area' => $row['area'],
            'vip' => $row['vip']
        );
        // Adiciona o array associativo ao array principal
        $data[] = $userData;
        }
        
        return json_encode($data);
    } catch (PDOException $e) {
      echo "Erro ao obter os usuários: " . $e->getMessage();
      return false;
    }
  }

  public function registerUser($data){
    try{
      $stmt = $this->conn->prepare("INSERT INTO stf_users (login, name, cargo, area) VALUES (?, ?, ?, ?)");
      $stmt->bindParam(1, $data['login']);
      $stmt->bindParam(2, $data['name']);
      $stmt->bindParam(3, $data['cargo']);
      $stmt->bindParam(4, $data['area']);
      if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'usuario cadastrado com sucesso']);
        return true;
      }
    } catch (PDOException $e) {
      echo "Erro ao atualizar o usuário: " . $e->getMessage();
      return false;
    }
  }

  public function atualizaVip($login){
    try{
      $stmt = $this->conn->prepare("UPDATE stf_users SET vip = 'sim' WHERE login = :login");
      $stmt->bindParam(':login', $login, PDO::PARAM_STR);
      if ($stmt->execute()) {
        http_response_code(200);
        return json_encode(['message' => 'Usuário alterado como VIP']);
      } else{
        http_response_code(400);
        return json_encode(['message' => 'Erro ao alterar']);
      }
    } catch (PDOException $e) {
      return "Erro ao atualizar o usuário: " . $e->getMessage();
    }
  }

  public function removeVip($userId){
    try{
      $stmt = $this->conn->prepare("UPDATE stf_users SET vip = 'nao' WHERE id = :userId");
      $stmt->bindParam(':userId', $userId['id'], PDO::PARAM_INT);
      if ($stmt->execute()) {
        http_response_code(200);
        return json_encode(['message' => 'Usuário retirado da lista de VIPs']);
      } else{
        http_response_code(400);
        return json_encode(['message' => 'Erro ao alterar']);
      }
    } catch (PDOException $e) {
      return "Erro ao atualizar o usuário: " . $e->getMessage();
    }
  }

  public function returnVips(){
    try {
      $stmt = $this->conn->prepare("SELECT * FROM stf_users WHERE vip = 'sim' ORDER BY id DESC");
      $stmt->execute();

      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return json_encode($resultado);
    } catch (PDOException $e) {
      echo "Erro ao obter os usuários: " . $e->getMessage();
      return false;
    }
  }
}