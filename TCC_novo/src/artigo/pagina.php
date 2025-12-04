<?php
require_once '../backend/auth_check.php';
require_once '../backend/config.php';

if (!isset($_SESSION)) session_start();
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$artigo = [
    'id' => '', 'titulo' => '', 'resumo' => '', 'palavras_chave' => '',
    'introducao' => '', 'objetivos' => '', 'referencial' => '', 'metodologia' => '',
    'resultados' => '', 'discussao' => '', 'conclusao' => '', 'referencias' => '',
    'autor' => '', 'orientador' => '', 'instituicao' => '', 'unidade' => '', 'curso' => '',
    'sections_json' => '{}',
    'rendered_html' => ''
];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM artigos WHERE id = :id AND usuario_id = :uid");
    $stmt->execute([':id' => $id, ':uid' => $_SESSION['user']['id']]);
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        function safe_decrypt($val) {
            if (!$val || !function_exists('decrypt_text')) return $val;
            try { 
                $res = decrypt_text($val); 
                return ($res === false) ? $val : $res; 
            } catch(Exception $e) { return $val; }
        }

        $fields = ['autor','orientador','resumo','introducao','objetivos','referencial','metodologia','resultados','discussao','conclusao','referencias','palavras_chave','unidade','curso','instituicao'];
        foreach ($fields as $f) { 
            if (!empty($row[$f])) $row[$f] = safe_decrypt($row[$f]); 
        }
        if (empty($row['sections_json'])) $row['sections_json'] = '{}';
        $artigo = array_merge($artigo, $row);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Editor</title>
    <link rel="icon" href="../img/logo_azl.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
    
    <script src="https://unpkg.com/docx@7.1.0/build/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.0.6/purify.min.js"></script>

    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 0; color: #333; height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
        
        .top-bar { flex: 0 0 50px; background: #fff; border-bottom: 1px solid #ddd; display: flex; align-items: center; padding: 0 20px; z-index: 10; }
        .btn-back { background:#1E88E5; color:#fff; padding:6px 14px; border-radius:6px; text-decoration:none; font-weight:bold; font-size: 14px; font-family: Arial, sans-serif; }
        .btn-back:hover { background:#1565C0; }
        
        .page-wrap { display: flex; flex: 1; overflow: hidden; position: relative; }
        .left { width: 360px; min-width: 300px; background: #fff; border-right: 1px solid #ddd; display: flex; flex-direction: column; z-index: 2; transition: transform 0.3s ease; }
        .editor-content { flex: 1; overflow-y: auto; padding: 15px; }
        .right { flex: 1; background: #ffffff44; position: relative; overflow: hidden; display: flex; flex-direction: column; }
        .preview-scroller { flex: 1; overflow: auto; padding: 30px; display: flex; justify-content: center; align-items: flex-start; }

        @media (max-width: 900px) {
            body { overflow: auto; height: auto; display: block; }
            .page-wrap { display: block; height: auto; overflow: visible; }
            .top-bar { position: sticky; top: 0; }
            .left { width: 100%; height: auto; border-right: none; border-bottom: 1px solid #ddd; }
            .editor-content { overflow: visible; max-height: 50vh; overflow-y: auto; }
            .right { height: auto; min-height: 100vh; overflow: visible; }
        }

        #previewSheet { 
            background: #fff; color: #000; 
            font-family: Arial, sans-serif;
            font-size: 12pt; line-height: 1.5; 
            width: 210mm; min-height: 297mm; 
            padding: 3cm 2cm 2cm 3cm; 
            box-shadow: 0 0 15px rgba(0,0,0,0.2); 
            margin-bottom: 50px; 
            transform-origin: top center; 
            text-align: justify;
            overflow-wrap: break-word;
        }
        
        @media screen and (max-width: 1450px) { #previewSheet { transform: scale(0.85); margin-bottom: -15%; } }
        @media screen and (max-width: 1100px) { #previewSheet { transform: scale(0.7); margin-bottom: -30%; } }
        @media screen and (max-width: 900px) { #previewSheet { transform: none; width: 100%; padding: 2cm 1.5cm; margin-bottom: 20px; } }

        #previewSheet p { margin: 0; text-indent: 1.25cm; text-align: justify; }
        #previewSheet h1 { font-size: 12pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.2; text-align: center; }
        #previewSheet h3 { font-size: 12pt; font-weight: bold; text-transform: uppercase; margin: 24px 0 12px 0; text-align: left; }
        #previewSheet h4.sec-secondary { font-size: 12pt; font-weight: normal; text-transform: uppercase; margin: 24px 0 12px 0; text-align: left; }
        #previewSheet h5.sec-tertiary { font-size: 12pt; font-weight: bold; text-transform: none; margin: 24px 0 12px 0; text-align: left; }

        .citation-block { margin: 1em 0 1em 4cm; font-size: 10pt; line-height: 1.0; text-align: justify; text-indent: 0; }
        .references { margin-top: 2em; line-height: 1.0; text-align: left; }
        .ref-item { margin-bottom: 1em; text-indent: 0 !important; padding-left: 0; display: block; }

        .article-header { display: flex; flex-direction: column; align-items: center; gap: 15px; margin-bottom: 40px; text-align: center; }
        .header-meta { display: flex; flex-direction: column; align-items: center; gap: 5px; }
        .meta-details { font-size: 10pt; text-transform: uppercase; font-weight: bold; }
        .author-name { font-size: 12pt; font-weight: bold; }
        .advisor-block { font-size: 10pt; font-style: italic; text-align: center; }

        .abstract-block h3 { margin: 0 0 0.5em 0 !important; }
        .abstract-text { text-indent: 0 !important; font-size: 12pt; line-height: 1.0; }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="../home.php" class="btn-back">⯇ Voltar</a>
        <span style="margin-left: 15px; font-size: 14px; color: #666; font-family: Arial, sans-serif;">Editando: <b><?= htmlspecialchars($artigo['titulo'] ?: 'Novo Artigo') ?></b></span>
        
        <?php if(!empty($_GET['msg'])): ?>
            <span style="margin-left:20px; color:green; font-weight:bold; background:#e8f5e9; padding:4px 8px; border-radius:4px; font-family: Arial, sans-serif;">
                <?= htmlspecialchars($_GET['msg']) ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="page-wrap">
        <div class="left">
            <div class="editor-content">
                <?php include 'editor.php'; ?>
            </div>
        </div>
        <div class="right">
            <div class="preview-scroller">
                <div id="previewSheet"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const resumoField = document.getElementById('resumo');
            const resumoWarn = document.getElementById('resumoWarning');

            function updateResumoCount() {
                if(!resumoField || !resumoWarn) return;
                
                const text = resumoField.value.trim();
                const wordCount = text ? text.split(/\s+/).length : 0;
                
                if (wordCount < 150) {
                    resumoWarn.style.color = '#d32f2f'; 
                    resumoWarn.textContent = `Contagem atual: ${wordCount} palavras`;
                } else {
                    resumoWarn.style.color = '#2e7d32'; 
                    resumoWarn.textContent = `Contagem atual: ${wordCount} palavras`;
                }
            }

            if(resumoField) {
                resumoField.addEventListener('input', updateResumoCount);
                updateResumoCount();
            }

            let unsavedChanges = false;
            const v = (id) => { const el = document.getElementById(id); return el ? el.value : ''; };
            const escapeHtml = (text) => {
                if (!text) return '';
                return String(text).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
            };
            
            function createTertiaryField(container, data = null) {
                const div = document.createElement('div');
                div.className = 'tertiary';
                const titleVal = data ? data.title : '';
                const contentVal = data ? data.content : '';
                div.innerHTML = `
                    <div class="sub-actions">
                        <input class="ter-title" placeholder="Título (1.1.1)" style="flex:1" value="${escapeHtml(titleVal)}">
                        <button type="button" class="remove-btn">Remover</button>
                    </div>
                    <textarea class="ter-content" rows="2" placeholder="Conteúdo...">${escapeHtml(contentVal)}</textarea>
                `;
                container.appendChild(div);
                
                div.querySelector('.remove-btn').addEventListener('click', () => { 
                    if(confirm("Tem certeza que deseja remover esta seção terciária?")) {
                        div.remove(); 
                        updatePreview(); 
                    }
                });
                
                div.querySelectorAll('input, textarea').forEach(i => i.addEventListener('input', updatePreview));
                return div;
            }

            function createSubsectionField(sectionKey, data = null) {
                const container = document.querySelector(`.subsections[data-for="${sectionKey}"]`);
                if(!container) return;
                const div = document.createElement('div');
                div.className = 'subsection';
                const titleVal = data ? data.title : '';
                const contentVal = data ? data.content : '';
                div.innerHTML = `
                    <div class="sub-actions">
                        <input class="sub-title" placeholder="Título Secundário (1.1)" style="flex:1" value="${escapeHtml(titleVal)}">
                        <button type="button" class="remove-btn">Remover</button>
                    </div>
                    <textarea class="sub-content" rows="3" placeholder="Conteúdo...">${escapeHtml(contentVal)}</textarea>
                    <div class="tertiaries"></div>
                    <button type="button" class="add-tertiary-btn">+ Adicionar Seção Terciária</button>
                `;
                container.appendChild(div);
                const tertContainer = div.querySelector('.tertiaries');
                
                div.querySelector('.add-tertiary-btn').addEventListener('click', () => { createTertiaryField(tertContainer); });
                
                div.querySelector('.remove-btn').addEventListener('click', () => { 
                    if(confirm("ATENÇÃO: Tem certeza que deseja remover esta seção secundária? Isso apagará também todas as seções terciárias contidas nela.")) {
                        div.remove(); 
                        updatePreview(); 
                    }
                });
                
                div.querySelectorAll('input, textarea').forEach(i => i.addEventListener('input', updatePreview));
                
                if (data && data.tertiaries && Array.isArray(data.tertiaries)) {
                    data.tertiaries.forEach(tertData => { createTertiaryField(tertContainer, tertData); });
                }
            }

            document.querySelectorAll('.add-sub-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const section = this.getAttribute('data-section');
                    createSubsectionField(section);
                });
            });

            const savedSectionsJSON = `<?= $artigo['sections_json'] ?>`;
            try {
                if(savedSectionsJSON && savedSectionsJSON !== '{}') {
                    const savedData = JSON.parse(savedSectionsJSON);
                    for (const [key, subsections] of Object.entries(savedData)) {
                        if(Array.isArray(subsections)) {
                            subsections.forEach(subData => { createSubsectionField(key, subData); });
                        }
                    }
                }
            } catch(e) { console.error("Erro ao carregar seções salvas:", e); }

            function processText(raw) {
                if(!raw) return '';
                const paras = raw.replace(/\r\n/g, '\n').split(/\n+/).filter(Boolean);
                let html = '';
                paras.forEach(p => {
                    p = p.trim();
                    if(p.startsWith('>')) html += `<div class="citation-block">${escapeHtml(p.substring(1).trim())}</div>`;
                    else html += `<p>${escapeHtml(p)}</p>`;
                });
                return html;
            }

            function renderSubsectionsPreview(key) {
                const container = document.querySelector(`.subsections[data-for="${key}"]`);
                if(!container) return '';
                const baseNum = { 'introducao':1, 'objetivos':2, 'referencial':3, 'metodologia':4, 'resultados':5, 'discussao':6, 'conclusao':7 }[key] || 0;
                let html = '';
                let secCounter = 0;
                container.querySelectorAll('.subsection').forEach(sub => {
                    secCounter++;
                    const titleInput = sub.querySelector('.sub-title')?.value;
                    const content = sub.querySelector('.sub-content')?.value;
                    if(titleInput) {
                        const fullTitle = `${baseNum}.${secCounter} ${titleInput.toUpperCase()}`;
                        html += `<h4 class="sec-secondary">${escapeHtml(fullTitle)}</h4>`;
                    }
                    if(content) html += processText(content);
                    const tertContainer = sub.querySelector('.tertiaries');
                    if(tertContainer) {
                        let tertCounter = 0;
                        tertContainer.querySelectorAll('.tertiary').forEach(tert => {
                            tertCounter++;
                            const tTitle = tert.querySelector('.ter-title')?.value;
                            const tCont = tert.querySelector('.ter-content')?.value;
                            if(tTitle) {
                                const fmtTitle = tTitle.charAt(0).toUpperCase() + tTitle.slice(1).toLowerCase();
                                const numPrefix = `${baseNum}.${secCounter}.${tertCounter}`;
                                html += `<h5 class="sec-tertiary">${numPrefix} ${escapeHtml(fmtTitle)}</h5>`;
                            }
                            if(tCont) html += processText(tCont);
                        });
                    }
                });
                return html;
            }

            function updatePreview() {
                const sheet = document.getElementById('previewSheet');
                if(!sheet) return;
                const fontSelect = document.getElementById('fontSelect');
                const font = fontSelect ? fontSelect.value : 'Times New Roman';
                
                if (font === 'Arial') {
                    sheet.style.fontFamily = 'Arial, sans-serif';
                } else if (font === 'Calibri') {
                    sheet.style.fontFamily = 'Calibri, sans-serif';
                } else {
                    sheet.style.fontFamily = '"Times New Roman", Times, serif';
                }
                
                const title = escapeHtml(v('titulo') || 'TÍTULO DO ARTIGO').toUpperCase();
                const metaLines = [v('instituicao'), v('unidade'), v('curso')].filter(Boolean);
                const metaHtml = metaLines.map(l => `<div class="meta-details">${escapeHtml(l)}</div>`).join('');
                const autoresHtml = (v('autor') || 'Autor').split('\n').filter(Boolean).map(l => `<div class="author-name">${escapeHtml(l)}</div>`).join('');
                
                let orientadorHtml = '';
                const orientList = (v('orientador') || '').split('\n').filter(Boolean);
                if (orientList.length) {
                    const label = orientList.length > 1 ? 'Orientadores:' : 'Orientador:';
                    orientadorHtml = `<div class="advisor-block"><span>${label}</span>` + orientList.map(o => `<div>${escapeHtml(o)}</div>`).join('') + `</div>`;
                }

                let resumoSection = '';
                if(v('resumo') || v('palavras_chave')) {
                    resumoSection = `<div class="abstract-block">${v('resumo') ? `<h3>RESUMO</h3><div class="abstract-text">${escapeHtml(v('resumo'))}</div>` : ''}${v('palavras_chave') ? `<div class="abstract-text" style="margin-top:15px;"><strong>Palavras-chave:</strong> ${escapeHtml(v('palavras_chave'))}</div>` : ''}</div>`;
                }
                
                let html = `<div class="article-header"><div class="header-meta">${metaHtml}</div><h1>${title}</h1><div class="header-meta">${autoresHtml}${orientadorHtml}</div></div>${resumoSection}`;

                const sections = ['introducao', 'objetivos', 'referencial', 'metodologia', 'resultados', 'discussao', 'conclusao'];
                const titles = { 'introducao': '1 INTRODUÇÃO', 'objetivos': '2 OBJETIVOS', 'referencial': '3 REFERENCIAL TEÓRICO', 'metodologia': '4 METODOLOGIA', 'resultados': '5 RESULTADOS', 'discussao': '6 DISCUSSÃO', 'conclusao': '7 CONCLUSÃO' };
                
                sections.forEach(sec => {
                    const content = processText(v(sec));
                    const subs = renderSubsectionsPreview(sec);
                    if (content || subs || v(sec)) html += `<h3>${titles[sec] || sec.toUpperCase()}</h3>${content}${subs}`;
                });

                if(v('referencias')) {
                    const lines = v('referencias').split('\n').map(r => r.trim()).filter(Boolean).sort((a, b) => a.localeCompare(b, 'pt', { sensitivity: 'base' }));
                    html += `<div class="references"><h3>REFERÊNCIAS</h3>${lines.map(r => `<div class="ref-item">${escapeHtml(r)}</div>`).join('')}</div>`;
                }

                sheet.innerHTML = html;
                const renderInput = document.getElementById('rendered_html');
                if(renderInput) renderInput.value = html;
                
                if(typeof updateResumoCount === 'function') updateResumoCount();
            }

            const btnSave = document.getElementById('btnSave');
            if(btnSave) {
                btnSave.addEventListener('click', (e) => { 
                    e.preventDefault();
                    let sectionsData = {};
                    const mainSections = ['introducao', 'objetivos', 'referencial', 'metodologia', 'resultados', 'discussao', 'conclusao'];
                    mainSections.forEach(key => {
                        const container = document.querySelector(`.subsections[data-for="${key}"]`);
                        if (container) {
                            let subs = [];
                            container.querySelectorAll('.subsection').forEach(subDiv => {
                                let tertiaries = [];
                                subDiv.querySelectorAll('.tertiary').forEach(terDiv => {
                                    tertiaries.push({ title: terDiv.querySelector('.ter-title').value, content: terDiv.querySelector('.ter-content').value });
                                });
                                subs.push({ title: subDiv.querySelector('.sub-title').value, content: subDiv.querySelector('.sub-content').value, tertiaries: tertiaries });
                            });
                            if (subs.length > 0) sectionsData[key] = subs;
                        }
                    });
                    let form = document.getElementById('articleForm');
                    let jsonInput = document.getElementById('sections_json_input');
                    if (!jsonInput) { jsonInput = document.createElement('input'); jsonInput.type = 'hidden'; jsonInput.name = 'sections_json'; jsonInput.id = 'sections_json_input'; form.appendChild(jsonInput); }
                    jsonInput.value = JSON.stringify(sectionsData);
                    unsavedChanges = false;
                    updatePreview(); 
                    form.submit(); 
                });
            }
            
            const btnDocx = document.getElementById('btnDocx');
            if(btnDocx) {
                btnDocx.addEventListener('click', () => {
                    const { Document, Packer, Paragraph, TextRun, AlignmentType } = docx;

                    const selectElement = document.getElementById('fontSelect');
                    const fontName = selectElement ? selectElement.value : "Times New Roman";

                    const margins = { top: 1701, right: 1134, bottom: 1134, left: 1701 };
                    
                    const createPara = (text, opts = {}) => {
                        if (!text) return null;
                        
                        if (text.trim().startsWith('>')) {
                            return new Paragraph({
                                alignment: AlignmentType.JUSTIFIED,
                                indent: { left: 2268 },
                                spacing: { line: 240 },
                                children: [new TextRun({ 
                                    text: text.replace('>','').trim(), 
                                    font: fontName, 
                                    size: 20 
                                })]
                            });
                        }

                        return new Paragraph({
                            children: [new TextRun({ text: text, font: fontName, size: 24 })],
                            alignment: opts.align || AlignmentType.JUSTIFIED,
                            indent: opts.noIndent ? {} : { firstLine: 708 }, 
                            spacing: { line: 360, after: 0 }, 
                            ...opts.extra
                        });
                    };

                    const splitAndCreate = (rawText, opts={}) => {
                        if (!rawText) return [];
                        return rawText.replace(/\r\n/g, '\n').split('\n').filter(t => t.trim() !== '').map(line => createPara(line, opts));
                    };

                    let children = [];

                    [v('instituicao'), v('unidade'), v('curso')].forEach(line => {
                        if(line) children.push(new Paragraph({
                            children: [new TextRun({ text: line.toUpperCase(), font: fontName, size: 24, bold: true })], 
                            alignment: AlignmentType.CENTER,
                            spacing: { line: 240 } 
                        }));
                    });
                    children.push(new Paragraph({ text: "", spacing: { after: 240 } }));

                    const autores = v('autor').split('\n').filter(Boolean);
                    autores.forEach(aut => {
                        children.push(new Paragraph({
                            children: [new TextRun({ text: aut, font: fontName, size: 24, bold: true })],
                            alignment: AlignmentType.CENTER,
                            spacing: { line: 240 }
                        }));
                    });

                    const orientadores = v('orientador').split('\n').filter(Boolean);
                    if(orientadores.length > 0) {
                        children.push(new Paragraph({ 
                            spacing: { before: 240 }, 
                            children: [new TextRun({ 
                                text: (orientadores.length > 1 ? "Orientadores:" : "Orientador:"), 
                                font: fontName, 
                                size: 20, 
                                italics: true 
                            })], 
                            alignment: AlignmentType.CENTER 
                        }));
                        orientadores.forEach(ori => {
                            children.push(new Paragraph({ 
                                children: [new TextRun({ 
                                    text: ori, 
                                    font: fontName, 
                                    size: 20 
                                })], 
                                alignment: AlignmentType.CENTER 
                            }));
                        });
                    }
                    children.push(new Paragraph({ text: "", spacing: { after: 480 } }));

                    if(v('titulo')) {
                        children.push(new Paragraph({
                            children: [new TextRun({ 
                                text: v('titulo').toUpperCase(), 
                                font: fontName, 
                                size: 24,
                                bold: true 
                            })],
                            alignment: AlignmentType.CENTER,
                            spacing: { after: 480 }
                        }));
                    }

                    if(v('resumo')) {
                        children.push(new Paragraph({ children: [new TextRun({ text: "RESUMO", font: fontName, size: 24, bold: true })], alignment: AlignmentType.LEFT, spacing: { after: 120 } }));
                        children.push(new Paragraph({ children: [new TextRun({ text: v('resumo'), font: fontName, size: 24 })], alignment: AlignmentType.JUSTIFIED, spacing: { line: 240 } }));
                    }
                    if(v('palavras_chave')) {
                        children.push(new Paragraph({ spacing: { before: 240 }, children: [new TextRun({ text: "Palavras-chave: ", font: fontName, size: 24, bold: true }), new TextRun({ text: v('palavras_chave'), font: fontName, size: 24 })], alignment: AlignmentType.JUSTIFIED }));
                    }

                    const sections = ['introducao', 'objetivos', 'referencial', 'metodologia', 'resultados', 'discussao', 'conclusao'];
                    const titles = { 'introducao': '1 INTRODUÇÃO', 'objetivos': '2 OBJETIVOS', 'referencial': '3 REFERENCIAL TEÓRICO', 'metodologia': '4 METODOLOGIA', 'resultados': '5 RESULTADOS', 'discussao': '6 DISCUSSÃO', 'conclusao': '7 CONCLUSÃO' };
                    const baseNums = { 'introducao':1, 'objetivos':2, 'referencial':3, 'metodologia':4, 'resultados':5, 'discussao':6, 'conclusao':7 };

                    sections.forEach(key => {
                        const content = v(key);
                        const container = document.querySelector(`.subsections[data-for="${key}"]`);
                        const hasSubs = container && container.querySelectorAll('.subsection').length > 0;

                        if (content || hasSubs) {
                            children.push(new Paragraph({
                                children: [new TextRun({ text: titles[key], font: fontName, size: 24, bold: true })],
                                spacing: { before: 480, after: 240 },
                                alignment: AlignmentType.LEFT
                            }));
                            
                            children.push(...splitAndCreate(content));

                            if (container) {
                                let subCounter = 0;
                                container.querySelectorAll('.subsection').forEach(sub => {
                                    subCounter++;
                                    const stitle = sub.querySelector('.sub-title').value;
                                    const scontent = sub.querySelector('.sub-content').value;
                                    if(stitle) {
                                        children.push(new Paragraph({
                                            children: [new TextRun({ text: `${baseNums[key]}.${subCounter} ${stitle.toUpperCase()}`, font: fontName, size: 24 })],
                                            spacing: { before: 360, after: 240 },
                                            alignment: AlignmentType.LEFT
                                        }));
                                    }
                                    children.push(...splitAndCreate(scontent));
                                    const tertContainer = sub.querySelector('.tertiaries');
                                    if (tertContainer) {
                                        let tertCounter = 0;
                                        tertContainer.querySelectorAll('.tertiary').forEach(tert => {
                                            tertCounter++;
                                            const ttitle = tert.querySelector('.ter-title').value;
                                            const tcontent = tert.querySelector('.ter-content').value;
                                            if(ttitle) {
                                                const fmtTitle = ttitle.charAt(0).toUpperCase() + ttitle.slice(1).toLowerCase();
                                                children.push(new Paragraph({
                                                    children: [new TextRun({ text: `${baseNums[key]}.${subCounter}.${tertCounter} ${fmtTitle}`, font: fontName, size: 24, bold: true })],
                                                    spacing: { before: 360, after: 240 },
                                                    alignment: AlignmentType.LEFT
                                                }));
                                            }
                                            children.push(...splitAndCreate(tcontent));
                                        });
                                    }
                                });
                            }
                        }
                    });

                    if(v('referencias')) {
                        children.push(new Paragraph({
                            children: [new TextRun({ text: "REFERÊNCIAS", font: fontName, size: 24, bold: true })],
                            spacing: { before: 480, after: 240 },
                            alignment: AlignmentType.LEFT
                        }));
                        const refs = v('referencias').split('\n').map(r => r.trim()).filter(Boolean).sort((a, b) => a.localeCompare(b, 'pt', { sensitivity: 'base' }));
                        refs.forEach(r => {
                            children.push(new Paragraph({
                                children: [new TextRun({ text: r, font: fontName, size: 24 })],
                                alignment: AlignmentType.LEFT,
                                spacing: { line: 240, after: 240 },
                                indent: { left: 0 } 
                            }));
                        });
                    }
                    const doc = new Document({
                        styles: {
                            paragraphStyles: [
                                {
                                    id: "Normal",
                                    name: "Normal",
                                    run: {
                                        font: {
                                            ascii: fontName,
                                            hAnsi: fontName,
                                            cs: fontName,
                                            eastAsia: fontName
                                        },
                                        size: 24,
                                        color: "000000"
                                    },
                                    paragraph: {
                                        spacing: { line: 360 }
                                    }
                                }
                            ]
                        },
                        sections: [{
                            properties: { page: { margin: margins } },
                            children: children
                        }]
                    });

                    Packer.toBlob(doc).then(blob => {
                        saveAs(blob, (v('titulo')||'artigo') + ".docx");
                    });
                });
            }

            document.addEventListener('input', (e) => {
                if(['INPUT','TEXTAREA','SELECT'].includes(e.target.tagName)) { unsavedChanges = true; updatePreview(); }
            });
            window.addEventListener('beforeunload', (e) => { if(unsavedChanges) { e.preventDefault(); e.returnValue = ''; } });

            updatePreview();
        });
    </script>
</body>
</html>