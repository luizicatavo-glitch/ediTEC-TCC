<?php
require_once 'backend/auth_check.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="icon" href="img/logo_azl.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #FFF;
            color: #000;
        }
        .profile-icon {
            width: 28px;
            height: 28px;
            cursor: pointer;
        }

        .container {
            padding: 40px 20px;
            text-align: center;
        }

        .welcome {
            font-size: 22px;
            margin-bottom: 30px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .card {
            background: #f8f8f8;
            border: 1px solid #DDD;
            border-radius: 12px;
            padding: 30px 20px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, .1);
            transition: transform .2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #1E88E5;
        }

        .card p {
            font-size: 14px;
            color: #616161;
        }

        .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #1E88E5;
            color: #fff;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn:hover {
            background: #1565C0;
        }
        
        .btn-download {
            background: #2E7D32;
        }
        .btn-download:hover {
            background: #1B5E20;
        }

        .guide {
            max-width: 1000px;
            margin: 30px auto 80px;
            text-align: left;
            color: #222;
            line-height: 1.6;
            padding: 24px;
            border-radius: 10px;
        }

        .guide h2 {
            color: #1E88E5;
            margin-top: 0;
        }

        .guide p {
            margin: 0 0 12px 0;
        }

        .guide .muted {
            color: #555;
            font-size: 14px;
        }

        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        @media (max-width: 760px) {
            .two-columns {
                grid-template-columns: 1fr;
            }
        }

        .checklist {
            background: #fff;
            border: 1px solid #eee;
            padding: 12px;
            border-radius: 8px;
        }

        .small {
            font-size: 13px;
            color: #444;
        }

        .example {
            background: #f7f9fc;
            border-left: 4px solid #1E88E5;
            padding: 10px 12px;
            margin: 8px 0 14px 0;
            font-family: monospace;
            white-space: pre-wrap;
        }

        .subtle {
            color: #666;
            font-size: 13px;
        }

        .source-title {
            font-weight:700;
            margin-top:10px;
            color:#1E88E5;
        }

        .accordion {
            margin-top: 12px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e9f1fb;
            background: #fff;
        }

        .accordion-item + .accordion-item { border-top: 1px solid #eef6ff; }

        .accordion-header {
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding: 12px 14px;
            cursor:pointer;
            gap:12px;
            background: #fff;
        }

        .accordion-header h5 {
            margin:0;
            font-size:15px;
            color:#114b8a;
        }

        .accordion-toggle {
            width:36px;
            height:36px;
            border-radius:50%;
            border:1px solid #dbefff;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            background:#fff;
            color:#1E88E5;
            transition: transform .25s ease;
            font-size:14px;
        }

        .accordion-content {
            max-height:0;
            overflow:hidden;
            transition: max-height .35s ease, padding .25s ease;
            padding: 0 14px;
            background:#fbfdff;
        }

        .accordion-content.open { padding:12px 14px 18px 14px; }

        .accordion-item.open .accordion-toggle { transform: rotate(180deg); }

        @media (max-width:540px){
            .accordion-header{padding:10px}
            .accordion-toggle{width:32px;height:32px;font-size:13px}
        }

        .btn-top {
            display: none;
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            border: none;
            outline: none;
            background-color: #1E88E5;
            color: white;
            cursor: pointer;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-top:hover {
            background-color: #1565C0;
            transform: translateY(-3px);
        }
        
        .btn-top svg {
            width: 24px;
            height: 24px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2.5;
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
<?php include 'navbar.php'; ?>

<div class="container">
    <div class="welcome">Bem-vindo ao Editec, o Sistema de Formatação para Artigos Científicos!</div>

    <div class="cards">
        <div class="card">
            <h3>Criar Artigo</h3>
            <p>Inicie um novo artigo científico.</p>
            <a href="artigo/pagina.php" class="btn">Começar</a>
        </div>

        <div class="card">
            <h3>Trabalhos Salvos</h3>
            <p>Acesse seus artigos já criados.</p>
            <a href="artigo/trabalhos.php" class="btn">Ver Trabalhos</a>
        </div>
    </div>

    <div class="guide" role="region" aria-label="Guia resumido de Artigo Científico e normas ABNT">
        <h2 style="text-align:center;">Guia: Como criar um artigo científico?</h2>

        <p class="muted">Este resumo reúne definições e orientações essenciais para a criação, estruturação e formatação de um artigo científico
        segundo as práticas adotadas pela ABNT. Use-o como referência prática para preparar seu trabalho.</p>

        <h3>O que é um artigo científico?</h3>
        <p>
            Um artigo científico é uma publicação curta que apresenta e discute ideias, métodos, técnicas, processos ou resultados
            de investigação. Pode ser original (apresenta abordagens/achados inéditos) ou de revisão (resume e analisa literatura já
            publicada). Seu propósito é comunicar conhecimento de forma clara, objetiva e verificável.
        </p>

        <h3>O que é a ABNT?</h3>
        <p>
           A Associação Brasileira de Normas Técnicas é o órgão responsável pela normalização técnica no Brasil, apoiando o desenvolvimento tecnológico brasileiro. Trata-se de uma entidade privada, sem fins lucrativos e de utilidade pública.
        </p>

        <div class="cards" style="margin: 35px 0; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            <div class="card" style="border-left: 5px solid #2E7D32;">
                <h3 style="color: #2E7D32;">Manual TCC Etecs e Centro Paula Souza</h3>
                <p>Baixe o manual oficial com todas as normas, diretrizes e explicações detalhadas sobre artigos científicos</p>
                <a href="arquivos/Manual-TCC-Etecs_2022_2a.Edicao-artigo_científico.pdf.pdf" class="btn btn-download" download>Baixar Manual (PDF)</a>
            </div>

            <div class="card" style="border-left: 5px solid #2E7D32;">
                <h3 style="color: #2E7D32;">Modelo Editável</h3>
                <p>Baixe o modelo em .docx pronto para edição</p>
                <br>
                <a href="arquivos/MODELO ARTIGO CIENTÍFICO.docx" class="btn btn-download" download>Baixar Modelo (DOCX)</a>
            </div>
        </div>
        <h3>Estrutura do artigo (resumo prático)</h3>
        <p>Segundo a NBR 6022, a estrutura básica inclui três grupos de elementos:</p>

        <div class="two-columns">
            <div>
                <strong>Elementos pré-textuais</strong>
                <ul class="small">
                    <li>Título e subtítulo (se houver) — obrigatório</li>
                    <li>Nome(s) do(s) autor(es) — obrigatório</li>
                    <li>Resumo em língua portuguesa (pt-BR) — obrigatório</li>
                    <li>Palavras-chave (pt-BR) — obrigatório</li>
                    <li>Resumo em língua estrangeira / Palavras-chave em língua estrangeira — opcional</li>
                </ul>
            </div>

            <div>
                <strong>Elementos textuais</strong>
                <ul class="small">
                    <li>Introdução — delimita o tema, apresenta objetivo(s), problema e justificativa — obrigatório</li>
                    <li>Desenvolvimento — inclui metodologia, fundamentação teórica, apresentação de dados e discussão — obrigatório</li>
                    <li>Conclusão / Considerações finais — sintetiza respostas aos objetivos e indica recomendações — obrigatório</li>
                </ul>
            </div>
        </div>

        <p style="margin-top:12px;">
            <strong>Elementos pós-textuais:</strong> Referências (obrigatórias), e opcionalmente glossário, apêndices, anexos e agradecimentos.
        </p>

        <h3>Pré-textuais — observações práticas</h3>
        <p>
            O título deve ser conciso e informativo; o(s) autor(es) devem aparecer com breve currículo (quando exigido) e
            contatos. O resumo deve ser claro, em 1 parágrafo, geralmente até 250 palavras, escrito em voz ativa e terceira pessoa,
            seguido das palavras-chave (3-6), separadas por ponto e vírgula e terminadas em ponto.
        </p>

        <h3>Formatação recomendada (resumo prático)</h3>
        <div class="checklist">
            <ul class="small">
                <li>Papel: A4 (210 x 297 mm).</li>
                <li>Margens: superior e esquerda = 3 cm; inferior e direita = 2 cm.</li>
                <li>Fonte: Arial, Calibri ou Times New Roman (cor preta); tamanho 12 para texto; 10 para citações longas, notas e legendas.</li>
                <li>Espaçamento: 1,5 entre linhas no texto; citações longas (igual ou mais que 3 linhas), notas de rodapé, referências e legendas em espaço simples.</li>
                <li>Alinhamento: texto justificado; títulos podem ser centralizados conforme norma; referências alinhadas à esquerda.</li>
                <li>Numeração de páginas: algarismos arábicos (local conforme normas da instituição).</li>
                <li>Extensão usual: de 5 a, no máximo, 30 páginas (ver orientações do periódico/instituição).</li>
            </ul>
        </div>

        <h3>Seções essenciais — o que escrever em cada uma</h3>
        <p>
            <strong>Introdução:</strong> explique o tema, delimite o problema, apresente objetivos (geral e específicos) e justifique a pesquisa.
        </p>
        <p>
            <strong>Metodologia:</strong> descreva o desenho da pesquisa, amostra, instrumentos, procedimentos e métodos de análise para permitir reprodutibilidade.
        </p>
        <p>
            <strong>Resultados:</strong> apresente dados de forma objetiva (tabelas, figuras), com títulos/legendas e referência à fonte.
        </p>
        <p>
            <strong>Discussão:</strong> interprete os resultados, compare com a literatura, aponte implicações e limitações.
        </p>
        <p>
            <strong>Conclusão:</strong> responda aos objetivos, sintetize contribuições e sugira pesquisas futuras (não inclua novos dados).
        </p>

        <h3>Figuras, tabelas e citações</h3>
        <p>
            Numere figuras e tabelas sequencialmente; a legenda da tabela fica acima, a da figura abaixo. Use alta resolução em imagens e
            prefira gráficos vetoriais quando possível. Citações devem seguir NBR 10520 (citação direta curta e longa, indireta, etc.).
        </p>

        <h3 style="text-align:center;color:#1E88E5;margin-top:18px;">Como fazer citações (tipos e exemplos)</h3>
        <p class="subtle">Abaixo há explicações práticas (e exemplos) dos principais tipos de citação exigidos pela ABNT (NBR 10520).</p>

        <div class="accordion" id="citationsAccordion">
            <div class="accordion-item" id="cit-short">
                <div class="accordion-header" role="button" tabindex="0" aria-controls="content-cit-short" aria-expanded="false">
                    <h5>1) Citação direta curta (até 3 linhas)</h5>
                    <div class="accordion-toggle" aria-hidden="true">▾</div>
                </div>
                <div id="content-cit-short" class="accordion-content" role="region" aria-labelledby="cit-short">
                    <div class="example">
Trecho transcrito literalmente do autor. Deve ser incorporado ao parágrafo entre aspas duplas e acompanhado da indicação de autoria, ano e página.

Ex.: "A educação é a chave para o desenvolvimento." (SILVA, 2019, p. 35)

ou

Segundo Silva (2019, p. 35), "a educação é a chave para o desenvolvimento."
                    </div>
                </div>
            </div>

            <div class="accordion-item" id="cit-long">
                <div class="accordion-header" role="button" tabindex="0" aria-controls="content-cit-long" aria-expanded="false">
                    <h5>2) Citação direta longa (mais de 3 linhas)</h5>
                    <div class="accordion-toggle" aria-hidden="true">▾</div>
                </div>
                <div id="content-cit-long" class="accordion-content" role="region" aria-labelledby="cit-long">
                    <div class="example">
Quando a citação tem mais de três linhas, apresente-a em bloco próprio, sem aspas, com recuo (ex.: 4 cm da margem esquerda), espaçamento simples e fonte menor (ex.: 10 pt); a indicação de página é obrigatória.

Ex.: 
A educação desempenha papel central na transformação social, sendo capaz de reduzir desigualdades e promover a autonomia das pessoas... (SILVA, 2019, p. 102)
                    </div>
                </div>
            </div>

            <div class="accordion-item" id="cit-indirect">
                <div class="accordion-header" role="button" tabindex="0" aria-controls="content-cit-indirect" aria-expanded="false">
                    <h5>3) Citação indireta (paráfrase)</h5>
                    <div class="accordion-toggle" aria-hidden="true">▾</div>
                </div>
                <div id="content-cit-indirect" class="accordion-content" role="region" aria-labelledby="cit-indirect">
                    <div class="example">
