<?php
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cadastro.php');
    exit();
}

$nome  = $_POST['nome']  ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (!$nome || !$email || !$senha) {
    header('Location: ../cadastro.php?err=missing');
    exit();
}

$stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    header('Location: ../cadastro.php?err=exists');
    exit();
}

$hash = password_hash($senha, PASSWORD_DEFAULT);
$ins = $pdo->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)');
if ($ins->execute([$nome, $email, $hash])) {
    $id = $pdo->lastInsertId();
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id'    => $id,
        'nome'  => $nome,
        'email' => $email,
        'tipo'  => 'user'
    ];
    header('Location: ../home.php');
    exit();
} else {
    header('Location: ../cadastro.php?err=db');
    exit();
}
?>