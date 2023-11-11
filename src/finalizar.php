<?php
    session_start();
    if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 3) {
    $data_pedido = date('Y-m-d');
    $id_sala = $_GET['id_sala'];
    $mesas = $_GET['mesas'];
    include_once "includes/header.php";
    ?>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit"></i>
                Pratos
            </h3>
        </div>
        <div class="card-body">
            <input type="hidden" id="id_sala" value="<?php echo $_GET['id_sala']; ?>">
            <input type="hidden" id="mesas" value="<?php echo $_GET['mesas']; ?>">
            <div class="row">
                <?php
                include "../conexao.php";
                $query = mysqli_query($conexao, "SELECT * FROM pedidos WHERE id_sala = $id_sala AND num_mesa = $mesas AND estado = 'PENDENTE'");
                $result = mysqli_fetch_assoc($query);
                if (!empty($result)) { ?>
                    <div class="col-md-12 text-center">
                        <div class="col-12">
                            Data: <?php echo $result['data']; ?>
                            <hr>
                            Mesa: <?php echo $_GET['mesas']; ?>
                        </div>

                        <div class="bg-gray py-2 px-3 mt-4">
                            <h2 class="mb-0">
                                $<?php echo $result['total']; ?>
                            </h2>
                        </div>
                        <hr>
                        <h3>Pratos</h3>
                        <div class="row">
                        <?php $id_pedido = $result['id'];
                        $query1 = mysqli_query($conexao, "SELECT * FROM detalhe_pedidos WHERE id_pedido = $id_pedido");
                        while ($data1 = mysqli_fetch_assoc($query1)) { ?>
                            <div class="col-md-4 card card-widget widget-user">
                                <div class="widget-user-header bg-warning">
                                    <h3 class="widget-user-username">Preço</h3>
                                    <h5 class="widget-user-desc"><?php echo $data1['preco']; ?></h5>
                                </div>
                                <div class="widget-user-image">
                                    <img class="img-circle elevation-2" src="../assets/img/mesa.jpg" alt="User Avatar">
                                </div>
                                <div class="card-footer">
                                    <div class="description-block">
                                        <span><?php echo $data1['nome']; ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                        <div class="mt-4">
                            <a class="btn btn-primary btn-block btn-flat finalizarPedido" href="#">
                                <i class="fas fa-cart-plus mr-2"></i>
                                Finalizar
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php include_once "includes/footer.php";
    } else {
        header('Location: permissao.php');
    } ?>