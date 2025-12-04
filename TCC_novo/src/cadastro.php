<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Cadastro</title>
  <link rel="icon" href="img/logo_azl.png" type="image/png">
  <link rel="stylesheet" href="css/style.css">
  <style>
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
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      line-height: 0;
      padding: 0;
      box-shadow: none;
      -webkit-tap-highlight-color: transparent;
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

    @media (max-width: 520px) {
      .eye-btn { right: 8px; width: 28px; height: 28px; top: 47%; transform: translateY(-60%); }
      .input-wrap input { padding: 10px 40px 10px 12px; }
    }
  </style>
</head>

<body>
  <?php include 'navbar_white.php'; ?>

  <main>
    <div class="container">
      <div class="form-card">
        <h1>Criar conta</h1>
        <form action="backend/register.php" method="POST">

          <label for="name">Nome completo</label>
          <input id="name" name="nome" type="text" required placeholder="Seu nome completo">

          <label for="email">E-mail</label>
          <input id="email" name="email" type="email" required placeholder="Seu e-mail">

          <label for="password">Senha</label>
          <div class="input-wrap">
            <input id="password" name="senha" type="password" minlength="6" required placeholder="Mínimo 6 caracteres">
            <button type="button" class="eye-btn" aria-label="Mostrar senha" data-target="password" data-open="false" title="Mostrar/ocultar senha"></button>
          </div>

          <label for="passwordConfirm">Confirmar senha</label>
          <div class="input-wrap">
            <input id="passwordConfirm" name="senha_confirm" type="password" minlength="6" required placeholder="Repita sua senha">
            <button type="button" class="eye-btn" aria-label="Mostrar senha" data-target="passwordConfirm" data-open="false" title="Mostrar/ocultar senha"></button>
          </div>

          <button type="submit" class="btn btn-primary">Cadastrar</button>
          <p class="muted">Já possui conta? <a href="login_form.php">Entrar</a></p>
        </form>
      </div>
    </div>
  </main>

  <script>
    document.querySelectorAll('.eye-btn').forEach(btn => {
      const targetId = btn.getAttribute('data-target');
      const input = document.getElementById(targetId);
      if (!input) return;

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

      btn.addEventListener('mousedown', e => e.preventDefault());

      btn.addEventListener('click', () => {
        const isOpen = btn.getAttribute('data-open') === 'true';
        btn.setAttribute('data-open', String(!isOpen));
        input.type = isOpen ? 'password' : 'text';
        input.focus({ preventScroll: true });
        btn.setAttribute('aria-label', isOpen ? 'Mostrar senha' : 'Ocultar senha');
      });

      btn.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          btn.click();
        }
      });
    });
  </script>
</body>
</html>