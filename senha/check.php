<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Recuperação</title>
</head>
<body class="bg-gray-900 h-screen flex flex-col items-center justify-center gap-3">
    <?php
    require '../ConectBanco/bancoUsuarios.php';

    // Verificar conexão
    if ($conexao->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
    }

    // Validar o e-mail do formulário
    $email = $_POST['email'];

    // Gerar um token único
    $token = bin2hex(random_bytes(32));

    // Calcular a data de expiração do token (por exemplo, 1 hora a partir de agora)
    $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Atualizar o banco de dados com o token e a data de expiração
    $sql = "UPDATE usuarios SET token = '$token', expiracao_token = '$expiracao' WHERE email = '$email'";

    if ($conexao->query($sql) === TRUE) {
        // Enviar e-mail com o link de redefinição de senha
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        $from = "atendimento@n1track.com";
        $to = $email;
        $subject = "N1track - Redefinição de Senha";
        $message = "Clique no link a seguir para redefinir sua senha: http://n1track.com/senha/redefinirSenha.php?token=$token";
        $headers = "From: " . $from;
        mail($to, $subject, $message, $headers);

        echo '<p class="text-xl leading-[1.5] text-gray-50 text-center">
        Foi enviado um e-mail contendo as instruções necessárias para redefinir sua senha. Por favor, verifique a caixa de entrada do seu e-mail. <br> Lembre-se de conferir as pastas de spam ou lixeira, pois os e-mails podem ser direcionados para essas áreas.</p>';
    } else {
        echo '<p class="text-xl leading-[1] text-gray-50">Erro ao processar a solicitação: ' . $conexao->error . "</p>";
    }

    // Fechar conexão
    $conexao->close();
    ?>

    <a href="https://n1track.com" class="text-lg leading-[1.5] text-blue-500 text-center">Voltar para página principal</a>
</body>
</html>





