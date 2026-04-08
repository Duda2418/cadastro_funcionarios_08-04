<?php
session_start();
include("database/conexao.php");

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE usuario = $1";
    $result = pg_query_params($conn, $sql, array($usuario));

    if ($result && pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);

        // Verifica senha com criptografia do PostgreSQL
        $sqlSenha = "SELECT senha = crypt($1, senha) AS valido FROM usuarios WHERE usuario = $2";
        $resSenha = pg_query_params($conn, $sqlSenha, array($senha, $usuario));
        $valido = pg_fetch_assoc($resSenha);

        if ($valido['valido'] === 't') {
            $_SESSION['usuario'] = $usuario;
            header("Location: home.php");
            exit;
        } else {
            $msg = "Senha incorreta!";
        }
    } else {
        $msg = "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        body {
            font-family: Arial;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #2d6cdf;
            color: white;
            border: none;
        }
        .erro {
            color: red;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Login</h2>

    <?php if ($msg) echo "<p class='erro'>$msg</p>"; ?>

    <form method="POST">
        <input type="text" name="usuario" placeholder="Usuário" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>