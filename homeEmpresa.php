<?php
session_start();
// print_r($_SESSION);
if ((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)) {
    // Se não estiver logado, redireciona
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: loginEmpresa.php');
    exit;
}
// Removido: $logado = $_SESSION['empresa_id'];

// Inclua a configuração do banco
include_once('config.php');

// Busque todos os remédios (removido filtro por empresa_id)
$query = "SELECT id, nome, quantidade, status, foto FROM remedios ORDER BY nome ASC";
$stmt = mysqli_prepare($conexao, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$remedios = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Farmácia para Todos | Home Empresa</title>
  <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="homeEmpresa.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
  <header class="header">
    <img src="favicon.png" alt="logo" class="logo">
    <div class="header-right">
      <div class="link-plain">
        <a href="sair.php" class="btn btn-danger">Sair</a>
      </div>
      <a class="link-plain" href="cadastroRemedio.php">CADASTRAR REMÉDIO</a>
      <a class="link-plain small-muted" href="solicitacoes.php">SOLICITAÇÕES</a>
    </div>
  </header>

  <main class="container" style="padding-top:150px;padding-bottom:60px">
    <h2 style="color:var(--brand);margin-bottom:6px">Meus Medicamentos</h2>
    <p class="hint" style="margin-bottom:18px">Aqui aparecem os medicamentos cadastrados.</p>

    <section id="cards-area" class="cards-area">
      <?php if (empty($remedios)): ?>
        <p class="text-muted">Nenhum remédio cadastrado ainda. <a href="cadastroRemedio.php">Cadastrar um agora</a>.</p>
      <?php else: ?>
        <div class="row">
          <?php foreach ($remedios as $remedio): ?>
            <div class="col-md-4 mb-4">
              <div class="card h-100">
                <?php if (!empty($remedio['foto'])): ?>
                  <img src="<?php echo htmlspecialchars($remedio['foto']); ?>" class="card-img-top" alt="Foto do remédio" style="height: 200px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title"><?php echo htmlspecialchars($remedio['nome']); ?></h5>
                  <p class="card-text">
                    <strong>Quantidade:</strong> <?php echo htmlspecialchars($remedio['quantidade']); ?><br>
                    <strong>Status:</strong> 
                    <span class="badge <?php echo $remedio['status'] === 'Disponível' ? 'bg-danger' : 'bg-success'; ?>">
                      <?php echo htmlspecialchars($remedio['status']); ?>
                    </span>
                  </p>
                  <div class="mt-auto">
                    <a href="cadastroRemedio.php?id=<?php echo $remedio['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                    <form action="excluirRemedio.php" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este remédio?');">
                      <input type="hidden" name="id" value="<?php echo $remedio['id']; ?>">
                      <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <footer class="rodape">
    <p>&copy; 2025 <br> Farmácia para Todos.<br>Todos os direitos reservados.</p>
  </footer>


</body>
</html>