<?php
// Inicia a sessão
session_start();

if ($_SESSION['rol'] != 1) {
    header('Location: permissao.php');
    exit;
}

include "../conexao.php";

if (!empty($_POST)) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $alert = "";

    // Verifica se algum dos campos obrigatórios está vazio
    if (empty($nome) || empty($email) || empty($rol)) {
        // Cria um alerta de aviso se algum campo obrigatório estiver vazio
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Todos os campos são obrigatórios!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
    } else {
        // Se o ID estiver vazio, é uma inserção; caso contrário, é uma atualização
        if (empty($id)) {
            // Para inserção, verifica se a senha está presente
            $senha = $_POST['senha'];
            if (empty($senha)) {
                // Cria um alerta de aviso se a senha estiver ausente
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    A senha é necessária!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            } else {
                // Verifica se o e-mail já existe no banco de dados
                $senha = ($_POST['senha']);
                $query = mysqli_query($conexao, "SELECT * FROM usuarios where email = '$email' AND estado = 1");
                $result = mysqli_fetch_array($query);
                if ($result > 0) {
                    // Cria um alerta de aviso se o e-mail já existe
                    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    O email não existe!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                } else {
                    // Insere o novo usuário no banco de dados
                    $query_insert = mysqli_query($conexao, "INSERT INTO usuarios (nome,email,rol,senha) values ('$nome', '$email', '$rol', '$senha')");
                    if ($query_insert) {
                        // Cria um alerta de sucesso se a inserção for bem-sucedida
                        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Usuario Cadastrado!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                    } else {
                        // Cria um alerta de erro se a inserção falhar
                        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Erro ao cadastrar!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                    }
                }
            }
        } else {
            // Se o ID estiver presente, é uma atualização
            // Atualiza os dados do usuário no banco de dados
            $sql_update = mysqli_query($conexao, "UPDATE usuarios SET nome = '$nome', email = '$email' , rol = '$rol' WHERE idusuario = $id_usuario");
            if ($sql_update) {
                // Cria um alerta de sucesso se a atualização for bem-sucedida
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Usuario Alterado!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            } else {
                // Cria um alerta de erro se a atualização falhar
                $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Erro ao alterar!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            }
        }
    }
}

// Inclui o cabeçalho da página
include "includes/header.php";
?>
<div class="card">
    <div class="card-body">
        <form action="" method="post" autocomplete="off" id="formulario">
            <?php echo isset($alert) ? $alert : ''; ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" placeholder="Insira o nome" name="nome" id="nome!">
                        <input type="hidden" id="id" name="id">
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" placeholder="Insira o email" name="email" id="email">
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="rol">Função</label>
                        <select id="rol" class="form-control" name="rol">
                            <option>Seleccionar</option>
                            <option value="1">Administrador</option>
                            <option value="2">Cozinheiro</option>
                            <option value="3">Outro</option>
                        </select>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" class="form-control" placeholder="Insira a senha" name="senha" id="senha!">
                    </div>
                </div>
            </div>
            <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
            <input type="button" value="Novo" class="btn btn-success" id="btnNuevo" onclick="limpiar()">
        </form>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover table-striped table-bordered mt-2" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Rol</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = mysqli_query($conexao, "SELECT * FROM usuarios WHERE estado = 1");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
                    if ($data['rol'] == 1) {
                        $rol = '<span class="badge badge-success">Administrador</span>';
                    }else if($data['rol'] == 2){
                        $rol = '<span class="badge badge-info">Cozinheiro</span>';
                    }else{
                        $rol = '<span class="badge badge-warning">Outro</span>';
                    }
                    ?>
                    <tr>
                        <td><?php echo $data['id']; ?></td>
                        <td><?php echo $data['nome']; ?></td>
                        <td><?php echo $data['email']; ?></td>
                        <td><?php echo $rol; ?></td>
                        <td>
                            <a href="#" onclick="editarUsuario(<?php echo $data['id']; ?>)" class="btn btn-success"><i class='fas fa-edit'></i></a>
                            <form action="eliminar.php?id=<?php echo $data['id']; ?>&accion=usuarios" method="post" class="confirmar d-inline">
                                <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                            </form>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>
<?php include_once "includes/footer.php"; ?>