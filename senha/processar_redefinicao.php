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
$token = $_POST['token'];
$sql = "SELECT * FROM usuarios WHERE token = '$token' AND expiracao_token > NOW()";

$result = $conexao->query($sql);

if ($result->num_rows > 0) {
    // Obter nova senha do formulário
    $novaSenha = $_POST['nova_senha'];

    // Atualizar a senha no banco de dados
    $row = $result->fetch_assoc();
    $userId = $row['id'];
    $hashedNovaSenha = password_hash($novaSenha, PASSWORD_DEFAULT);

    $updateSql = "UPDATE usuarios SET senha = '$hashedNovaSenha', token = NULL, expiracao_token = NULL WHERE id = $userId";

    if ($conexao->query($updateSql) === TRUE) {
        echo '<p class="text-xl leading-[1.5] text-gray-50 text-center">Sua senha foi redefinida com sucesso. <br> <a class="text-blue-500" href="https://n1track.com">Clique aqui para logar</a></p>';
    } else {
        echo 'Erro ao redefinir a senha: ' . $conexao->error;
    }
} else {
    echo '<p class="text-xl leading-[1.5] text-gray-50 text-center">Token inválido ou expirado.</p>';
}

// Fechar conexão
$conexao->close();
?>
</body>
</html>

