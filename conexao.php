<?php
    $host = "localhost";
    $user = "root";
    $senha = "";
    $bd = "restaurante";
    $conexao = mysqli_connect($host, $user, $senha, $bd);
    if (mysqli_connect_errno()){
        echo "Não é possível conectar ao banco de dados";
        exit();
    }
    mysqli_select_db($conexao, $bd) or die("Não foi possível encontrar o banco de dados");
    mysqli_set_charset($conexao, "utf8");
?>