Reproduz a ideia do autor com suas próprias palavras. Não se usam aspas; indicar autor e ano. Indicação de página é recomendada quando se referencia ideia específica.

Ex.: De acordo com Silva (2019), a educação contribui para a redução das desigualdades sociais.
                    </div>
                </div>
            </div>

            <div class="accordion-item" id="cit-apud">
                <div class="accordion-header" role="button" tabindex="0" aria-controls="content-cit-apud" aria-expanded="false">
                    <h5>4) Citação de citação (apud)</h5>
                    <div class="accordion-toggle" aria-hidden="true">▾</div>
                </div>
                <div id="content-cit-apud" class="accordion-content" role="region" aria-labelledby="cit-apud">
                    <div class="example">
Use quando você encontra uma informação citada em outra obra e não teve acesso à fonte original. Indique a referência secundária com 'apud'.

Ex.: (PEREIRA apud SILVA, 2018, p. 45)
                    </div>
                </div>
            </div>

            <div class="accordion-item" id="cit-multi">
                <div class="accordion-header" role="button" tabindex="0" aria-controls="content-cit-multi" aria-expanded="false">
                    <h5>5) Autoria com vários autores</h5>
                    <div class="accordion-toggle" aria-hidden="true">▾</div>
                </div>
                <div id="content-cit-multi" class="accordion-content" role="region" aria-labelledby="cit-multi">
                    <div class="example">
