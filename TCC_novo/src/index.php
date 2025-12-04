<?php
?>
<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>ediTEC</title>
  <link rel="icon" href="img/logo_azl.png" type="image/png">
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
    }
    main {
        flex: 1;
    }
    .footer {
        background: #f4f4f4;
        border-top: 1px solid #ddd;
        padding: 15px 0;
        width: 100%;
        margin-top: auto;
    }
    .footer-inner {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .footer-logo {
        height: 25px;
        width: auto;
        opacity: 0.8;
    }
    .footer-text {
        font-size: 14px;
        color: #666;
    }
  </style>
</head>

<body>
  <?php include 'navbar_white.php'; ?>

  <main>
    <section class="hero container center">
      
      <p class="lead" style="max-width:720px; margin: 1.2rem auto 0;">
        Esta ferramenta foi criada para simplificar a etapa de formatação: aqui você cria modelos de artigos científicos e supre de
        recursos que ajudam a formatação. Crie sua conta, organize seus trabalhos e exporte em DOCX com a estrutura adequada.
      </p>

      <br>
      <div class="actions">
        <a class="btn cta" href="cadastro.php">Começar — Criar conta</a>
        <a class="muted-link" href="login_form.php">Já tenho conta</a>
      </div>
    </section>
    <br><br><br><br>
    <section class="container" style="max-width:960px; margin: 2.4rem auto;">
      <h2 style="margin-bottom:0.6rem; text-align:center;">O que é um artigo científico?</h2>
      <p style="margin-top:0;">
        Um artigo científico é um texto objetivo que apresenta resultados de investigação, revisão ou reflexão acadêmica.
        Ele organiza ideias em elementos essenciais.
      </p>
      <br>
      <h2 style="margin-top:1.2rem; margin-bottom:0.6rem; text-align:center;">Sobre a ABNT</h2>
      <p style="margin-top:0;">
        A ABNT reúne as normas técnicas que padronizam a apresentação de trabalhos acadêmicos no Brasil (por exemplo, NBRs
        relacionadas a artigos, citações e referências). Seguir essas normas garante consistência, facilita a leitura e evita
        problemas de avaliação por formatação incorreta.
      </p>
      <br>
      <h2 style="margin-top:1.2rem; margin-bottom:0.6rem; text-align:center;">O nosso trabalho</h2>
      <p style="margin-top:0;">
        Esta plataforma foi desenvolvida para reduzir o tempo gasto na formatação e diminuir erros comuns. Entre as funções
        principais estão: aplicação automática das regras de formatação, campos estruturados para cada seção do artigo,
        exportação em DOCX e histórico de trabalhos salvos.
      </p>
      <br><br>
      <p style="margin-top:1rem; font-style:italic;">
        Em resumo: nossa proposta é transformar uma etapa técnica e trabalhosa (a formatação) em algo simples e confiável,
        ajudando você a apresentar um trabalho com qualidade e de acordo com as exigências da ETEC e do Centro Paula Souza.
      </p>
    </section>
  </main>

  <footer class="footer">
    <div class="footer-inner">
        <img src="img/logo.png" alt="Logo Editec" class="footer-logo">
        
        <span class="footer-text">© 2025 — Elaborada pelos autores — ediTEC</span>
    </div>
  </footer>
</body>
</html>