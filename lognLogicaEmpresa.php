<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    include_once('config.php');
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM empresa WHERE email = '$email' AND senha = '$senha'";
    $result = $conexao->query($sql);

    if (mysqli_num_rows($result) < 1) {
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header("Location: loginEmpresa.php");
        exit;
    } else {
        $_SESSION['email'] = $email;
        $_SESSION['senha'] = $senha;
        header("Location: homeEmpresa.php");
        exit;
    }
} else {
    header("Location: loginEmpresa.php");
    exit;
}
?>
