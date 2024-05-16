<?php

class Binds {
  private $conn;

  public function __construct(Database $connect) {
    $this->conn = $connect->getConnetion();
  }

  public function getData() {
    try {
      $stmt = $this->conn->prepare("SELECT * FROM infobinds ORDER BY id DESC");
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

      http_response_code(200);
      return json_encode($resultado);
    } catch (PDOException $e) {
      echo "Erro ao obter binds de informações: " . $e->getMessage();
      return false;
    }
  }

  public function registerBindInfo($data){
        try{
            $stmt = $this->conn->prepare("INSERT INTO infobinds (nome, informacao) VALUES (?, ?)");
            $stmt->bindParam(1, $data['nome']);
            $stmt->bindParam(2, $data['informacao']);
            
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(['message' => 'Bind de informação cadastrada com sucesso']);
            }
        } catch (PDOException $e) {
            echo "Erro ao atualizar o usuário: " . $e->getMessage();
        }
    }

      public function returnAutoComplete($termo){
        try {
          $stmt =  $this->conn->prepare("SELECT nome, informacao FROM infobinds WHERE nome LIKE ?");
          $term = $termo . "%";; // Adiciona o caractere de porcentagem para buscar todas as ocorrências do termo
            $stmt->bindParam(1, $term, PDO::PARAM_STR);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $data = array();
            
            foreach ($result as $row) {
              $userData = array(
                'nome' => $row['nome'],
                'informacao' => $row['informacao']
            );
            // Adiciona o array associativo ao array principal
            $data[] = $userData;
            }
        
        return json_encode($data);
    } catch (PDOException $e) {
      echo "Erro ao obter binds: " . $e->getMessage();
      return false;
    }
  }

  public function deleteBindById($id){
    try{
      $stmt = $this->conn->prepare("DELETE FROM infobinds WHERE id = ?");
      $stmt->bindParam(1, $id, PDO::PARAM_INT);
      $stmt->execute();
      return json_encode(["success" => true]);
    } catch(PDOException $e) {
      return json_encode(["success" => false]);
    }
  }

}


