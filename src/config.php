 <?php
 session_start();
if ($_SESSION['rol'] != 1) {
    header('Location: permissao.php');
    exit;
}
require_once "../conexao.php";
$query = mysqli_query($conexao, "SELECT * FROM config");
$data = mysqli_fetch_assoc($query);
if ($_POST) {
    $alert = '';
    if (empty($_POST['nome']) || empty($_POST['telefone']) || empty($_POST['email']) || empty($_POST['cidade'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todos os campos são obrigatórios!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $cidade = $_POST['cidade'];
        $id = $_POST['id'];
        $update = mysqli_query($conexao, "UPDATE config SET nome = '$nome', telefone = '$telefone', email = '$email', cidade = '$cidade' WHERE id = $id");
        if ($update) {
            $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Dados Atualizados
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        }
    }
}
include_once "includes/header.php";
?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Dados da Empresa</h4>
            </div>
            <div class="card-body">
                <?php echo isset($alert) ? $alert : ''; ?>
                <form action="" method="post" class="p-3">
                    <div class="form-group">
                        <label>Nome da Empresa:</label>
                        <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
                        <input type="text" name="nome" class="form-control" value="<?php echo $data['nome']; ?>" id="txtNombre" placeholder="Nome da Empresa" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Telefone:</label>
                        <input type="number" name="telefone" class="form-control" value="<?php echo $data['telefone']; ?>" id="txtTelEmpresa" placeholder="Telefone da Empresa" required>
                    </div>
                    <div class="form-group">
                        <label>E-mail:</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>" id="txtEmailEmpresa" placeholder="E-mail da Empresa" required>
                    </div>
                    <div class="form-group">
                        <label>Cidade:</label>
                        <input type="text" name="cidade" class="form-control" value="<?php echo $data['cidade']; ?>" id="txtDirEmpresa" placeholder="Cidade da Empresa" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Salvar Alterações</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
