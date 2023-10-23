<?php
session_start();
if ($_SESSION['rol'] != 1) {
    header('Location: permissao.php');
    exit;
}
include "../conexao.php";
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nome']) || empty($_POST['mesas'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todos os campos são obrigatórios!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $mesas = $_POST['mesas'];
        $result = 0;
        if (empty($id)) {
            $query = mysqli_query($conexao, "SELECT * FROM salas WHERE nome = '$nome' AND estado = 1");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        A sala já existe!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $query_insert = mysqli_query($conexao, "INSERT INTO salas (nome,mesas) values ('$nome', '$mesas')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Sala cadastrada!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Erro ao registrar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                }
            }
        } else {
            $sql_update = mysqli_query($conexao, "UPDATE salas SET nome = '$nome' , mesas = '$mesas' WHERE id = $id");
            if ($sql_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Sala Alterada
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Erro ao modificar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            }
        }
    }
    mysqli_close($conexao);
}
include_once "includes/header.php";
?>
<div class="card">
    <div class="card-body">
        <div class="card">
            <div class="card-body">
                <?php echo (isset($alert)) ? $alert : ''; ?>
                <form action="" method="post" autocomplete="off" id="formulario">
                <input type="hidden" name="id" id="id">    
                <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="nome" class="text-dark font-weight-bold">Nome</label>
                                <input type="text" placeholder="Insira o nome da sala" name="nome" id="nome" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="mesas" class="text-dark font-weight-bold">Mesas</label>
                                <input type="number" placeholder="Qtd. de Mesas" name="mesas" id="mesas" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-5 text-center">
                            <label for="">Ações</label> <br>
                            <input type="submit" value="Registrar" class="btn btn-success" id="btnAccion">
                            <input type="button" value="Limpar" class="btn btn-primary" id="btnNuevo" onclick="limpiar()">
                        </div>
                    </div>
                </form> 
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Mesas</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "../conexao.php";

                            $query = mysqli_query($conexao, "SELECT * FROM salas WHERE estado = 1");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td><?php echo $data['id']; ?></td>
                                        <td><?php echo $data['nome']; ?></td>
                                        <td><?php echo $data['mesas']; ?></td>
                                        <td>
                                            <a href="#" onclick="editarCliente(<?php echo $data['id']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                            <form action="eliminar.php?id=<?php echo $data['id']; ?>&accion=salas" method="post" class="confirmar d-inline">
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
<?php include_once "includes/footer.php"; ?>