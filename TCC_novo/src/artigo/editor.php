<style>
    body, h2, .field label, .field small, .btn, .warning { font-family: Arial, sans-serif; }
    
    h2 { margin: 0 0 15px 0; font-size: 18px; font-weight: 700; color: #333; text-align: center; }
    
    .field { margin-bottom: 15px; }
    .field label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px; color: #444; }
    .field small { display: block; color: #666; font-size: 11px; margin-top: 2px; }
    
    .field input, .field textarea, .field select { 
        width: 100%; 
        padding: 8px 10px; 
        border: 1px solid #ccc; 
        border-radius: 4px; 
        font-size: 14px; 
        background: #f9f9f9;
        transition: border 0.2s;
        font-family: Arial, sans-serif;
        box-sizing: border-box;
    }
    .field input:focus, .field textarea:focus { border-color: #1976d2; background: #fff; outline: none; }
    .field textarea { min-height: 100px; resize: vertical; }
    
    .actions-grid { 
        display: grid; 
        grid-template-columns: 1fr 1fr;
        gap: 8px; 
        margin-bottom: 15px; 
        position: sticky; 
        top: 0; 
        background: #fff; 
        padding-bottom: 15px; 
        z-index: 5; 
        border-bottom: 1px solid #eee;
    }
    .actions-grid button { width: 100%; }

    .full-row { 
        grid-column: span 2; 
        width: 100%; 
        margin-top: 5px;
    }
    
    #focusSelect { 
        width: 100%;
        padding: 12px 10px;
        font-weight: 700;
        cursor: pointer;
        
        background-color: #f9f9f9; 
        border: 1px solid #ccc; 
        color: #333;
        
        text-align: center;
        text-align-last: center;
        -moz-text-align-last: center;
        
        box-shadow: 0 1px 2px rgba(0,0,0,0.05); 
        appearance: none;       
        -webkit-appearance: none;
        transition: border 0.2s;
    }
    
    #focusSelect option {
        text-align: center;
    }
    
    #focusSelect:focus {
        border-color: #1976d2; 
        background: #fff; 
        outline: none;
    }

    .btn { color:#fff; border:none; padding:10px; border-radius:4px; font-weight:600; cursor:pointer; font-size: 13px; font-family: Arial, sans-serif; }
    
    .btn-save { background: #2e7d32; } .btn-save:hover { background: #1b5e20; }
    
    .btn-docx { background: #2b579a; } .btn-docx:hover { background: #1e3e6f; }

    .subsections { margin-top:10px; }
    
    .subsection { 
        background:#f5f5f5; padding:12px; border-radius:6px; 
        margin-bottom:12px; border: 1px solid #ddd; 
        position: relative;
    }
    
    .tertiary {
        background: #fff; margin-top: 10px; padding: 10px;
        border-radius: 4px; border: 1px solid #e0e0e0;
        border-left: 4px solid #66bb6a;
        margin-left: 20px;
    }

    .sub-actions { display:flex; gap:8px; margin-bottom:8px; align-items:center; }

    .add-sub-btn, .add-tertiary-btn { 
        background:#4caf50; color:#fff; border:none; 
        width:100%; padding:8px; margin-top:8px; 
        cursor:pointer; font-size:12px; font-weight: bold; 
        border-radius: 4px; display: flex; justify-content: center; 
        font-family: Arial, sans-serif;
    }
    .add-sub-btn:hover, .add-tertiary-btn:hover { background: #43a047; }

    .remove-btn { 
        background: #ef5350; color:white; border:none; 
        padding: 0 12px; height: 35px; border-radius: 4px; 
        cursor: pointer; font-weight: bold; font-size: 12px;
        font-family: Arial, sans-serif;
    }
    .remove-btn:hover { background: #e53935; }

    .warning { color:#d32f2f; font-size:12px; margin-top:4px; font-weight: bold; font-family: Arial, sans-serif; }
</style>

<div>
    <h2>Editar Artigo</h2>

    <div class="actions-grid">
        <button id="btnSave" title="Salvar artigo" type="button" class="btn btn-save">Salvar</button>
        <button id="btnDocx" title="Download .docx" type="button" class="btn btn-docx">Download</button>
        <br>
        <div class="field full-row" style="margin-bottom: 0;">
            <label for="focusSelect">Foco de Edição</label>
            <select id="focusSelect" title="Foco de Edição">
                <option value="all" selected>Artigo Científico (Completo)</option>
                <option value="cabecalho">Cabeçalho (Título, Autor, Inst.)</option>
                <option value="resumo">Resumo e Palavras-chave</option>
                <option value="introducao">Introdução</option>
                <option value="objetivos">Objetivos</option>
                <option value="referencial">Referencial Teórico</option>
                <option value="metodologia">Metodologia</option>
                <option value="resultados">Resultados</option>
                <option value="discussao">Discussão</option>
                <option value="conclusao">Conclusão</option>
                <option value="referencias">Referências</option>
            </select>
        </div>
    </div>

    <div class="field">
        <label>Fonte de Visualização</label>
        <select id="fontSelect">
            <option value="Times New Roman">Times New Roman</option>
            <option value="Arial" selected>Arial</option>
            <option value="Calibri">Calibri</option>
        </select>
    </div>

    <form id="articleForm" method="POST" action="../backend/salvar_artigo.php">
        <input type="hidden" name="id" id="articleId" value="<?= htmlspecialchars($artigo['id']) ?>">
        <input type="hidden" name="rendered_html" id="rendered_html" value="<?= htmlspecialchars($artigo['rendered_html'] ?? '') ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <div class="field-group" data-section="cabecalho">
            <div class="field">
                <label>Título do Artigo</label>
                <input id="titulo" name="titulo" type="text" value="<?= htmlspecialchars($artigo['titulo']) ?>" placeholder="TÍTULO EM MAIÚSCULO">
            </div>

            <div class="field">
                <label>Autor(es) (um por linha)</label>
                <textarea id="autor" name="autor" rows="2"><?= htmlspecialchars($artigo['autor'] ?: ($_SESSION['user']['nome'] ?? '')) ?></textarea>
            </div>

            <div class="field">
                <label>Instituição</label>
                <input id="instituicao" name="instituicao" type="text" value="<?= htmlspecialchars($artigo['instituicao'] ?: ($_SESSION['user']['instituicao'] ?? '')) ?>">
            </div>
            
            <div style="display:flex; gap:10px;">
                <div class="field" style="flex:1;">
                    <label>Unidade</label>
                    <input id="unidade" name="unidade" type="text" value="<?= htmlspecialchars($artigo['unidade']) ?>">
                </div>
                <div class="field" style="flex:1;">
                    <label>Curso</label>
                    <input id="curso" name="curso" type="text" value="<?= htmlspecialchars($artigo['curso']) ?>">
                </div>
            </div>

            <div class="field">
                <label>Orientador(es) (um por linha)</label>
                <textarea id="orientador" name="orientador" rows="2"><?= htmlspecialchars($artigo['orientador'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="field-group" data-section="resumo">
            <div class="field">
                <label>Resumo (NBR 6028)</label>
                <small>Parágrafo único, voz ativa, 150 a 250 palavras.</small>
                <textarea id="resumo" name="resumo"><?= htmlspecialchars($artigo['resumo']) ?></textarea>
                <div id="resumoWarning" class="warning"></div>
            </div>

            <div class="field">
                <label>Palavras-chave</label>
                <small>Separadas por ponto e vírgula (;).</small>
                <input id="palavras_chave" name="palavras_chave" type="text" value="<?= htmlspecialchars($artigo['palavras_chave']) ?>">
            </div>
        </div>

        <?php 
        $sections = [
            'introducao' => 'Introdução',
            'objetivos' => 'Objetivos',
            'referencial' => 'Referencial Teórico',
            'metodologia' => 'Metodologia',
            'resultados' => 'Resultados',
            'discussao' => 'Discussão',
            'conclusao' => 'Conclusão'
        ];
        foreach($sections as $key => $label): ?>
        <div class="field-group" data-section="<?= $key ?>">
            <div class="field">
                <label><?= $label ?></label>
                <small>Para citação longa (>3 linhas), inicie o parágrafo com <code>&gt;</code></small>
                <textarea id="<?= $key ?>" name="<?= $key ?>"><?= htmlspecialchars($artigo[$key]) ?></textarea>
                <div class="subsections" data-for="<?= $key ?>"></div>
                <button type="button" class="add-sub-btn" data-section="<?= $key ?>">+ Adicionar Seção Secundária (1.1)</button>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="field-group" data-section="referencias">
            <div class="field">
                <label>Referências</label>
                <small>Uma por linha. O sistema alinhará à esquerda automaticamente.</small>
                <textarea id="referencias" name="referencias" placeholder="SOBRENOME, Nome. Título. Edição. Local: Editora, Ano."><?= htmlspecialchars($artigo['referencias']) ?></textarea>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const focusSelect = document.getElementById('focusSelect');
    const fieldGroups = document.querySelectorAll('.field-group');

    function updateFocus() {
        const selected = focusSelect.value;
        fieldGroups.forEach(group => {
            if (selected === 'all') {
                group.style.display = 'block';
            } else {
                group.style.display = (group.getAttribute('data-section') === selected) ? 'block' : 'none';
            }
        });
    }

    if(focusSelect) {
        focusSelect.addEventListener('change', updateFocus);
        updateFocus();
    }

    const sectionMap = {
        'introducao': 1, 'objetivos': 2, 'referencial': 3,
        'metodologia': 4, 'resultados': 5, 'discussao': 6, 'conclusao': 7
    };

    function updateButtonLabels() {
        document.querySelectorAll('.add-sub-btn').forEach(btn => {
            const sectionKey = btn.getAttribute('data-section');
            const baseNum = sectionMap[sectionKey];
            if(baseNum) {
                const container = document.querySelector(`.subsections[data-for="${sectionKey}"]`);
                const nextNum = container ? (container.querySelectorAll('.subsection').length + 1) : 1;
                btn.innerText = `+ Adicionar Seção Secundária (${baseNum}.${nextNum})`;
            }
        });

        document.querySelectorAll('.subsection').forEach(sub => {
            const parentWrapper = sub.closest('.subsections');
            if(!parentWrapper) return;
            const sectionKey = parentWrapper.getAttribute('data-for');
            const baseNum = sectionMap[sectionKey];
            const allSubs = Array.from(parentWrapper.querySelectorAll('.subsection'));
            const subIndex = allSubs.indexOf(sub) + 1;
            const tertBtn = sub.querySelector('.add-tertiary-btn');
            if(tertBtn) {
                const nextTertNum = sub.querySelectorAll('.tertiary').length + 1;
                tertBtn.innerText = `+ Adicionar Seção Terciária (${baseNum}.${subIndex}.${nextTertNum})`;
            }
        });
    }

    updateButtonLabels();

    const formEl = document.getElementById('articleForm');
    if(formEl) {
        const observer = new MutationObserver(() => {
            observer.disconnect();
            updateButtonLabels();
            observer.observe(formEl, { childList: true, subtree: true });
        });
        observer.observe(formEl, { childList: true, subtree: true });
    }
});
</script>   