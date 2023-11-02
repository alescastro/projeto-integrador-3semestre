<?php
require_once "../conexao.php";
session_start();
if (isset($_GET['detalhe'])) {
    $id = $_SESSION['idUser'];
    $dados = array();
    $detalhe = mysqli_query($conexao, "SELECT d.*, p.nome, p.preco, p.imagem FROM temp_pedidos d INNER JOIN pratos p ON d.id_produto = p.id WHERE d.id_usuario = $id");
    while ($row = mysqli_fetch_assoc($detalhe)) {
        $data['id'] = $row['id'];
        $data['nome'] = $row['nome'];
        $data['quantidade'] = $row['quantidade'];
        $data['preco'] = $row['preco'];
        $data['imagem'] = ($row['imagem'] == null) ? '../assets/img/default.png' : $row['imagem'];
        $data['total'] = $data['preco'] * $data['quantidade'];
        array_push($dados, $data);
    }
    echo json_encode($dados);
    die();
} else if (isset($_GET['delete_detalhe'])) {
    $id_detalhe = $_GET['id'];
    $query = mysqli_query($conexao, "DELETE FROM temp_pedidos WHERE id = $id_detalhe");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['detalhe_quantidade'])) {
    $id_detalhe = $_GET['id'];
    $quantidade = $_GET['quantidade'];
    $query = mysqli_query($conexao, "UPDATE temp_pedidos set quantidade = $quantidade WHERE id = $id_detalhe");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['processarPedido'])) {
    $id_sala = $_GET['id_sala'];
    $id_user = $_SESSION['idUser'];
    $mesas = $_GET['mesas'];
    $observacao = $_GET['observacao'];
    $consulta = mysqli_query($conexao, "SELECT d.*, p.nome FROM temp_pedidos d INNER JOIN pratos p ON d.id_produto = p.id WHERE d.id_usuario = $id_user");
    $total = 0;
    while ($row = mysqli_fetch_assoc($consulta)) {
        $total += $row['quantidade'] * $row['preco'];
    }
    $inserir = mysqli_query($conexao, "INSERT INTO pedidos (id_sala, num_mesa, total, observacao, id_usuario) VALUES ($id_sala, $mesas, '$total', '$observacao', $id_user)");
    $id_pedido = mysqli_insert_id($conexao);
    if ($inserir == 1) {
        //$insertarDet = 0;
        $consulta = mysqli_query($conexao, "SELECT d.*, p.nome FROM temp_pedidos d INNER JOIN pratos p ON d.id_produto = p.id WHERE d.id_usuario = $id_user");
        while ($dado = mysqli_fetch_assoc($consulta)) {
            $nome = $dado['nome'];
            $quantidade = $dado['quantidade'];
            $preco = $dado['preco'];
            $inserirDet = mysqli_query($conexao, "INSERT INTO detalhe_pedidos (nome, preco, quantidade, id_pedido) VALUES ('$nome', '$preco', $quantidade, $id_pedido)");
        }
        if ($inserirDet > 0) {
            $eliminar = mysqli_query($conexao, "DELETE FROM temp_pedidos WHERE id_usuario = $id_user");
            $sala = mysqli_query($conexao, "SELECT * FROM salas WHERE id = $id_sala");
            $resultSala = mysqli_fetch_assoc($sala);
            $msg = array('mensagem' => $resultSala['mesas']);
        }
    } else {
        $msg = array('mensagem' => 'error');
    }

    echo json_encode($msg);
    die();
} else if (isset($_GET['editarUsuario'])) {
    $idusuario = $_GET['id'];
    $sql = mysqli_query($conexao, "SELECT * FROM usuario WHERE idusuario = $idusuario");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarProduto'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexao, "SELECT * FROM pratos WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['finalizarPedido'])) {
    $id_sala = $_GET['id_sala'];
    $id_user = $_SESSION['idUser'];
    $mesas = $_GET['mesas'];
    $inserir = mysqli_query($conexao, "UPDATE pedidos SET estado='FINALIZADO' WHERE id_sala=$id_sala AND num_mesa=$mesas AND estado='PENDENTE' AND id_usuario=$id_user");
    if ($inserir) {
        $sala = mysqli_query($conexao, "SELECT * FROM salas WHERE id = $id_sala");
        $resultSala = mysqli_fetch_assoc($sala);
        $msg = array('mensagem' => $resultSala['mesas']);
    } else {
        $msg = array('mensagem' => 'error');
    }

    echo json_encode($msg);
    die();
}
if (isset($_POST['regDetalhe'])) {
    $id_produto = $_POST['id'];
    $id_user = $_SESSION['idUser'];
    $consulta = mysqli_query($conexao, "SELECT * FROM temp_pedidos WHERE id_produto = $id_produto AND id_usuario = $id_user");
    $row = mysqli_fetch_assoc($consulta);
    if (empty($row)) {
        $produto = mysqli_query($conexao, "SELECT * FROM pratos WHERE id = $id_produto");
        $result = mysqli_fetch_assoc($produto);
        $preco = $result['preco'];
        $query = mysqli_query($conexao, "INSERT INTO temp_pedidos (quantidade, preco, id_produto, id_usuario) VALUES (1, $preco, $id_produto, $id_user)");
    } else {
        $nova = $row['quantidade'] + 1;
        $query = mysqli_query($conexao, "UPDATE temp_pedidos SET quantidade = $nova WHERE id_produto = $id_produto AND id_usuario = $id_user");
    }
    if ($query) {
        $msg = "registrado";
    } else {
        $msg = "erro ao registrar";
    }
    echo json_encode($msg);
    die();
}
