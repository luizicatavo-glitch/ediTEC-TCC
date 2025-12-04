<?php
require_once 'config.php';
session_start();

if (isset($_SESSION['user'])) {
    header('Location: ../home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login_form.php');
    exit();
}

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (!$email || !$senha) {
    header('Location: ../login_form.php?err=missing');
    exit();
}

$stmt = $pdo->prepare('SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: ../login_form.php?err=notfound');
    exit();
}

if (password_verify($senha, $user['senha'])) {
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id'    => $user['id'],
        'nome'  => $user['nome'],
        'email' => $user['email'],
        'tipo'  => $user['tipo']
    ];
    header('Location: ../home.php');
    exit();
} else {
    header('Location: ../login_form.php?err=badpass');
    exit();
}
?>