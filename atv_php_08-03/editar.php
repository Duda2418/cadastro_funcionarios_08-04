<?php
session_start();
include("database/conexao.php");

// Verifica se usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

// ========================
// BUSCAR FUNCIONÁRIO PELO ID
// ========================
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: lista.php");
    exit;
}

// Busca os dados do funcionário
$sql = "SELECT * FROM funcionarios WHERE id=$1";
$res = pg_query_params($conn, $sql, array($id));

if (pg_num_rows($res) == 0) {
    $msg = "Funcionário não encontrado!";
    $funcionario = null;
} else {
    $funcionario = pg_fetch_assoc($res);
}

// ========================
// ATUALIZAR FUNCIONÁRIO
// ========================
if (isset($_POST['salvar'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $cargo = trim($_POST['cargo']);

    if ($nome != "" && $email != "" && $cargo != "") {
        $sql_update = "UPDATE funcionarios SET nome=$1, email=$2, cargo=$3 WHERE id=$4";
        $res_update = pg_query_params($conn, $sql_update, array($nome, $email, $cargo, $id));

        $msg = $res_update ? "Funcionário atualizado com sucesso!" : "Erro ao atualizar funcionário!";
        // Atualiza os dados para mostrar no formulário
        if ($res_update) {
            $funcionario['nome'] = $nome;
            $funcionario['email'] = $email;
            $funcionario['cargo'] = $cargo;
        }
    } else {
        $msg = "Preencha todos os campos!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Editar Funcionário</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="home.php">Home</a>
    <a href="cadastro.php">Cadastro</a>
    <a href="lista.php">Lista</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h2>Editar Funcionário</h2>

    <?php if (!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>

    <?php if ($funcionario): ?>
        <form method="POST" action="editar.php?id=<?php echo $funcionario['id']; ?>">
            <label for="nome">Nome:</label><br>
            <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($funcionario['nome']); ?>"><br><br>

            <label for="email">Email:</label><br>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($funcionario['email']); ?>"><br><br>

            <label for="cargo">Cargo:</label><br>
            <input type="text" name="cargo" id="cargo" value="<?php echo htmlspecialchars($funcionario['cargo']); ?>"><br><br>

            <button type="submit" name="salvar">Salvar</button>
            <a href="lista.php"><button type="button">Cancelar</button></a>
        </form>
    <?php else: ?>
        <p>Funcionário não encontrado.</p>
        <a href="lista.php">Voltar à lista</a>
    <?php endif; ?>

</div>

</body>
</html>