<?php
$host = 'localhost';
$db   = 'plataforma';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Erro de conexão: ' . $e->getMessage());
}

if (!defined('ENCRYPTION_KEY')) {
    define('ENCRYPTION_KEY', '2eaff4aeb8eec8c62204ae85e03a6708');
}

function encrypt_text($plaintext){
    if ($plaintext === null || $plaintext === '') return $plaintext;
    $method = 'AES-256-CBC';
    $key = hash('sha256', ENCRYPTION_KEY, true);
    $ivlen = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $cipher = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
    if ($cipher === false) return false;
    return base64_encode($iv . $cipher);
}

function decrypt_text($b64){
    if ($b64 === null || $b64 === '') return $b64;

    $method = 'AES-256-CBC';
    $key = hash('sha256', ENCRYPTION_KEY, true);
    $ivlen = openssl_cipher_iv_length($method);

    $raw = base64_decode($b64, true);
    if ($raw !== false && strlen($raw) > $ivlen) {
        $iv = substr($raw, 0, $ivlen);
        $cipher = substr($raw, $ivlen);
        if (strlen($iv) === $ivlen && $cipher !== false && $cipher !== '') {
            $plain = openssl_decrypt($cipher, $method, $key, OPENSSL_RAW_DATA, $iv);
            if ($plain !== false) return $plain;
        }
    }

    if (strpos($b64, ':') !== false) {
        list($maybeIv, $maybeCipher) = explode(':', $b64, 2);
        if (ctype_xdigit($maybeIv) && ctype_xdigit($maybeCipher)) {
            $iv = @hex2bin($maybeIv);
            $cipher = @hex2bin($maybeCipher);
            if ($iv !== false && strlen($iv) === $ivlen && $cipher !== false && $cipher !== '') {
                $plain = openssl_decrypt($cipher, $method, $key, OPENSSL_RAW_DATA, $iv);
                if ($plain !== false) return $plain;
            }
        }
    }

    if (ctype_xdigit($b64) && strlen($b64) >= ($ivlen * 2 + 2)) {
        $rawHex = @hex2bin($b64);
        if ($rawHex !== false && strlen($rawHex) > $ivlen) {
            $iv = substr($rawHex, 0, $ivlen);
            $cipher = substr($rawHex, $ivlen);
            if (strlen($iv) === $ivlen) {
                $plain = openssl_decrypt($cipher, $method, $key, OPENSSL_RAW_DATA, $iv);
                if ($plain !== false) return $plain;
            }
        }
    }

    return false;
}