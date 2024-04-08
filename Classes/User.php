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
      http_response_code(200);
      return json_encode($resultado);
    } catch (PDOException $e) {
      http_response_code(400);
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

  public function alterDataUser($dataJson){
    $data = json_decode($dataJson);
    try {
      $stmt = $this->conn->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
      $stmt->bindParam(1, $data->nome, PDO::PARAM_STR); 
      $stmt->bindParam(2, $data->email, PDO::PARAM_STR);
      $stmt->bindParam(3, $data->id, PDO::PARAM_INT);
      $stmt->execute();


      http_response_code(200);
      return json_encode(['message' => 'Aletrado com sucesso']);
    } catch (PDOException $e) {
      http_response_code(400);
      echo "Erro ao obter os usuários: " . $e->getMessage();
      return false;
    }
  }

  public function alterPassword($dataJson){
    $data = json_decode($dataJson);

    try {
        // Verifique se todos os campos necessários foram fornecidos
        if (!isset($data->id) || !isset($data->senha_antiga) || !isset($data->nova_senha)) {
            throw new Exception("Campos obrigatórios não fornecidos");
        }

        // Primeiro, verifique se a senha antiga está correta
        $stmt = $this->conn->prepare("SELECT senha FROM usuarios WHERE id = ?");
        $stmt->bindParam(1, $data->id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("Usuário não encontrado");
        }

        $senha_hash = $user['senha'];
        if (!password_verify($data->senha_antiga, $senha_hash)) {
            throw new Exception("Senha antiga incorreta");
        }

        // Se a senha antiga estiver correta, atualize para a nova senha
        $nova_senha_hash = password_hash($data->nova_senha, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        $stmt->bindParam(1, $nova_senha_hash, PDO::PARAM_STR);
        $stmt->bindParam(2, $data->id, PDO::PARAM_INT);
        $stmt->execute();

        http_response_code(200);
        return json_encode(['message' => 'Senha alterada com sucesso']);
    } catch (Exception $e) {
        http_response_code(400);
        return json_encode(['error' => $e->getMessage()]);
    }
}
}
