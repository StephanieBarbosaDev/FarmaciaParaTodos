<?php
session_start();
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $observacoes = $_POST['observacoes'] ?? '';
    $quantidade = $_POST['quantidade'] ?? '';
    $status = $_POST['status'] ?? '';
    // Removido: $empresa_id = $_POST['empresa_id'] ?? '';

    $foto_path = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file_name = basename($_FILES['foto']['name']);
        $file_path = $upload_dir . uniqid() . '_' . $file_name;
        $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        
        if (in_array($file_type, ['png', 'jpg', 'jpeg'])) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $file_path)) {
                $foto_path = $file_path;
            }
        }
    }

    // Removido: empty($empresa_id) da validação
    if (empty($nome) || empty($quantidade)) {
        echo "<p style='color:red;'>Campos obrigatórios não preenchidos.</p>";
    } elseif (!$conexao) {
        die("Erro de conexão com o banco: " . mysqli_connect_error());
    } else {
        // Removido: empresa_id da query e bind_param
        $stmt = mysqli_prepare($conexao, "INSERT INTO remedios (nome, observacoes, quantidade, status, foto) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssiss', $nome, $observacoes, $quantidade, $status, $foto_path);
        
        if (mysqli_stmt_execute($stmt)) {
            header('Location: homeEmpresa.php');
            exit;
        } else {
            echo "<p style='color:red;'>Erro ao inserir: " . mysqli_error($conexao) . "</p>";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head> 
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Cadastrar / Editar Remédio</title>
  <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="homeEmpresa.css">
  <link rel="stylesheet" href="cadastroRemedio.css">
</head>
<body>
  <header class="header">
    <img src="favicon.png" alt="logo" class="logo">
    <div class="header-right">
      <button class="btn-nav" id="btn-back-cadastrar">Voltar</button>
    </div>
  </header>

  <main class="container">
    <div class="form-card">
      <h2 id="form-title">Cadastrar Remédio</h2>

      <form action="cadastroRemedio.php" method="POST" enctype="multipart/form-data" id="form-remedio">
        <!-- Removido: Campo oculto para empresa_id -->

        <div class="row">
          <div class="col">
            <label class="label">Escolher ficheiro (opcional)</label>
            <input id="foto" name="foto" type="file" accept="image/*">
            <div class="small-muted">Apenas PNG/JPG. Será comprimida para armazenar localmente.</div>
          </div>

          <div class="col status-col">
            <label class="label">Status</label>
            <select id="status" name="status" class="input">
              <option value="Disponível">Disponível</option>
              <option value="Indisponível">Indisponível</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <label class="label" for="nome">Nome do Medicamento</label>
            <input id="nome" name="nome" class="input" type="text" placeholder="Ex.: Dipirona 500mg" required>
          </div>
          <div class="col small-col">
            <label class="label" for="quantidade">Quantidade</label>
            <input id="quantidade" name="quantidade" class="input" type="number" min="0" value="0" required>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <label class="label" for="observacoes">Observações</label>
            <input id="observacoes" name="observacoes" class="input" type="text" placeholder="Ex.: Cartela com 5 comprimidos, vencer 06/2025">
          </div>
        </div>

        <div class="actions">
          <button id="btn-cancel" class="btn-nav" type="button">Cancelar</button>
          <input name="submit" id="btn-save" class="btn-nav secondary" type="submit" value="Salvar">
        </div>
      </form>
    </div>
  </main>

  <footer class="rodape">
    <p>&copy; 2025 <br> Farmácia para Todos.<br>Todos os direitos reservados.</p>
  </footer>

  <script src="cadastroRemedio.js"></script>
  <script>
    document.getElementById('btn-back-cadastrar').addEventListener('click', function() {
      window.location.href = 'homeEmpresa.php';
    });
  </script>
</body>
</html>