Obras com até três autores: mencione todos no texto. A partir de quatro autores, no texto use <em>et al.</em>, na referência liste todos.

Ex.: Texto: (SOUZA; LIMA; COSTA, 2017)  —  ou  (ARAÚJO et al., 2021)
                    </div>
                </div>
            </div>

            <div class="accordion-item" id="cit-web">
                <div class="accordion-header" role="button" tabindex="0" aria-controls="content-cit-web" aria-expanded="false">
                    <h5>6) Documentos eletrônicos e sem paginação</h5>
                    <div class="accordion-toggle" aria-hidden="true">▾</div>
                </div>
                <div id="content-cit-web" class="accordion-content" role="region" aria-labelledby="cit-web">
                    <div class="example">
Se não houver paginação, indique autor e ano; se útil, indique seção, parágrafo ou título.

Ex.: No texto: (IBGE, 2023)  — ou — (SILVA, 2020, seção 2)
                    </div>
                </div>
            </div>
        </div>

        <h4 class="source-title" style="text-align:center;margin-top:18px;color:#1E88E5;">Agora: exemplos práticos por tipo de fonte (NBR 6023)</h4>

        <div class="accordion" id="examplesAccordion">
            <div class="accordion-item" id="acc-book-single">
                <div class="accordion-header" role="button" tabindex="0" aria-controls="content-book-single" aria-expanded="false">
                    <h5>Livro (autor único)</h5>
                    <div class="accordion-toggle" aria-hidden="true">▾</div>
                </div>
                <div id="content-book-single" class="accordion-content" role="region" aria-labelledby="acc-book-single">
                    <div class="example">
