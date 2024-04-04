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

      return json_encode($resultado);
    } catch (PDOException $e) {
      echo "Erro ao obter os usuários: " . $e->getMessage();
      return false;
    }
  }

  public function returnAutoComplete($termo){
    try {
      $stmt =  $this->conn->prepare("SELECT name, cargo, area, vip FROM stf_users WHERE login LIKE ?");
      $term = $termo . "%";; // Adiciona o caractere de porcentagem para buscar todas as ocorrências do termo
        $stmt->bindParam(1, $term, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = array();
        
        foreach ($result as $row) {
          $userData = array(
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

  public function atualizaVip($userId){
    try{
      $stmt = $this->conn->prepare("UPDATE stf_users SET vip = 'sim' WHERE id = :userId");
      $stmt->bindParam(':userId', $userId['id'], PDO::PARAM_INT);
      if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'alterado como vip']);
        return true;
      } else{
        echo json_encode(['message' => 'erro ao alterar']);
      }
    } catch (PDOException $e) {
      echo "Erro ao atualizar o usuário: " . $e->getMessage();
      return false;
    }
  }

  public function removeVip($userId){
    try{
      $stmt = $this->conn->prepare("UPDATE stf_users SET vip = 'nao' WHERE id = :userId");
      $stmt->bindParam(':userId', $userId['id'], PDO::PARAM_INT);
      if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'alterado como nao vip']);
        return true;
      } else{
        echo json_encode(['message' => 'erro ao alterar']);
      }
    } catch (PDOException $e) {
      echo "Erro ao atualizar o usuário: " . $e->getMessage();
      return false;
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