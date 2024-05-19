<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Redefinição de senha</title>
</head>
<body class="bg-gray-900 h-screen flex flex-col items-center justify-center gap-3">
<?php

require '../ConectBanco/bancoUsuarios.php';

// Verificar conexão
if ($conexao->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
}

// Verificar se o token está correto e não expirou
$token = $_GET['token'];
$sql = "SELECT * FROM usuarios WHERE token = '$token' AND expiracao_token > NOW()";

$result = $conexao->query($sql);

if ($result->num_rows > 0) {
    // Exibir formulário para redefinir a senha
    echo '<div class="flex flex-col gap-10 w-2/6">
          <div class="">
            <h1 class="text-7xl leading-[1] text-gray-50">Recuperação de <br> senha</h1>
            <p class="text-gray-50 mt-4">Para dar continuidade ao processo de recuperação de senha, por favor, insira o seu endereço de e-mail no campo abaixo.</p>
          </div>
          <div>
            <form class="flex flex-col gap-8" method="post" action="processar_redefinicao.php">
                <input type="hidden" name="token" value="' . $token . '">
              <div>
                <input type="password" name="nova_senha" placeholder="Senha" class="h-14 w-full rounded-full p-3 bg-gray-700 border-transparent text-lg text-gray-200">
              </div>
              
              <div>
                <button type="submit" class="h-14 w-40 bg-gradient-to-t from-[#0449A4] to-[#006EFF] rounded-full text-lg text-gray-100 font-bold">
                  Redefinir
                </button>
              </div>
            </form>
          </div>
    
        </div>';
} else { 
    echo '<p class="text-7xl leading-[1] text-gray-50">Token inválido ou expirado.</p>';
}

// Fechar conexão
$conexao->close();
?>
</body>
</html>