No texto: (FREIRE, 1996, p. 22)

Referência:
FREIRE, Paulo. Pedagogia da autonomia: saberes necessários à prática educativa. 25. ed. São Paulo: Paz e Terra, 1996.
                    </div>
                </div>
            </div>

            <div class="accordion-item" id="acc-book-two">
                <div class="accordion-header" role="button" tabindex="0" aria-controls="content-book-two" aria-expanded="false">
                    <h5>Livro com dois ou três autores</h5>
                    <div class="accordion-toggle" aria-hidden="true">▾</div>
                </div>
                <div id="content-book-two" class="accordion-content" role="region" aria-labelledby="acc-book-two">
                    <div class="example">
No texto: (GIL; LICHT, 2019)

Referência:
GIL, Antonio Carlos; LICHT, Roni. Como elaborar projetos de pesquisa. 7. ed. São Paulo: Atlas, 2019.
                    </div>
                </div>
            </div>

            <div class="accordion-item" id="acc-book-multi">
                <div class="accordion-header" role="button" tabindex="0" aria-controls="content-book-multi" aria-expanded="false">
                    <h5>Livro com quatro ou mais autores</h5>
                    <div class="accordion-toggle" aria-hidden="true">▾</div>
                </div>
                <div id="content-book-multi" class="accordion-content" role="region" aria-labelledby="acc-book-multi">
                    <div class="example">
