# LogiControl — Gerenciador Logístico

Projeto acadêmico da disciplina **Fundamentos de Programação para Internet**.

## Objetivo
Gerenciar produtos, endereços, transportadores e fretes. Um frete pode relacionar um transportador a vários produtos.

## Tecnologias
- PHP
- HTML5
- CSS
- JavaScript
- Bootstrap 5
- MySQL/MariaDB pelo XAMPP

## Como executar
1. Inicie Apache e MySQL no XAMPP.
2. Copie a pasta `LogiControl` para `C:\xampp\htdocs`.
3. Abra `http://localhost/phpmyadmin`.
4. Importe o arquivo `banco.sql` na aba **Importar**.
5. Acesse `http://localhost/LogiControl`.

## Ordem sugerida para testar
1. Cadastre dois endereços (um de origem e um de destino).
2. Cadastre um transportador.
3. Cadastre dois ou mais produtos.
4. Crie um frete selecionando o transportador, origem, destino e vários produtos.
5. Abra Relatórios, escolha a data do frete e baixe o CSV.

## Estrutura
- `config/conexao.php`: conexão com o banco.
- `includes/`: cabeçalho, menu e rodapé.
- `css/` e `js/`: arquivos de apresentação e interação.
- páginas PHP: cadastros, fretes e relatórios.
- `banco.sql`: criação das tabelas.

