<?php
include("../conexion.php");
if ($_POST['action'] == 'sales') {
    $desde = date('Y') . '-01-01 00:00:00';
    $ate = date('Y') . '-12-31 23:59:59';
    $query = mysqli_query($conexao, "SELECT SUM(IF(MONTH(data_pedido) = 1, total, 0)) AS ene,
    SUM(IF(MONTH(data_pedido) = 2, total, 0)) AS feb,
    SUM(IF(MONTH(data_pedido) = 3, total, 0)) AS mar,
    SUM(IF(MONTH(data_pedido) = 4, total, 0)) AS abr,
    SUM(IF(MONTH(data_pedido) = 5, total, 0)) AS may,
    SUM(IF(MONTH(data_pedido) = 6, total, 0)) AS jun,
    SUM(IF(MONTH(data_pedido) = 7, total, 0)) AS jul,
    SUM(IF(MONTH(data_pedido) = 8, total, 0)) AS ago,
    SUM(IF(MONTH(data_pedido) = 9, total, 0)) AS sep,
    SUM(IF(MONTH(data_pedido) = 10, total, 0)) AS oct,
    SUM(IF(MONTH(data_pedido) = 11, total, 0)) AS nov,
    SUM(IF(MONTH(data_pedido) = 12, total, 0)) AS dic 
    FROM pedidos WHERE data_pedido BETWEEN '$desde' AND '$ate'");
    $ajuste = mysqli_fetch_assoc($query);
    echo json_encode($ajuste);
    die();
}
?>