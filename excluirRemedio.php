<?php
session_start();
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    // Removido: $empresa_id = $_SESSION['empresa_id'];

    // Removido: Verificação se o remédio pertence à empresa logada
    $stmt = mysqli_prepare($conexao, "SELECT foto FROM remedios WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $remedio = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($remedio) {
        // Exclua o remédio
        $stmt = mysqli_prepare($conexao, "DELETE FROM remedios WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Opcional: Exclua a foto do servidor se existir
        if (!empty($remedio['foto']) && file_exists($remedio['foto'])) {
            unlink($remedio['foto']);
        }

        // Redirecione de volta
        header('Location: homeEmpresa.php?msg=Remédio excluído com sucesso');
        exit;
    } else {
        echo "<p style='color:red;'>Erro: Remédio não encontrado.</p>";
    }
} else {
    header('Location: homeEmpresa.php');
    exit;
}
?>