<?php
require_once 'auth_check.php';
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../artigo/trabalhos.php');
    exit;
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Erro de segurança (CSRF). Tente recarregar a página.');
}

$uid = $_SESSION['user']['id'];
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$titulo      = $_POST['titulo'] ?? '';
$instituicao = $_POST['instituicao'] ?? '';
$unidade     = $_POST['unidade'] ?? '';
$curso       = $_POST['curso'] ?? '';
$rendered    = $_POST['rendered_html'] ?? '';
$sectionsJSON = $_POST['sections_json'] ?? '{}';

$camposTexto = [
    'autor', 'orientador', 'resumo', 'palavras_chave', 
    'introducao', 'objetivos', 'referencial', 'metodologia', 
    'resultados', 'discussao', 'conclusao', 'referencias'
];

$dados = [
    'usuario_id' => $uid,
    'titulo' => $titulo,
    'instituicao' => $instituicao,
    'unidade' => $unidade,
    'curso' => $curso,
    'rendered_html' => $rendered,
    'sections_json' => $sectionsJSON
];

foreach ($camposTexto as $campo) {
    $raw = $_POST[$campo] ?? '';
    $dados[$campo] = ($raw !== '') ? encrypt_text($raw) : '';
}

try {
    if ($id) {
        $check = $pdo->prepare("SELECT id FROM artigos WHERE id = ? AND usuario_id = ?");
        $check->execute([$id, $uid]);
        if (!$check->fetch()) die("Artigo não encontrado ou acesso negado.");

        $sets = [];
        $vals = [];
        foreach ($dados as $k => $v) {
            if ($k === 'usuario_id') continue;
            $sets[] = "$k = ?";
            $vals[] = $v;
        }
        $vals[] = $id;
        $vals[] = $uid;

        $stmt = $pdo->prepare("UPDATE artigos SET " . implode(', ', $sets) . " WHERE id = ? AND usuario_id = ?");
        $stmt->execute($vals);
        $redirId = $id;
        $msg = "Artigo atualizado!";

    } else {
        $cols = implode(', ', array_keys($dados));
        $places = implode(', ', array_fill(0, count($dados), '?'));
        
        $stmt = $pdo->prepare("INSERT INTO artigos ($cols) VALUES ($places)");
        $stmt->execute(array_values($dados));
        $redirId = $pdo->lastInsertId();
        $msg = "Artigo criado!";
    }

    header("Location: ../artigo/pagina.php?id=$redirId&msg=" . urlencode($msg));
    exit;

} catch (PDOException $e) {
    die("Erro no Banco de Dados: " . $e->getMessage());
}
?>