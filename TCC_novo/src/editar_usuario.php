<?php
require_once 'backend/auth_check.php';
require_once 'backend/config.php';
if(($_SESSION['user']['tipo'] ?? '') !== 'admin'){
    header('Location: home.php?err=forbidden');
    exit();
}
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if(!$id){
    header('Location: gerenciar_usuarios.php?err=missing_id');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['delete'])){
        $del = $pdo->prepare('DELETE FROM usuarios WHERE id = ?');
        $del->execute([$id]);
        header('Location: gerenciar_usuarios.php?msg=deleted');
        exit();
    } else {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $tipo = in_array($_POST['tipo'] ?? 'user', ['user','admin']) ? $_POST['tipo'] : 'user';
        $pass = $_POST['senha'] ?? '';
        try {
            if(!empty($pass)){
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $upd = $pdo->prepare('UPDATE usuarios SET nome = ?, email = ?, tipo = ?, senha = ? WHERE id = ?');
                $upd->execute([$nome, $email, $tipo, $hash, $id]);
            } else {
                $upd = $pdo->prepare('UPDATE usuarios SET nome = ?, email = ?, tipo = ? WHERE id = ?');
                $upd->execute([$nome, $email, $tipo, $id]);
            }
            header('Location: gerenciar_usuarios.php?msg=updated');
            exit();
        } catch(PDOException $e){
            $err = $e->getMessage();
        }
    }
}

$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$user){
    header('Location: gerenciar_usuarios.php?err=notfound');
    exit();
}

