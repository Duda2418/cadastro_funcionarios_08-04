<?php
session_start();
include("database/conexao.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$msg = "";

if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cargo = $_POST['cargo'];

    $sql = "INSERT INTO funcionarios (nome, email, cargo) VALUES ($1, $2, $3)";
    $res = pg_query_params($conn, $sql, array($nome, $email, $cargo));

    $msg = $res ? "Cadastrado com sucesso!" : "Erro!";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Cadastro</title>
<link rel="stylesheet" href="./style.css">
</head>

<body>

<div class="navbar">
    <a href="home.php">Home</a>
    <a href="cadastro.php">Cadastro</a>
    <a href="lista.php">Lista</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <div class="card">
        <h2>Cadastro de Funcionário</h2>

        <?php if ($msg) echo "<p class='msg'>$msg</p>"; ?>

        <form method="POST">
            <input name="nome" placeholder="Nome">
            <input name="email" placeholder="Email">
            <input name="cargo" placeholder="Cargo">
            <button name="cadastrar">Cadastrar</button>
        </form>
    </div>
</div>

</body>
</html>