<?php
session_start();
include_once('config.php');

// Garante compatibilidade com nome da variável do banco
if (!isset($conexao) && isset($conn)) {
  $conexao = $conn;
}

// Busque solicitações pendentes com detalhes do usuário e remédio

$query = "SELECT s.id, r.nome AS remedio_nome, u.nome AS usuario_nome, u.email AS usuario_email, s.status 
          FROM solicitacoes s 
          JOIN remedios r ON s.remedio_id = r.id 
          JOIN usuarios u ON s.usuario_id = u.id 
          ORDER BY s.id DESC";

$stmt = mysqli_prepare($conexao, $query) or die("Erro na consulta: " . mysqli_error($conexao));
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
  <title>Farmácia para Todos | Solicitações Pendentes</title>
  <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="homeEmpresa.css">  <!-- Ajuste o CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
  <header class="header">
    <img src="favicon.png" alt="logo" class="logo">
    <div class="header-right">
      <button class="btn-nav" id="btn-back">Voltar</button>
    </div>
  </header>

  <main style="padding-top:150px;padding-left:20px;padding-right:20px;">
    <div class="container">
      <h2 class="center" style="color:var(--brand);margin-bottom:12px">Solicitações Pendentes</h2>
      <section id="list" class="list">
        <?php if (empty($solicitacoes)): ?>
          <p class="text-muted">Nenhuma solicitação pendente.</p>
        <?php else: ?>
          <ul class="list-group">
            <?php foreach ($solicitacoes as $solicitacao): ?>
              <li class="list-group-item">
                <strong>Remédio:</strong> <?php echo htmlspecialchars($solicitacao['remedio_nome']); ?><br>
                <strong>Usuário:</strong> <?php echo htmlspecialchars($solicitacao['usuario_nome']); ?> (<?php echo htmlspecialchars($solicitacao['usuario_email']); ?>)<br>
                <strong>Status:</strong> 
                <span class="badge 
                  <?php 
                    if ($solicitacao['status'] === 'Aprovada') echo 'bg-success'; 
                    elseif ($solicitacao['status'] === 'Rejeitada') echo 'bg-danger'; 
                    else echo 'bg-warning'; 
                  ?>">
                  <?php echo htmlspecialchars($solicitacao['status']); ?>
                </span><br>
                <form method="POST" action="atualizarSolicitacao.php" style="display:inline;">
                  <input type="hidden" name="solicitacao_id" value="<?php echo $solicitacao['id']; ?>">
                  <button type="submit" name="acao" value="aprovar" class="btn btn-success">Aprovar</button>
                  <button type="submit" name="acao" value="rejeitar" class="btn btn-danger">Rejeitar</button>
                </form>
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
    document.addEventListener('DOMContentLoaded', () => {
      const b = document.getElementById('btn-back');
      if (b) b.addEventListener('click', () => location.href = 'homeEmpresa.php');  // Ajuste para a home da empresa
    });
  </script>
</body>
</html>