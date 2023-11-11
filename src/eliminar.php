<?php
session_start();
if ($_SESSION['rol'] != 1) {
    header('Location: permissao.php');
    exit;
}
require("../conexao.php");
if (empty($_SESSION['idUser'])) {
    header('Location: ../');
}
if (!empty($_GET['id']) && !empty($_GET['accion'])) {
    $id = $_GET['id'];
    $table = $_GET['accion'];
    $id_user = $_SESSION['idUser'];
    $query_delete = mysqli_query($conexao, "UPDATE $table SET estado = 0 WHERE id = $id");
    mysqli_close($conexao);
    header("Location: " . $table . '.php');
}
