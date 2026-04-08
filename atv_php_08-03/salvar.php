<?php
include("database/conexao.php");

$nome = $_POST['nome'];
$cargo = $_POST['cargo'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$situacao = $_POST['situacao'];

$sql = "INSERT INTO funcionarios (nome, cargo, email, telefone, situacao)
VALUES ('$nome','$cargo','$email','$telefone','$situacao')";

pg_query($conn, $sql);

header("Location: listar.php");
?>