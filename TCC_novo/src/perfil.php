<?php 
require_once 'backend/auth_check.php';
require_once 'backend/config.php';

$userId = $_SESSION['user']['id'] ?? 0;
$success = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = trim($_POST['nome'] ?? '');
    $newPass = $_POST['senha'] ?? '';
    $confirm = $_POST['senha_confirm'] ?? '';

    if(!$nome){
    } else {
        try {
            if(!empty($newPass)){
                if(strlen($newPass) < 6){
                    throw new Exception('A senha deve ter pelo menos 6 caracteres.');
                }
                if($newPass !== $confirm){
                    throw new Exception('As senhas não coincidem.');
                }
                $hash = password_hash($newPass, PASSWORD_DEFAULT);
                $upd = $pdo->prepare('UPDATE usuarios SET nome = ?, senha = ? WHERE id = ?');
                $upd->execute([$nome, $hash, $userId]);
            } else {
                $upd = $pdo->prepare('UPDATE usuarios SET nome = ? WHERE id = ?');
                $upd->execute([$nome, $userId]);
            }

            $_SESSION['user']['nome'] = $nome;
            $success = 'Dados atualizados com sucesso.';
        } catch (Exception $ex) {
            $error = 'Erro ao atualizar: ' . $ex->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perfil</title>
<link rel="icon" href="img/logo_azl.png" type="image/png">
<link rel="stylesheet" href="css/style.css">
<style>
body {
  background: #f4f4f4;
  font-family: Arial, sans-serif;
  margin:0;
  padding:0;
}
.container {
  max-width:700px;
  margin:20px auto;
  padding:0 16px;
}
.card {
  background:#fff;
  padding:18px;
  border-radius:8px;
  box-shadow:0 2px 6px rgba(0,0,0,.06);
}
.view-card {
  background:#fff;
  padding:16px;
  border-radius:8px;
  box-shadow:0 1px 4px rgba(0,0,0,.04);
  margin-bottom:12px;
  text-align: left;
}
.profile-field { margin-bottom:12px; }
.profile-field label { display:block; font-weight:600; margin-bottom:6px; color:#333 }
.profile-field .value {
  width:100%;
  padding:10px;
  background:#fafafa;
  border:1px solid #eee;
  border-radius:6px;
  box-sizing:border-box;
  color:#222
}
.btn-back {
  display:inline-block;
  background:#1E88E5;
  color:#fff;
  padding:6px 14px;
  border-radius:6px;
  font-weight:bold;
  text-decoration:none;
  margin-bottom:16px;
  font-size: 14px;
}
.btn-back:hover { background:#1565C0; }

.btn { display:inline-block; padding:6px; border-radius:6px; font-weight:bold; border:0; cursor:pointer; font-size:14px; }
.btn-save { background:#2E7D32; color:#fff; padding:8px 14px; }
.btn-save:hover { filter:brightness(0.95); }
.btn-logout { background:#d32f2f; color:#fff; padding:8px 12px; text-decoration:none; display:inline-block; border-radius:6px; font-weight:700 }

.view-card .logout-center {
  display: flex;
  justify-content: center;
  margin-top: 30px;
}

.msg-success { background:#e8f5e9; color:#2e7d32; padding:10px; border-radius:6px; margin-bottom:12px; }
.msg-error { background:#ffebee; color:#c62828; padding:10px; border-radius:6px; margin-bottom:12px; }

.view-card .profile-title {
  text-align: center;
  margin-top: 0;
  margin-bottom: 12px;
  font-size: 1.25rem;
}

h2.profile-title { text-align:left; margin-top:0; margin-bottom:8px }

.toggle-edit {
  display:flex;
  align-items:center;
  justify-content:space-between;
  cursor:pointer;
  margin:12px 0;
  padding:10px 12px;
  border-radius:8px;
  background:#fff;
  border:1px solid #eee;
}
.toggle-edit h3 { margin:0; font-size:1rem }
.chev { transition:transform .2s ease; color:#666 }
.chev.open { transform:rotate(180deg) }

.edit-card {
  background:#fff;
  padding:16px;
  border-radius:8px;
  border:1px solid #eee;
  box-shadow:0 1px 4px rgba(0,0,0,.03);
  display:none
}

.input-wrap {
  position: relative;
  width: 100%;
}

.input-wrap input {
  width: 100%;
  padding: 10px 44px 10px 12px;
  border-radius: 6px;
  border: 1px solid #ddd;
  background: #fff;
  box-sizing: border-box;
  font-size: 14px;
}

.eye-btn {
  position: absolute;
  right: 10px;
  top: 48%;
  transform: translateY(-62%);
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

.eye-btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(30,136,229,0.10);
}

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

.eye-btn,
.eye-btn svg { box-sizing: border-box; }

.actions-row {
  display:flex;
  align-items:center;
  justify-content:flex-end;
  margin-top:14px;
  gap:12px;
}
@media (max-width:520px){
  .actions-row{flex-direction:column-reverse;align-items:stretch; justify-content:stretch}
  .eye-btn { right: 8px; width: 28px; height: 28px; top: 47%; transform: translateY(-60%); }
  .input-wrap input { padding: 10px 40px 10px 12px; }
  .view-card .logout-center { justify-content: center; }
}
</style>
</head>
<body>
<div class="container">
  <a href="home.php" class="btn-back">⯇ Voltar</a>

  <div class="view-card card" aria-labelledby="profile-heading">
    <h2 id="profile-heading" class="profile-title">Meu Perfil</h2>
    <div class="profile-field">
      <label>Nome</label>
      <div class="value"><?php echo htmlspecialchars($_SESSION['user']['nome'] ?? '—'); ?></div>
    </div>
    <div class="profile-field">
      <label>E-mail</label>
      <div class="value"><?php echo htmlspecialchars($_SESSION['user']['email'] ?? '—'); ?></div>
    </div>

    <div class="logout-center">
      <a class="btn-logout" aria-label="Sair da Conta" title="Sair da Conta" href="../site/backend/logout.php">Sair</a>
    </div>
  </div>

  <div id="toggleEdit" class="toggle-edit" role="button" tabindex="0" aria-expanded="false" aria-controls="editCard">
    <h3>Editar meu perfil</h3>
    <svg class="chev" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true">
      <path d="M6 9L12 15L18 9" stroke="#444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </div>

  <div id="editCard" class="edit-card" aria-hidden="true">
    <?php if($success): ?><div class="msg-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
    <?php if($error): ?><div class="msg-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

    <form method="post" action="perfil.php">
      <div class="profile-field">
        <label for="nome_edit">Nome</label>
        <input id="nome_edit" name="nome" type="text" placeholder="Digite novo nome">
      </div>

      <hr style="margin:12px 0; border:none; border-top:1px solid #e0e0e0;">

      <div class="profile-field">
        <label for="senha_edit">Nova senha</label>
        <div class="input-wrap">
          <input id="senha_edit" name="senha" type="password" minlength="6" placeholder="Digite a nova senha" autocomplete="new-password" aria-describedby="senha-help">
          <button type="button" class="eye-btn" aria-label="Mostrar senha" data-target="senha_edit" data-open="false" title="Mostrar/ocultar senha">
          </button>
        </div>
      </div>

      <div class="profile-field">
        <label for="senha_confirm_edit">Confirmar nova senha</label>
        <div class="input-wrap">
          <input id="senha_confirm_edit" name="senha_confirm" type="password" minlength="6" placeholder="Repita a nova senha" autocomplete="new-password">
          <button type="button" class="eye-btn" aria-label="Mostrar senha" data-target="senha_confirm_edit" data-open="false" title="Mostrar/ocultar senha">
          </button>
        </div>
      </div>

      <div class="actions-row">
        <button type="submit" class="btn btn-save">Salvar alterações</button>
      </div>
    </form>
  </div>
</div>

<script>
(function(){
  const toggle = document.getElementById('toggleEdit');
  const editCard = document.getElementById('editCard');
  const chev = toggle.querySelector('.chev');

  function setOpen(open){
    if(open){
      editCard.style.display = 'block';
      chev.classList.add('open');
      toggle.setAttribute('aria-expanded','true');
      editCard.setAttribute('aria-hidden','false');
    } else {
      editCard.style.display = 'none';
      chev.classList.remove('open');
      toggle.setAttribute('aria-expanded','false');
      editCard.setAttribute('aria-hidden','true');
    }
  }
  toggle.addEventListener('click', ()=> setOpen(editCard.style.display !== 'block'));
  toggle.addEventListener('keypress', (e)=> {
    if(e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      setOpen(editCard.style.display !== 'block');
    }
  });

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
      if(e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        btn.click();
      }
    });
  });

  const hasMsg=<?php echo ($success||$error)?'true':'false'; ?>;
  if(hasMsg) setOpen(true);

  const form = editCard.querySelector('form');
  let isDirty = false;

  form.querySelectorAll('input').forEach(input => {
    input.addEventListener('input', () => { isDirty = true; });
    input.addEventListener('change', () => { isDirty = true; });
  });

  form.addEventListener('submit', () => { isDirty = false; });

  window.addEventListener('beforeunload', function(e){
    if(!isDirty) return undefined;
    const message = 'Você tem alterações não salvas. Tem certeza que deseja sair?';
    e.returnValue = message;
    return message;
  });
})();
</script>
</body>
</html>