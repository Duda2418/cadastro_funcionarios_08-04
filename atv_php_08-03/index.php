<?php
session_start();
include("database/conexao.php");

$msg = "";

if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    if ($usuario == "admin" && $senha == "123") {
        $_SESSION['usuario'] = $usuario;
        header("Location: home.php");
        exit;
    } else {
        $msg = "Login inválido!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="./style.css">
</head>

<body>

<div class="container">
    <div class="card">
        <h2>Login</h2>

        <?php if ($msg) echo "<p class='msg'>$msg</p>"; ?>

        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuário">
            <input type="password" name="senha" placeholder="Senha">
            <button name="login">Entrar</button>
        </form>
    </div>
</div>

</body>
</html>