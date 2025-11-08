<!DOCTYPE html> 
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="loginCivil.css" />
  <title>Farmácia para Todos | Login</title>
  <link rel="icon" type="image/png" href="favicon.png" />
</head>
<body>
  <div class="login-page">
    <div class="login-card">
      <img class="img" src="favicon.png" alt="Logotipo da Farmácia para Todos" />
      <h2 class="login-title">LOGIN</h2>

      <form action="loginLogica.php" method="POST" id="form-login">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" placeholder="Seu email" required />

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" placeholder="Sua senha" required minlength="4" />

        <div class="checkbox-container">
          <input type="checkbox" id="manter-logado" name="manter_logado" />
          <label for="manter-logado">Manter-me logado</label>
        </div>

        <input type="submit" name="submit" value="Entrar" class="btn-login" />
        <p id="erro" style="color: red; display: none"></p>
      </form>

      <div class="extras link-gray">
        <p>
          Não possui conta ainda?
          <a href="cadastroCivil.php" class="link link-purple">Crie uma nova conta</a>
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
