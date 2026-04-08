<?php
session_start();
include("database/conexao.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

// ========================
// EXCLUIR FUNCIONÁRIO
// ========================
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $sql = "DELETE FROM funcionarios WHERE id=$1";
    $res = pg_query_params($conn, $sql, array($id));
    $msg = $res ? "Funcionário excluído com sucesso!" : "Erro ao excluir funcionário!";
}

// ========================
// BUSCAR FUNCIONÁRIO
// ========================
$busca = $_GET['busca'] ?? "";

if ($busca != "") {
    $sql_lista = "SELECT * FROM funcionarios WHERE nome ILIKE $1 OR email ILIKE $1 OR cargo ILIKE $1 ORDER BY id DESC";
    $res_lista = pg_query_params($conn, $sql_lista, array("%$busca%"));
} else {
    $sql_lista = "SELECT * FROM funcionarios ORDER BY id DESC";
    $res_lista = pg_query($conn, $sql_lista);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Lista de Funcionários</title>
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
    <div class="table-container">

        <h2>Lista de Funcionários</h2>

        <?php if (!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>

        <!-- Busca -->
        <div class="search-box">
            <form method="GET" action="lista.php">
                <input type="text" name="busca" placeholder="Buscar por nome, email ou cargo" value="<?php echo htmlspecialchars($busca); ?>">
                <button>Buscar</button>
            </form>
        </div>

        <!-- Tabela -->
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Cargo</th>
                <th>Ações</th>
            </tr>

            <?php
            if (pg_num_rows($res_lista) > 0) {
                while ($row = pg_fetch_assoc($res_lista)) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>".htmlspecialchars($row['nome'])."</td>";
                    echo "<td>".htmlspecialchars($row['email'])."</td>";
                    echo "<td>".htmlspecialchars($row['cargo'])."</td>";
                    echo "<td>
                            <a href='editar.php?id={$row['id']}'><button type='button'>Editar</button></a>
                            <button type='button' onclick=\"if(confirm('Tem certeza que deseja excluir?')) location.href='lista.php?excluir={$row['id']}'\">Excluir</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>Nenhum funcionário encontrado</td></tr>";
            }
            ?>

        </table>
    </div>
</div>

</body>
</html>