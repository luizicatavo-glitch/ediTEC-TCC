<?php
require_once 'auth_check.php';
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $uid = $_SESSION['user']['id'];

    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

    if ($id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM artigos WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$id, $uid]);
            
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success']);
                exit;
            } else {
                header("Location: ../artigo/trabalhos.php?msg=Deletado"); 
                exit;
            }

        } catch (PDOException $e) {
            if ($isAjax) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
            die("Erro SQL: " . $e->getMessage());
        }
    }
}

header("Location: ../artigo/trabalhos.php");
exit;
?>