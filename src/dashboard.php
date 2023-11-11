<?php
session_start();
include_once "includes/header.php";
include "../conexao.php";
$query1 = mysqli_query($conexao, "SELECT COUNT(id) AS total FROM salas WHERE estado = 1");
$totalSalas = mysqli_fetch_assoc($query1);
$query2 = mysqli_query($conexao, "SELECT COUNT(id) AS total FROM pratos WHERE estado = 1");
$totalPratos = mysqli_fetch_assoc($query2);
$query3 = mysqli_query($conexao, "SELECT COUNT(id) AS total FROM usuarios WHERE estado = 1");
$totalUsuarios = mysqli_fetch_assoc($query3);
$query4 = mysqli_query($conexao, "SELECT COUNT(id) AS total FROM pedidos WHERE estado = 1");
$totalPedidos = mysqli_fetch_assoc($query4);
$query5 = mysqli_query($conexao, "SELECT SUM(total) AS total FROM pedidos");
$totalVendas = mysqli_fetch_assoc($query5);
?>
<div class="card">
    <div class="card-header text-center">
        Dashboard
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo $totalPratos['total']; ?></h3>

                        <p>Produtos</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="pratos.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo $totalSalas['total']; ?></h3>

                        <p>Salas</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="salas.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo $totalUsuarios['total']; ?></h3>

                        <p>Usuarios</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="usuarios.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?php echo $totalPedidos['total']; ?></h3>

                        <p>Pedidos</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="lista_vendas.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Vendas</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                <span class="text-bold text-lg">$<?php echo $totalVendas['total']; ?></span>
                                <span>Total</span>
                            </p>
                        </div>

                        <div class="position-relative mb-4">
                            <canvas id="sales-chart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>

<script src="../assets/js/dashboard.js"></script>