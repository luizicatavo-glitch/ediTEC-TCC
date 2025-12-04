<?php
session_start();

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
    session_unset();
    session_destroy();
    header('Location: index.php?msg=deslogado');
    exit();
}
?>