No texto: (LAKATOS et al., 2017)

Referência:
LAKATOS, Eva Maria et al. Fundamentos de metodologia científica. 8. ed. São Paulo: Atlas, 2017.
                    </div>
                </div>
            </div>

            <div class="accordion-item" id="acc-article">
                <div class="accordion-header" role="button" tabindex="0" aria-controls="content-article" aria-expanded="false">
                    <h5>Artigo científico (periódico)</h5>
                    <div class="accordion-toggle" aria-hidden="true">▾</div>
                </div>
                <div id="content-article" class="accordion-content" role="region" aria-labelledby="acc-article">
                    <div class="example">
No texto: (SANTOS, 2021)

Referência:
SANTOS, João. Educação e tecnologia: desafios do ensino híbrido. Revista Brasileira de Educação, v. 26, n. 2, p. 45–59, 2021.
                    </div>
                </div>
            </div>

            <div class="accordion-item" id="acc-web">
                <div class="accordion-header" role="button" tabindex="0" aria-controls="content-web" aria-expanded="false">
                    <h5>Página da web / documento institucional</h5>
                    <div class="accordion-toggle" aria-hidden="true">▾</div>
                </div>
                <div id="content-web" class="accordion-content" role="region" aria-labelledby="acc-web">
                    <div class="example">
No texto: (IBGE, 2023)

Referência:
INSTITUTO BRASILEIRO DE GEOGRAFIA E ESTATÍSTICA (IBGE). População residente 2023. Disponível em: https://www.ibge.gov.br. Acesso em: 10 out. 2025.
                    </div>
                </div>
            </div>

            <div class="accordion-item" id="acc-tcc">
                <div class="accordion-header" role="button" tabindex="0" aria-controls="content-tcc" aria-expanded="false">
                    <h5>Trabalho acadêmico (TCC, dissertação, tese)</h5>
                    <div class="accordion-toggle" aria-hidden="true">▾</div>
                </div>
                <div id="content-tcc" class="accordion-content" role="region" aria-labelledby="acc-tcc">
                    <div class="example">
No texto: (MORAES, 2020)

Referência:
MORAES, Ana Clara de. O uso de metodologias ativas na formação técnica. 2020. Trabalho de Conclusão de Curso (Técnico em Informática) — ETEC de Campo Limpo Paulista, Campo Limpo Paulista, 2020.
                    </div>
                </div>
            </div>

            <div class="accordion-item" id="acc-law">
                <div class="accordion-header" role="button" tabindex="0" aria-controls="content-law" aria-expanded="false">
                    <h5>Lei ou documento legal</h5>
                    <div class="accordion-toggle" aria-hidden="true">▾</div>
                </div>
                <div id="content-law" class="accordion-content" role="region" aria-labelledby="acc-law">
                    <div class="example">
No texto: (BRASIL, 1996)

