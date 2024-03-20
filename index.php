<?php 

$url = isset($_GET['url']) ? $_GET['url'] : '/';

switch ($url) {
    case 'cadastro':
        require 'pages/cadastro.php';
        break;
    case 'teste':
        require 'pages/teste.php';
        break;
    case 'login':
        require 'pages/login.php';
        break;
    default:
        // Página de erro 404
        http_response_code(404);
        echo 'Página não encontrada';
}