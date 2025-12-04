<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="site-header">
  <div class="container header-inner">
    <div class="nav-left">
      <a href="home.php" aria-label="Início" title="Início">
        <img src="img/logo_branca.png" alt="Logo" class="logo">
      </a>
    </div>

    <div class="nav-container">
      <a href="artigo/pagina.php">Criar Artigo</a>
      <a href="artigo/trabalhos.php">Trabalhos Salvos</a>
      <?php if(isset($_SESSION['user']) && ($_SESSION['user']['tipo'] ?? '') === 'admin'): ?>
      <a href="gerenciar_usuarios.php">Gerenciar Usuários</a>
      <?php endif; ?>
    </div>

    <div class="nav-profile">
      <a href="perfil.php" aria-label="Perfil" title="Perfil">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
        </svg>
      </a>
    </div>
  </div>
</header>