Referência:
BRASIL. Lei nº 9.394, de 20 de dezembro de 1996. Estabelece as diretrizes e bases da educação nacional. Diário Oficial da União: Brasília, DF, 23 dez. 1996.
                    </div>
                </div>
            </div>
        </div>

        <h4 class="source-title" style="margin-top:16px;">Observações práticas rápidas</h4>
        <ul class="small">
            <li>A indicação de página é obrigatória em citações diretas (transcrições literais).</li>
            <li>Use aspas apenas em citações diretas curtas; citações longas nunca levam aspas.</li>
            <li>Consistência: se usar "et al." no texto, mantenha esse padrão em todo o documento.</li>
            <li>Verifique sempre se cada citação no texto possui sua referência completa na lista de referências (NBR 6023).</li>
        </ul>

        <h3>Checklist final</h3>
        <ul class="small">
            <li>Todas as citações no texto estão na lista de referências?</li>
            <li>Figuras e tabelas estão numeradas e referenciadas no texto?</li>
            <li>Margens, fonte e espaçamento conferidos?</li>
            <li>Ortografia e coesão revisadas; peça revisão ao orientador ou a um colega?</li>
            <li><strong>Citou corretamente?</strong> — verifique: citação direta curta (aspas + p.), citação direta longa (bloco + p.), citação indireta (sem aspas), citação de citação (apud), uso de <em>et al.</em> para 4+ autores e indicação de seção/parágrafo para fontes eletrônicas.</li>
        </ul>
        <br>
        <h3>Onde a plataforma ajuda</h3>
        <p>
            Nosso sistema aplica automaticamente a formatação básica (margens, fontes, espaçamento e estrutura), fornece campos
            organizados para cada seção do artigo e permite exportá-la. Assim, você gasta menos tempo com
            formatação e mais tempo escrevendo e revisando o conteúdo científico.
        </p>

        <p class="muted" style="margin-top:12px;">
            Observação: este guia é uma síntese prática. Consulte sempre a versão completa das normas ABNT (NBR 6022, NBR 6023, NBR 10520)
            e as diretrizes específicas da sua instituição ou periódico antes da submissão final.
        </p>
    </div>
</div>

<button onclick="scrollToTop()" id="btnTop" class="btn-top" title="Voltar ao topo">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"></path>
    </svg>
</button>

<script>
    (function(){
        function initAccordion(accordionId, singleOpen = true) {
            const accordion = document.getElementById(accordionId);
            if (!accordion) return;
            const items = accordion.querySelectorAll('.accordion-item');

            items.forEach(it => {
                const header = it.querySelector('.accordion-header');
                header.addEventListener('click', () => toggle(it, accordion, singleOpen));
                header.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(it, accordion, singleOpen); }
                });
            });

            function toggle(item, container, single) {
                const isOpen = item.classList.contains('open');
                if (isOpen) closeItem(item);
                else openItem(item);
                if (single && !isOpen) {
                    container.querySelectorAll('.accordion-item.open').forEach(other => {
                        if (other !== item) closeItem(other);
                    });
                }
            }

            function openItem(item) {
                const content = item.querySelector('.accordion-content');
                content.classList.add('open');
                content.style.maxHeight = content.scrollHeight + 'px';
                item.classList.add('open');
                const header = item.querySelector('.accordion-header');
                header.setAttribute('aria-expanded','true');
                setTimeout(() => item.scrollIntoView({behavior:'smooth', block:'start'}), 140);
            }

            function closeItem(item) {
                const content = item.querySelector('.accordion-content');
                content.style.maxHeight = '0';
                content.classList.remove('open');
                item.classList.remove('open');
                const header = item.querySelector('.accordion-header');
                header.setAttribute('aria-expanded','false');
            }
            items.forEach(it => closeItem(it));
        }
        initAccordion('citationsAccordion', true);
        initAccordion('examplesAccordion', true);
    })();

    const mybutton = document.getElementById("btnTop");

    window.onscroll = function() { scrollFunction() };

    function scrollFunction() {
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            mybutton.style.display = "flex";
        } else {
            mybutton.style.display = "none";
        }
    }

    function scrollToTop() {
        const currentScroll = document.documentElement.scrollTop || document.body.scrollTop;
        
        if (currentScroll > 0) {
            if (currentScroll <500) {
                window.scrollTo(0, 0);
                return; 
            }

            window.requestAnimationFrame(scrollToTop);
            window.scrollTo(0, currentScroll - currentScroll / 25);
        }
    }
</script>

<footer class="footer">
    <div class="footer-inner">
        <img src="img/logo.png" alt="Logo Editec" class="footer-logo">
        
        <span class="footer-text">© 2025 — Elaborada pelos autores — ediTEC</span>
    </div>
</footer>

</body>
</html>