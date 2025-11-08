<?php
session_start();
// print_r($_SESSION);
if ((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)) {
    // Se não estiver logado, redireciona
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: loginCivil.php');
    exit;
}
$logado = $_SESSION['email'];

// Inclua a configuração do banco
include 'config.php';

// Busque apenas remédios disponíveis
$query = "SELECT id, nome, quantidade, status, foto FROM remedios WHERE status = 'Disponível' ORDER BY nome ASC";
$stmt = mysqli_prepare($conexao, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$remedios = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmácia para Todos | Home Civil</title>
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="homeCivil.css">
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
            <div class="search-box">
                <img src="lupa.png" alt="Buscar" class="icon search-icon"/>
                <input type="text" class="search-input" id="search-input" placeholder="Buscar por remédio..." />
            </div>
            <a class="link-plain" href="status.php">STATUS</a>
            <img src="delayIcon.png" alt="delayIcon" class="delay-icon">
        </div>
    </header>

    <main class="container" style="padding-top:150px;padding-bottom:60px">
        <h2 style="color:var(--brand);margin-bottom:6px">Medicamentos Disponíveis</h2>
        <p class="hint" style="margin-bottom:18px">Aqui aparecem os medicamentos disponíveis. Clique em "Solicitar" para pedir um.</p>

        <section class="cards-area" id="cards-area">
            <?php if (empty($remedios)): ?>
                <p class="text-muted">Nenhum remédio disponível no momento.</p>
            <?php else: ?>
                <div class="row" id="remedios-row">
                    <?php foreach ($remedios as $remedio): ?>
                        <div class="col-md-4 mb-4 remedio-card" data-nome="<?php echo strtolower(htmlspecialchars($remedio['nome'])); ?>">
                            <div class="card h-100">
                                <?php if (!empty($remedio['foto'])): ?>
                                    <img src="<?php echo htmlspecialchars($remedio['foto']); ?>" class="card-img-top" alt="Foto do remédio" style="height: 200px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($remedio['nome']); ?></h5>
                                    <p class="card-text">
                                        <strong>Quantidade:</strong> <?php echo htmlspecialchars($remedio['quantidade']); ?><br>
                                        <strong>Status:</strong> 
                                        <span class="badge bg-success"><?php echo htmlspecialchars($remedio['status']); ?></span>
                                    </p>
                                    <div class="mt-auto">
                                        <form action="solicitarRemedio.php" method="POST" class="d-inline" onsubmit="return confirm('Confirmar solicitação?');">
                                            <input type="hidden" name="remedio_id" value="<?php echo $remedio['id']; ?>">
                                            <button type="submit" class="btn btn-success btn-sm">Solicitar</button>
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

    <script src="homeCivil.js"></script>
    <script>
        // JS apenas para a busca (filtragem no cliente)
        document.getElementById('search-input').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const cards = document.querySelectorAll('.remedio-card');
            cards.forEach(card => {
                const nome = card.getAttribute('data-nome');
                card.style.display = nome.includes(query) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