$createdKeys = ['created_at','created','data_criacao','criado_em','created_on','createdAt','criado','data_criado'];
$createdRaw = null;
foreach($createdKeys as $k){
    if(isset($user[$k]) && $user[$k]){
        $createdRaw = $user[$k];
        break;
    }
}
$createdFormatted = null;
if($createdRaw){
    $ts = strtotime($createdRaw);
    if($ts !== false){
        $createdFormatted = date('d/m/Y H:i', $ts);
    } else {
        $createdFormatted = $createdRaw;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Editar Usuário</title>
<link rel="icon" href="img/logo_azl.png" type="image/png">
<link rel="stylesheet" href="css/style.css">
<style>
    .btn-back {
        display:inline-block;
        background:#1E88E5;
        color:#fff;
        padding:6px;
        border-radius:6px;
        font-weight:bold;
        text-decoration:none;
        margin-bottom:20px;
        min-width:80px;
        text-align:center;
        font-size:14px;
    }
    .btn-back:hover { background:#1565C0; }
    .btn {
        display:inline-block;
        padding:6px;
        border-radius:6px;
        font-weight:bold;
        border:0;
        cursor:pointer;
        font-size:14px;
    }
    .btn-save {
        background:#2E7D32;
        color:#fff;
    }
    .btn-save:hover { filter:brightness(0.95); }
    .btn-delete {
        background:#c0392b;
        color:#fff;
    }
    .btn-delete:hover { filter:brightness(0.95); }
    .form-actions {
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-top:12px;
        gap:12px;
        flex-wrap:wrap;
    }
    .form-actions .left { display:flex; align-items:center; gap:8px; }
    .form-actions .right { display:flex; align-items:center; gap:8px; }
    form label { display:block; margin-top:12px; font-weight:600; }
    form input[type="text"], form input[type="email"], form input[type="password"], form select {
        width:100%;
        padding:8px 10px;
        border-radius:6px;
        border:1px solid #ddd;
        box-sizing:border-box;
        margin-top:6px;
    }
    .input-wrap { position: relative; width: 100%; }
    .input-wrap input { padding:8px 40px 8px 10px; }
    .eye-btn {
      position: absolute;
      right: 8px;
      top: 50%;
      transform: translateY(-50%);
      width: 30px;
      height: 30px;
      background: transparent;
      border: none;
      box-shadow: none;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      line-height: 0;
      -webkit-tap-highlight-color: transparent;
    }
    .eye-btn:focus { outline: none; box-shadow: 0 0 0 3px rgba(30,136,229,0.10); }
    .eye-btn svg {
      position: absolute;
      left: 50%;
      top: 50%;
      width: 16px;
      height: 16px;
      margin: 0;
      padding: 0;
      stroke: #111827;
      stroke-width: 1.6;
      fill: none;
      display: block;
      transform: translate(-50%, -50%);
      transition: opacity .06s linear;
      pointer-events: none;
    }
    .eye-btn .icon-open { opacity: 1; }
    .eye-btn .icon-closed { opacity: 0; }
    .eye-btn[data-open="true"] .icon-open { opacity: 0; }
    .eye-btn[data-open="true"] .icon-closed { opacity: 1; }
    @media (max-width:480px){
        .form-actions { flex-direction:column; align-items:stretch; }
        .form-actions .right { justify-content:flex-end; }
        .form-actions .left, .form-actions .right { width:100%; }
        .btn, .btn-back { width:100%; min-width:0; }
    }
    .meta-row { display:flex; gap:12px; color:#555; font-size:0.95rem; margin-bottom:8px; flex-wrap:wrap; }
    .meta-row .meta { background:#fafafa; padding:6px 8px; border-radius:6px; border:1px solid #eee; }
    .error { background:#ffebee; color:#c62828; padding:10px; border-radius:6px; margin-bottom:12px; }
</style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container" style="max-width:700px;margin:30px auto;">
    <h2>Editar Usuário: <?php echo htmlspecialchars($user['nome']); ?></h2>
    <div class="meta-row" aria-hidden="<?php echo $createdFormatted? 'false':'true'; ?>">
        <div class="meta"><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></div>
        <div class="meta"><strong>Criado em:</strong> <?php echo $createdFormatted ? htmlspecialchars($createdFormatted) : '—'; ?></div>
    </div>
    <?php if(!empty($err)): ?>
        <div class="error"><?php echo htmlspecialchars($err); ?></div>
    <?php endif; ?>
    <form method="post" action="editar_usuario.php?id=<?php echo $user['id']; ?>">
        <label>Nome</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required>
        <label>E-mail</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <label>Tipo</label>
        <select name="tipo">
            <option value="user" <?php echo ($user['tipo']==='user')? 'selected':''; ?>>Comum</option>
            <option value="admin" <?php echo ($user['tipo']==='admin')? 'selected':''; ?>>Administrador</option>
        </select>
        <label>Senha</label>
        <div class="input-wrap">
            <input type="password" name="senha" placeholder="Digite nova senha" value="" autocomplete="new-password" id="senha_field" minlength="6" >
            <button type="button" class="eye-btn" data-target="senha_field" aria-label="Mostrar senha" data-open="false" title="Mostrar/ocultar senha"></button>
        </div>
        <div class="form-actions">
            <div class="left">
                <a class="btn-back" href="gerenciar_usuarios.php">⯇ Voltar</a>
            </div>
            <div class="right">
                <button type="submit" name="delete" class="btn btn-delete" onclick="return confirm('Excluir usuário? Esta ação é irreversível.');">Excluir usuário</button>
                <button type="submit" class="btn btn-save">Salvar alterações</button>
            </div>
        </div>
    </form>
</div>

<script>
(function(){
  document.querySelectorAll('.eye-btn').forEach((btn) => {
    btn.innerHTML = `
      <svg class="icon-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" stroke-linecap="round" stroke-linejoin="round"/>
        <circle cx="12" cy="12" r="3"/>
      </svg>
      <svg class="icon-closed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a20.14 20.14 0 0 1 5.06-5.94" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M1 1l22 22" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    `;
  });

  document.querySelectorAll('.eye-btn').forEach(btn=>{
    const targetId = btn.getAttribute('data-target');
    const input = document.getElementById(targetId);
    if(!input) return;
    btn.addEventListener('mousedown', e => e.preventDefault());
    btn.addEventListener('click', () => {
      const isOpen = btn.getAttribute('data-open') === 'true';
      btn.setAttribute('data-open', String(!isOpen));
      input.type = isOpen ? 'password' : 'text';
      input.focus({preventScroll: true});
      btn.setAttribute('aria-label', isOpen ? 'Mostrar senha' : 'Ocultar senha');
    });
    btn.addEventListener('keydown', (e) => {
      if(e.key === 'Enter' || e.key === ' ') { e.preventDefault(); btn.click(); }
    });
  });

  const form = document.querySelector('form');
  let isDirty = false;
  form.querySelectorAll('input, select, textarea').forEach(input => {
    input.addEventListener('change', () => { isDirty = true; });
    input.addEventListener('input', () => { isDirty = true; });
  });
  form.addEventListener('submit', () => { isDirty = false; });
  window.addEventListener('beforeunload', function (e) {
    if (!isDirty) return undefined;
    const confirmationMessage = 'Você tem alterações não salvas. Tem certeza que deseja sair?';
    e.returnValue = confirmationMessage;
    return confirmationMessage;
  });
})();
</script>
</body>
</html>