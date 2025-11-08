<?php
session_start();
include_once('config.php');

if (!isset($_SESSION['email'])) {
    header('Location: loginUsuario.php');
    exit;
}


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

// Busque todas as solicitações do usuário logado
$query = "SELECT s.id, r.nome AS remedio_nome, s.status 
      FROM solicitacoes s 
      JOIN remedios r ON s.remedio_id = r.id 
      WHERE s.usuario_id = ?
      ORDER BY s.id DESC";
$stmt = mysqli_prepare($conexao, $query);
mysqli_stmt_bind_param($stmt, 'i', $usuario_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$solicitacoes = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Farmácia para Todos | Status</title>
  <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="homeCivil.css">
  <link rel="stylesheet" href="solicitacoes.css">
</head>
<body>
  <header class="header">
    <img src="favicon.png" alt="logo" class="logo">
    <div class="header-right">
      <button class="btn-nav" id="btn-back-solicitacoes">Voltar</button>
    </div>
  </header>

  <main style="padding-top:150px;padding-left:20px;padding-right:20px;">
    <div class="container">
      <h2 class="center" style="color:var(--brand);margin-bottom:12px">Minhas Solicitações</h2>
      <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-info" style="margin-bottom:16px;"> <?php echo htmlspecialchars($_GET['msg']); ?> </div>
      <?php endif; ?>
      <section id="list" class="list">
        <?php if (empty($solicitacoes)): ?>
          <p class="text-muted">Nenhuma solicitação encontrada.</p>
        <?php else: ?>
          <ul class="list-group">
            <?php foreach ($solicitacoes as $solicitacao): ?>
              <li class="list-group-item">
                <strong>Remédio:</strong> <?php echo htmlspecialchars($solicitacao['remedio_nome']); ?><br>
                <strong>Status:</strong> 
                <span class="badge 
                  <?php 
                    if ($solicitacao['status'] === 'Aprovada') echo 'bg-success'; 
                    elseif ($solicitacao['status'] === 'Rejeitada') echo 'bg-danger'; 
                    else echo 'bg-warning'; 
                  ?>">
                  <?php echo htmlspecialchars($solicitacao['status']); ?>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </section>
    </div>
  </main>

  <footer class="rodape">
    <p>&copy; 2025 <br> Farmácia para Todos.<br>Todos os direitos reservados.</p>
  </footer>

  <script>
    // Script para o botão voltar
    document.addEventListener('DOMContentLoaded', () => {
      const b = document.getElementById('btn-back-solicitacoes');
      if (b) b.addEventListener('click', () => location.href = 'homeCivil.php');
    });
  </script>
</body>
</html>