<?php
session_start();
if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
    include "../conexao.php";
    if (!empty($_POST)) {
        $alert = "";
        $id = $_POST['id'];
        $prato = $_POST['prato'];
        $preco = $_POST['preco'];
        $foto_atual = $_POST['foto_atual'];
        $foto = $_FILES['foto'];
        $data_pedido = date('YmdHis');
        if (empty($prato) || empty($preco) || $precio < 0) {
            $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todos os campos são obrigatórios!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        } else {
            $nome = null;
            if (!empty($foto['name'])) {
                $nome = '../assets/img/pratos/' . $data_pedido . '.jpg';
            } else if (!empty($foto_atual) && empty($foto['name'])) {
                $nome = $foto_atual;
            }

            if (empty($id)) {
                $query = mysqli_query($conexao, "SELECT * FROM pratos WHERE nome = '$prato' AND estado = 1");
                $result = mysqli_fetch_array($query);
                if ($result > 0) {
                    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        O prato já existe!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $query_insert = mysqli_query($conexao, "INSERT INTO pratos (nome,preco,imagem) VALUES ('$prato', '$preco', '$nome')");
                    if ($query_insert) {
                        if (!empty($foto['name'])) {
                            move_uploaded_file($foto['tmp_name'], $nome);
                        }
                        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Prato cadastrado!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                    } else {
                        $alert = '<div class="alert alert-danger" role="alert">
                    Erro ao cadastrar prato!
                  </div>';
                    }
                }
            } else {
                $query_update = mysqli_query($conexao, "UPDATE pratos SET nome = '$prato', preco=$preco, imagem='$nome' WHERE id = $id");
                if ($query_update) {
                    if (!empty($foto['name'])) {
                        move_uploaded_file($foto['tmp_name'], $nome);
                    }
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Prato alterado!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Erro ao alterar prato!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                }
            }
        }
    }
    include_once "includes/header.php";
?>
    <div class="card shadow-lg">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" autocomplete="off" id="formulario" enctype="multipart/form-data">
                                <?php echo isset($alert) ? $alert : ''; ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="hidden" id="id" name="id">
                                            <input type="hidden" id="foto_atual" name="foto_atual">
                                            <label for="prato" class=" text-dark font-weight-bold">Prato</label>
                                            <input type="text" placeholder="Insira o nome do prato" name="prato" id="prato" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="preco" class=" text-dark font-weight-bold">Preço</label>
                                            <input type="text" placeholder="Insira o preço" class="form-control" name="preco" id="preco">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="preco" class=" text-dark font-weight-bold">Foto (512px - 512px)</label>
                                            <input type="file" class="form-control" name="foto" id="foto">
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="">Ações</label> <br>
                                        <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                                        <input type="button" value="Novo" onclick="limpiar()" class="btn btn-success" id="btnNuevo">
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tbl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Prato</th>
                                        <th>Preço</th>
                                        <th>Imagem</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include "../conexao.php";
                                    $query = mysqli_query($conexao, "SELECT * FROM pratos WHERE estado = 1");
                                    $result = mysqli_num_rows($query);
                                    if ($result > 0) {
                                        while ($data = mysqli_fetch_assoc($query)) { ?>
                                            <tr>
                                                <td><?php echo $data['id']; ?></td>
                                                <td><?php echo $data['nome']; ?></td>
                                                <td><?php echo $data['preco']; ?></td>
                                                <td><img class="img-thumbnail" src="<?php echo ($data['imagem'] == null) ? '../assets/img/default.png' : $data['imagem']; ?>" alt="" width="100"></td>
                                                <td>
                                                    <a href="#" onclick="editarPrato(<?php echo $data['id']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                                    <form action="eliminar.php?id=<?php echo $data['id']; ?>&accion=pratos" method="post" class="confirmar d-inline">
                                                        <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                                    </form>
                                                </td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include_once "includes/footer.php";
} else {
    header('Location: permissao.php');
}
?>