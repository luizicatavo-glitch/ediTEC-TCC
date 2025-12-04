CREATE DATABASE IF NOT EXISTS plataforma
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;
USE plataforma;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('user','admin') DEFAULT 'user',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS artigos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(255),
    autor TEXT,
    instituicao VARCHAR(255),
    unidade VARCHAR(255),
    curso VARCHAR(255),
    orientador TEXT,
    resumo LONGTEXT,
    palavras_chave TEXT,
    introducao LONGTEXT,
    objetivos LONGTEXT,
    referencial LONGTEXT,
    metodologia LONGTEXT,
    resultados LONGTEXT,
    discussao LONGTEXT,
    conclusao LONGTEXT,
    referencias LONGTEXT,
    rendered_html LONGTEXT,
    sections_json JSON,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;