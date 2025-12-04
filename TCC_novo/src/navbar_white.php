<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

  <header class="site-header white">
    <div class="container header-inner">
      <a href="index.php" class="brand">
        <img src="img/logo.png" alt="Logotipo ediTEC" class="logo">
      </a>
      <nav class="nav">
        <a href="login_form.php" class="btn btn-ghost">Entrar</a>
        <a href="cadastro.php" class="btn btn-primary">Cadastrar</a>
      </nav>
    </div>
  </header>