
<?php
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!$conexao) {
        die("Erro de conexão com o banco: " . mysqli_connect_error());
    }

    $query = "INSERT INTO usuarios (nome, cpf, telefone, email, senha)
              VALUES ('$nome', '$cpf', '$telefone', '$email', '$senha')";

    if (mysqli_query($conexao, $query)) {
        // Redireciona de volta para o formulário com mensagem opcional
       header('Location: loginCivil.php');
        exit;
    } else {
        echo "<p style='color:red;'>Erro ao inserir: " . mysqli_error($conexao) . "</p>";
    }
  }

    
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="cadastroCivil.css" />
    <title>Farmácia para Todos | Cadastro Civil</title>
  <link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
  </head>
  <body>
    <div class="login-page">
      <div class="login-card">
        <img
          class="img-logo"
          src="favicon.png"
          alt="Logotipo da Farmácia para Todos"
        />

        <h2 class="login-title">CADASTRO</h2>

        <form action="cadastroCivil.php" method="POST" id="form-cadastro" novalidate>
          <label for="nome">NOME</label>
          <input type="text" id="nome" name="nome" />

          <label for="cpf">CPF</label>
          <input
            type="text"
            id="cpf"
            name="cpf"
            placeholder="000.000.000/00"
            required
            minlength="15"
            maxlength="15"
          />

          <label for="telefone">Telefone</label>
          <input
            type="tel"
            id="telefone"
            name="telefone"
            placeholder="(00) 00000-0000"
            required
            minlength="14"
            maxlength="15"
          />

          <label for="email">E-mail </label>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="email@gmail.com"
            required
          />

          <label for="senha">Senha</label>
          <input
            type="password"
            id="senha"
            name="senha"
            placeholder="Senha (mín. 6 caracteres)"
            required
            minlength="6"
          />

          <label for="confirma-senha">Confirmação de Senha</label>
          <input
            type="password"
            id="confirma-senha"
            name="confirma-senha"
            placeholder="Repita a senha"
            required
            minlength="6"
          />

          <input type="submit" name="submit" class="btn-login">
Cadastrar</input>

          <p id="mensagem-erro" style="color: red; display: none"></p>
          <p id="mensagem-sucesso" style="color: green; display: none"></p>
        </form>

        <div class="extras link-gray">
          <p>
            Já possui conta?
            <a href="loginCivil.php" class="link link-purple"
              >Acesse o login</a
            >
          </p>
        </div>
      </div>
    </div>
    <footer class="rodape">
      <p>
        &copy; 2025 <br />
        Farmácia para Todos.<br />Todos os direitos reservados.
      </p>
    </footer>


  </body>
</html>