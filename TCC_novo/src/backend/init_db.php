<?php
require_once 'config.php';

$conn = db_connect(false);
$sql = "CREATE DATABASE IF NOT EXISTS `".DB_NAME."` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if($conn->query($sql) === TRUE){
    echo "Database '".DB_NAME."' created or already exists.<br>";
} else {
    die('Error creating database: '.$conn->error);
}

$conn->select_db(DB_NAME);

$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin','user') DEFAULT 'user',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if($conn->query($sql) === TRUE){
    echo "Table 'usuarios' created or already exists.<br>";
} else {
    die('Error creating table: '.$conn->error);
}

$admin_email = 'admin@plataforma.local';
$res = $conn->prepare('SELECT id FROM usuarios WHERE email = ?');
$res;
$res->execute();
$res->store_result();
if($res->num_rows == 0){
    $nome = 'Administrador Inicial';
    $email = $admin_email;
    $senha_plain = 'Admin@123';
    $senha_hash = password_hash($senha_plain, PASSWORD_DEFAULT);
    $ins = $conn->prepare('INSERT INTO usuarios (nome,email,senha,tipo) VALUES (?,?,?,"admin")');
    $ins;
    if($ins->execute()){
        echo "Admin user created: {$email} (senha: Admin@123). Please change the password after first login.<br>";
    } else {
        echo 'Error inserting admin: '.$conn->error;
    }
} else {
    echo "Admin user already exists.<br>";
}

echo "<br>Init finished. For security, delete init_db.php after use.";

$conn->close();
?>