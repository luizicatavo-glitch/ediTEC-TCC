# ediTEC - Sistema de Formatação de Artigos Científicos

![Logo do ediTEC](design/logo.png)

## 📖 Descrição
O **ediTEC** é um sistema web desenvolvido para apoiar estudantes da ETEC de Campo Limpo Paulista na formatação de artigos científicos. O projeto visa automatizar a aplicação das normas da ABNT e diretrizes do Centro Paula Souza, solucionando dificuldades recorrentes como formatação manual e referências.

## 👥 Equipe de Desenvolvimento
* **Luiz Gustavo Dias Carvalhaes Aloi**
* **Miguel Augusto Borges de Jesus**

## 👩‍🏫 Orientadoras
* **Barbara Kathellen Andrade Porfirio**
* **Thaynara Cristina Maia dos Santos**

## 🛠 Tecnologias Utilizadas
* **HTML & CSS**
* **JavaScript** (ECMAScript 2024)
* **PHP**
* **MySQL**

## 📸 Telas do Sistema

### Tela Inicial
![Tela Inicial](design/home)

### Editor de Artigos
![Editor de Artigos](design/edicao)

## 🚀 Como executar o projeto

### Pré-requisitos
* **XAMPP** instalado (com Apache e MySQL).

### Passo a passo

1. **Prepare o Banco de Dados:**
   * Abra o XAMPP e inicie os módulos **Apache** e **MySQL**.
   * Acesse `http://localhost/phpmyadmin/` no seu navegador.
   * Clique em "Importar".
   * Selecione o arquivo `.sql` localizado na pasta `/database` deste repositório (originalmente `plataforma_schema.sql`).
   * Clique em "Executar" ou "Importar".

2. **Configure os Arquivos:**
   * Localize a pasta de instalação do XAMPP (geralmente `C:\xampp`) e abra a pasta `htdocs`.
   * Copie o conteúdo da pasta `/src` deste repositório para dentro de uma nova pasta em `htdocs` (ex: `C:\xampp\htdocs\site`).

3. **Acesse o Sistema:**
   * Abra o navegador e digite: `localhost/site`.
   * O sistema deve carregar automaticamente.

## 📚 Como citar o projeto
ALOI, Luiz Gustavo Dias Carvalhaes; DE JESUS, Miguel Augusto Borges. **Desenvolvimento de um sistema web para apoiar na formatação de artigos científicos na ETEC de Campo Limpo Paulista**. 2025. TCC (Ensino Médio com Habilitação Técnico em Informática para Internet) – ETEC de Campo Limpo Paulista, Centro Paula Souza.

---

*Para acessar o trabalho escrito completo, consulte a pasta `/docs`.*

