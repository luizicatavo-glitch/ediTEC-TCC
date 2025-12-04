<?php
require_once 'backend/auth_check.php';
require_once 'backend/config.php';
if(($_SESSION['user']['tipo'] ?? '') !== 'admin'){
    header('Location: home.php?err=forbidden');
    exit();
}
try {
    $stmt = $pdo->query('SELECT id, nome, email, tipo, data_criacao FROM usuarios ORDER BY data_criacao DESC');
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Erro ao buscar usuários: ' . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Gerenciar Usuários</title>
<link rel="icon" href="img/logo_azl.png" type="image/png">
<link rel="stylesheet" href="css/style.css">
<style>
*{box-sizing:border-box;}
.users-header{display:flex;align-items:center;justify-content:space-between;margin:20px 0}
.search-input{min-width:220px;padding:8px 12px;border-radius:6px;border:1px solid #ccc}
.user-list{margin-top:12px;border-radius:8px;overflow:hidden;border:1px solid #eee}

.user-row, .user-list-headers{display:flex;align-items:center;gap:12px;padding:14px 16px}
.user-row{background:transparent}
.user-row:nth-child(odd){background:var(--white)}
.user-row:nth-child(even){background:#f7f7f7}
.user-list-headers{background:#fafafa;font-weight:700;color:var(--gray-dark);border-bottom:1px solid #eee}

.user-name{flex:2; min-width:150px; font-weight:bold;}
.user-email{flex:3; min-width:200px; color:var(--gray-dark);}
.user-type{flex:1; text-transform:capitalize; color:var(--gray-dark);}
.user-id{flex:1; text-align:left; color:var(--gray-dark);}
.actions{flex:1; text-align:right;}

.user-list-headers .header-name{flex:2;}
.user-list-headers .header-email{flex:3;}
.user-list-headers .header-type{flex:1;}
.user-list-headers .header-id{flex:1;}
.user-list-headers .header-actions{flex:1; text-align:right;}

.btn-edit{display:inline-block;text-decoration:none;padding:8px 12px;border-radius:6px;background:var(--blue);color:#fff;font-weight:600}
.btn-edit:hover{background:#1565C0}

@media (max-width:600px){
    .user-name, .header-name{min-width:120px}
    .user-email, .header-email{min-width:140px}
    .user-list-headers, .user-row{padding:10px 12px}
}
</style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="users-header">
        <h2>Usuários</h2>
        <input id="searchUsers" class="search-input" placeholder="Pesquisar usuários..." />
    </div>

    <div id="userList" class="user-list" role="list">
        <div class="user-list-headers" aria-hidden="true">
            <div class="header-name">Nome</div>
            <div class="header-email">E-mail</div>
            <div class="header-type">Tipo</div>
            <div class="header-id">ID</div>
            <div class="header-actions"></div>
        </div>

        <?php if(!empty($usuarios)): foreach($usuarios as $row): ?>
        <div class="user-row" role="listitem" data-name="<?php echo htmlspecialchars(strtolower($row['nome'])); ?>">
            <div class="user-name"><?php echo htmlspecialchars($row['nome']); ?></div>
            <div class="user-email"><?php echo htmlspecialchars($row['email']); ?></div>
            <div class="user-type"><?php echo htmlspecialchars($row['tipo']); ?></div>
            <div class="user-id"><?php echo htmlspecialchars($row['id']); ?></div>
            <div class="actions">
                <a class="btn-edit" href="editar_usuario.php?id=<?php echo $row['id']; ?>">Editar</a>
            </div>
        </div>
        <?php endforeach; else: ?>
        <div class="user-row"><em style="padding-left:6px;">Nenhum usuário encontrado.</em></div>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('searchUsers').addEventListener('input', function(e){
    const q = this.value.trim().toLowerCase();
    const rows = document.querySelectorAll('#userList .user-row');
    rows.forEach(r=>{
        const name = r.getAttribute('data-name') || '';
        r.style.display = (!q || name.indexOf(q) !== -1) ? 'flex' : 'none';
    });
});
</script>
</body>
</html>