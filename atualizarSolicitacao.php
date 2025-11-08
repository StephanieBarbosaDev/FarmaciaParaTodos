<?php
session_start();
include_once('config.php');

if (!isset($_SESSION['email'])) {
    header('Location: loginEmpresa.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitacao_id']) && isset($_POST['acao'])) {
    $solicitacao_id = intval($_POST['solicitacao_id']);
    $acao = $_POST['acao'];
    $novo_status = ($acao === 'aprovar') ? 'Aprovada' : 'Rejeitada';

    // Atualiza o status
    $stmt = mysqli_prepare($conexao, "UPDATE solicitacoes SET status = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $novo_status, $solicitacao_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        // Redireciona para solicitacoes.php (mantém a empresa na mesma página)
        header('Location: solicitacoes.php?msg=Solicitação ' . strtolower($novo_status) . ' com sucesso');
        exit;
    } else {
        echo "<p style='color:red;'>Erro ao atualizar: " . mysqli_error($conexao) . "</p>";
    }
} else {
    header('Location: solicitacoes.php');
    exit;
}
?>