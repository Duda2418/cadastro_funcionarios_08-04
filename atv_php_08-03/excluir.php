<?php
include("database/conexao.php");

$id = $_GET['id'];
pg_query($conn, "DELETE FROM funcionarios WHERE id=$id");

header("Location: listar.php");
?>