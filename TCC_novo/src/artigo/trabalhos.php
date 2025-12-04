<?php
require_once '../backend/auth_check.php';
require_once '../backend/config.php';

$user = $_SESSION['user'];

$filter = $_GET['filter'] ?? 'newest';
$searchInit = $_GET['search'] ?? '';

$orderBy = 'data_criacao DESC';

switch ($filter) {
    case 'oldest':
        $orderBy = 'data_criacao ASC';
        break;
    case 'modified':
        $orderBy = 'data_atualizacao DESC';
        break;
    case 'newest':
    default:
        $orderBy = 'data_criacao DESC';
        break;
}

try {
    $sql = "SELECT id, titulo, data_criacao, data_atualizacao 
            FROM artigos 
            WHERE usuario_id = :uid 
            ORDER BY $orderBy";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':uid' => $user['id']]);
    $artigos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($artigos as $k => $r){ 
        if(empty(trim($r['titulo']))) $artigos[$k]['titulo'] = 'Artigo ' . ($k+1); 
    }
} catch (PDOException $e) {
    die('Erro ao buscar artigos: ' . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Trabalhos</title>

<link rel="icon" href="../img/logo_azl.png" type="image/png">
<link rel="stylesheet" href="css/style.css">
<style>
.users-header {
    display: flex;
    align-items: flex-start; 
    justify-content: space-between;
    margin: 20px 0;
    gap: 10px;
    flex-wrap: wrap;
}

.controls-wrapper {
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: flex-end;
    margin-top: 10px; 
}

.search-input, .filter-select {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-family: Arial, sans-serif;
    font-size: 14px;
    width: 250px;
    box-sizing: border-box;
}

.filter-select { cursor: pointer; background-color: #fff; }

body {
    background: #f4f4f4;
    font-family: Arial, sans-serif;
}

button, input, select, textarea {
    font-family: Arial, sans-serif;
}

.container {
    max-width: 900px;
    margin: 20px auto;
    padding: 0 16px;
}
.btn-back {
    background:#1E88E5; color:#fff; padding:6px 14px; border-radius:6px; text-decoration:none; font-weight:bold; font-size: 14px;
}
.btn-back:hover { background:#1565C0; }

.list {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,.06);
    overflow: hidden;
}
.item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 16px;
    border-bottom: 1px solid #eee;
    transition: all 0.3s ease;
}
.item:last-child { border-bottom: none; }

.left-info {
    display: flex;
    flex-direction: column;
}
.left-info .title {
    font-weight: bold;
    color: #333;
}
.left-info .date {
    font-size: 0.85rem;
    color: #777;
    margin-top: 2px;
}
.date .badge-updated {
    color: #1e9201;
    font-weight: bold;
    font-size: 0.8em;
    margin-left: 5px;
}

.actions {
    display: flex;
    gap: 8px;
}
.actions a,
.actions button {
    padding: 6px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.btn-edit   { background:#1E88E5; color:#fff; }
.btn-edit:hover   { background:#1565C0; }
.btn-delete { background:#e53935; color:#fff; }
.btn-delete:hover { background:#c62828; }
</style>
</head>
<body>

<div class="container">
    <a href="../home.php" class="btn-back">⯇ Voltar</a>

    <div class="users-header">
        <h2 style="margin: 0; padding-top: 10px;">Trabalhos Salvos</h2> <div class="controls-wrapper">
            <input id="searchTrabalhos" class="search-input" placeholder="Procurar trabalhos..." value="<?= htmlspecialchars($searchInit) ?>" />
            
            <select id="sortFilter" class="filter-select">
                <option value="newest" <?= $filter === 'newest' ? 'selected' : '' ?>>Mais recente ao mais antigo</option>
                <option value="oldest" <?= $filter === 'oldest' ? 'selected' : '' ?>>Mais antigo ao mais recente</option>
                <option value="modified" <?= $filter === 'modified' ? 'selected' : '' ?>>Alterados recentemente</option>
            </select>
        </div>
    </div>

    <div class="list" id="articlesList">
    <?php if (empty($artigos)): ?>
        <div class="item">Nenhum trabalho salvo ainda.</div>
    <?php else: foreach ($artigos as $row): ?>
        <div class="item" data-id="<?= $row['id'] ?>">
            <div class="left-info">
                <div class="title"><?= htmlspecialchars(trim($row['titulo'])) ?></div>
                <div class="date">
                    <?php 
                        echo 'Criado em: ' . date('d/m/Y H:i', strtotime($row['data_criacao']));
                        if ($filter === 'modified' && $row['data_atualizacao'] && $row['data_atualizacao'] != $row['data_criacao']) {
                            echo '<span class="badge-updated">(Atualizado em: ' . date('d/m/Y H:i', strtotime($row['data_atualizacao'])) . ')</span>';
                        }
                    ?>
                </div>
            </div>
            <div class="actions">
                <a href="pagina.php?id=<?= $row['id'] ?>" class="btn-edit" title="Editar">🖉</a>
                
                <form class="delete-form" method="POST" action="../backend/excluir_artigo.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn-delete" title="Excluir">🗑</button>
                 </form>
            </div>
        </div>
    <?php endforeach; endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchTrabalhos');
    const sortFilter = document.getElementById('sortFilter');

    function applySearchFilter() {
        const q = searchInput.value.trim().toLowerCase();
        const rows = document.querySelectorAll('.list .item');
        
        rows.forEach(r => {
            const t = (r.querySelector('.title')?.textContent || '').toLowerCase();
            const d = (r.querySelector('.left-info')?.textContent || '').toLowerCase();
            if (!q || t.includes(q) || d.includes(q)) {
                r.style.display = '';
            } else {
                r.style.display = 'none';
            }
        });
    }

    applySearchFilter();
    searchInput.addEventListener('input', applySearchFilter);

    sortFilter.addEventListener('change', function() {
        const val = this.value;
        const currentSearch = searchInput.value.trim();
        const url = new URL(window.location.href);
        
        url.searchParams.set('filter', val);
        if(currentSearch) {
            url.searchParams.set('search', currentSearch);
        } else {
            url.searchParams.delete('search');
        }
        window.location.href = url.toString();
    });

    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); 
            if(!confirm('Tem certeza que deseja excluir este artigo?')) return;

            const row = this.closest('.item');
            const formData = new FormData(this);
            const actionUrl = this.getAttribute('action');

            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                if (response.ok) {
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    setTimeout(() => {
                        row.remove();
                        const list = document.getElementById('articlesList');
                        const remaining = list.querySelectorAll('.item');
                        if(remaining.length === 0) {
                            list.innerHTML = '<div class="item">Nenhum trabalho salvo ainda.</div>';
                        }
                    }, 300);
                } else {
                    alert('Erro ao excluir. Tente novamente.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Erro de conexão.');
            });
        });
    });
});
</script>

</body>
</html>