<?php 

$url = isset($_GET['url']) ? $_GET['url'] : '/';

switch ($url) {
    case 'cadastro':
        require 'pages/cadastro.php';
        break;
    case 'validate':
        require 'pages/validate.php';
        break;
    case 'login':
        require 'pages/login.php';
        break;
    case 'usuarios':
        require 'pages/usuarios.php';
        break;
    case 'vips':
        require 'pages/vips.php';
        break;
    case 'tickets':
        require 'pages/tickets.php';
        break;
    case 'options':
        require 'pages/optionsTickets.php';
        break;
    case 'dados':
        require 'pages/dados.php';
        break;
    case 'senhas':
        require 'pages/senhas.php';
        break;
    case 'binds':
        require 'pages/binds.php';
        break;
    default:
        // Página de erro 404
        http_response_code(404);
        echo 'Página não encontrada';
}