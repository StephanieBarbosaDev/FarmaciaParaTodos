<?php
// Conexão com o banco de dados
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'civil_formulario';

$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Verifica se houve erro na conexão
if ($conexao->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
}

// Alias de compatibilidade (garante que ambos os nomes funcionem)
$conn = $conexao;

// Teste opcional (remova ou comente para produção)
// echo "Conexão com o banco de dados estabelecida com sucesso!";
?>