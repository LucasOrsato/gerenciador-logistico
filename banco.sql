CREATE DATABASE IF NOT EXISTS gerenciador_logistico CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gerenciador_logistico;
CREATE TABLE IF NOT EXISTS produtos (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(120) NOT NULL,
 descricao VARCHAR(255),
 quantidade INT NOT NULL DEFAULT 0,
 peso DECIMAL(10,2) NOT NULL DEFAULT 0,
 valor DECIMAL(10,2) NOT NULL DEFAULT 0
);
CREATE TABLE IF NOT EXISTS enderecos (
 id INT AUTO_INCREMENT PRIMARY KEY,
 tipo ENUM('Origem','Destino','Ambos') NOT NULL,
 identificacao VARCHAR(120) NOT NULL,
 rua VARCHAR(150) NOT NULL,
 numero VARCHAR(20),
 cidade VARCHAR(100) NOT NULL,
 estado CHAR(2),
 cep VARCHAR(15)
);
CREATE TABLE IF NOT EXISTS transportadores (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(120) NOT NULL,
 documento VARCHAR(25),
 telefone VARCHAR(25),
 veiculo VARCHAR(100),
 placa VARCHAR(10)
);
CREATE TABLE IF NOT EXISTS fretes (
 id INT AUTO_INCREMENT PRIMARY KEY,
 data_frete DATE NOT NULL,
 transportador_id INT NOT NULL,
 origem_id INT NOT NULL,
 destino_id INT NOT NULL,
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (transportador_id) REFERENCES transportadores(id),
 FOREIGN KEY (origem_id) REFERENCES enderecos(id),
 FOREIGN KEY (destino_id) REFERENCES enderecos(id)
);
CREATE TABLE IF NOT EXISTS frete_produtos (
 id INT AUTO_INCREMENT PRIMARY KEY,
 frete_id INT NOT NULL,
 produto_id INT NOT NULL,
 quantidade INT NOT NULL,
 FOREIGN KEY (frete_id) REFERENCES fretes(id) ON DELETE CASCADE,
 FOREIGN KEY (produto_id) REFERENCES produtos(id)
);
