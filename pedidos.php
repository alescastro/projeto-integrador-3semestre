<?php
session_start();
if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 3) {
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
            <div class="row">
                <div class="col-7 col-sm-9">
                    <div class="tab-content" id="vert-tabs-right-tabContent">
                        <div class="tab-pane fade show active" id="vert-tabs-right-home" role="tabpanel" aria-labelledby="vert-tabs-right-home-tab">
                            <input type="hidden" id="id_sala" value="<?php echo $_GET['id_sala'] ?>">
                            <input type="hidden" id="mesas" value="<?php echo $_GET['mesas'] ?>">
                            <div class="row">
                                <?php
                                include "../conexao.php";
                                $query = mysqli_query($conexao, "SELECT * FROM pratos WHERE estado = 1");
                                $result = mysqli_num_rows($query);
                                if ($result > 0) {
                                    while ($data = mysqli_fetch_assoc($query)) { ?>
                                        <div class="col-md-3">
                                            <div class="col-12">
                                                <img src="<?php echo ($data['imagem'] == null) ? '../assets/img/default.png' : $data['imagem']; ?>" class="product-image" alt="Product Image">
                                            </div>
                                            <h6 class="my-3"><?php echo $data['nome']; ?></h6>

                                            <div class="bg-gray py-2 px-3 mt-4">
                                                <h2 class="mb-0">
                                                    $<?php echo $data['preco']; ?>
                                                </h2>
                                            </div>

                                            <div class="mt-4">
                                                <a class="btn btn-primary btn-block btn-flat addDetalhe" href="#" data-id="<?php echo $data['id']; ?>">
                                                    <i class="fas fa-cart-plus mr-2"></i>
                                                    Agregar
                                                </a>
                                            </div>
                                        </div>
                                <?php }
                                } ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pedido" role="tabpanel" aria-labelledby="pedido-tab">
                            <div class="row" id="detalhe_pedidos"></div>
                            <hr>
                            <div class="form-group">
                                <label for="observacao">Observações</label>
                                <textarea id="observacao" class="form-control" rows="3" placeholder="Observações"></textarea>
                            </div>
                            <button class="btn btn-primary" type="button" id="realizar_pedido">Realizar pedido</button>
                        </div>
                    </div>
                </div>
                <div class="col-5 col-sm-3">
                    <div class="nav flex-column nav-tabs nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="vert-tabs-right-home-tab" data-toggle="pill" href="#vert-tabs-right-home" role="tab" aria-controls="vert-tabs-right-home" aria-selected="true">Pratos</a>
                        <a class="nav-link" id="pedido-tab" data-toggle="pill" href="#pedido" role="tab" aria-controls="pedido" aria-selected="false">Pedido</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
<?php include_once "includes/footer.php";
} else {
    header('Location: permissao.php');
}
?>