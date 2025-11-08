<?php
session_start();
include_once('config.php');

// Verifique se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: loginUsuario.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remedio_id'])) {
    $remedio_id = intval($_POST['remedio_id']);

    // Buscar o usuario_id pelo email da sessão
    $usuario_email = $_SESSION['email'];
    $stmt = mysqli_prepare($conexao, "SELECT id FROM usuarios WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $usuario_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$usuario) {
        echo "<p style='color:red;'>Usuário não encontrado.</p>";
        exit;
    }
    $usuario_id = $usuario['id'];

    // Verifique se o remédio existe e está disponível
    $stmt = mysqli_prepare($conexao, "SELECT id FROM remedios WHERE id = ? AND status = 'Disponível'");
    mysqli_stmt_bind_param($stmt, 'i', $remedio_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $remedio = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($remedio) {
        // Verifique se já existe uma solicitação pendente para este remédio E usuário
        $stmt = mysqli_prepare($conexao, "SELECT id FROM solicitacoes WHERE remedio_id = ? AND usuario_id = ? AND status = 'Pendente'");
        mysqli_stmt_bind_param($stmt, 'ii', $remedio_id, $usuario_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $solicitacao_existente = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$solicitacao_existente) {
            // Insira a solicitação com usuario_id
            $stmt = mysqli_prepare($conexao, "INSERT INTO solicitacoes (remedio_id, usuario_id, status) VALUES (?, ?, 'Pendente')");
            mysqli_stmt_bind_param($stmt, 'ii', $remedio_id, $usuario_id);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header('Location: homeCivil.php?msg=Solicitação enviada com sucesso');
                exit;
            } else {
                echo "<p style='color:red;'>Erro ao enviar solicitação: " . mysqli_error($conexao) . "</p>";
            }
        } else {
            header('Location: homeCivil.php?msg=Este remédio já foi solicitado e está pendente');
            exit;
        }
    } else {
        echo "<p style='color:red;'>Erro: Remédio não encontrado ou indisponível.</p>";
    }
} else {
    header('Location: homeCivil.php');
    exit;
}
?>