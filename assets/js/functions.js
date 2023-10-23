document.addEventListener("DOMContentLoaded", function () {
    if ($('#detalhe_pedidos').length > 0) {
        listar();
    }
    
    $('#tbl').DataTable({
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Portuguese.json"
        },
        "order": [
            [0, "desc"]
        ]
    });

    $(".confirmar").submit(function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza que deseja excluir?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        })
    })

    $('.addDetalhe').click(function () {
        let id_produto = $(this).data('id');
        registrarDetalhe(id_produto);
    })

    $('#realizar_pedido').click(function (e) {
        e.preventDefault();
        var action = 'processarPedido';
        var id_sala = $('#id_sala').val();
        var mesas = $('#mesas').val();
        var observacao = $('#observacao').val();
        $.ajax({
            url: 'ajax.php',
            async: true,
            data: {
                processarPedido: action,
                id_sala: id_sala,
                mesas: mesas,
                observacao: observacao
            },
            success: function (response) {
                const res = JSON.parse(response);
                if (response != 'error') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Pedido Realizado',
                        showConfirmButton: false,
                        timer: 2000
                    })
                    setTimeout(() => {
                        window.location = 'mesas.php?id_sala=' + id_sala + '&mesas=' + res.mensaje;
                    }, 1500);
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Erro ao gerar pedido',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
            },
            error: function (error) {
                alert(error);
            }
        });
    });

    $('.finalizarPedido').click(function () {
        var action = 'finalizarPedido';
        var id_sala = $('#id_sala').val();
        var mesas = $('#mesas').val();
        $.ajax({
            url: 'ajax.php',
            async: true,
            data: {
                finalizarPedido: action,
                id_sala: id_sala,
                mesas: mesas
            },
            success: function (response) {
                const res = JSON.parse(response);
                if (response != 'error') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Pedido Finalizado',
                        showConfirmButton: false,
                        timer: 2000
                    })
                    setTimeout(() => {
                        window.location = 'mesas.php?id_sala=' + id_sala + '&mesas=' + res.mensagem;
                    }, 1500);
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Erro ao finalizar',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
            },
            error: function (error) {
                alert(error);
            }
        });

    })
})

function listar() {
    let html = '';
    let detalhe = 'detalhe';
    $.ajax({
        url: "ajax.php",
        dataType: "json",
        data: {
            detalhe: detalhe
        },
        success: function (response) {
            response.forEach(row => {
                html += `<div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12">
                            <img src="${ row.imagem }" class="product-image" alt="Product Image">
                        </div>
                        <p class="my-3">${row.nome}</p>
                        <h2 class="mb-0">${row.preco}</h2>
                        <div class="mt-1">
                            <input type="number" class="form-control addQuantidade mb-2" data-id="${row.id}" value="${row.quantidade}">
                            <button class="btn btn-danger eliminarPrato" type="button" data-id="${row.id}">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>`;
            });
            document.querySelector("#detalhe_pedidos").innerHTML = html;
            $('.eliminarPrato').click(function () {
                let id = $(this).data('id');
                eliminarPrato(id);
            })
            $('.addQuantidade').change(function (e) {
                let id = $(this).data('id');
                quantidadePrato(e.target.value, id);
            })
        }
    });
}

function registrarDetalhe(id_pro) {
    let action = 'regDetalhe';
    $.ajax({
        url: "ajax.php",
        type: 'POST',
        dataType: "json",
        data: {
            id: id_pro,
            regDetalhe: action
        },
        success: function (response) {
            if (response == 'registrado') {
                listar();
            }
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Produto cadastrado!',
                showConfirmButton: false,
                timer: 2000
            })
        }
    });
}

function eliminarPrato(id) {
    let detalhe = 'Eliminar'
    $.ajax({
        url: "ajax.php",
        data: {
            id: id,
            delete_detalhe: detalhe
        },
        success: function (response) {

            if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Produto Excluído',
                    showConfirmButton: false,
                    timer: 2000
                })
                listar();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Erro ao excluir produto',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}

function quantidadePrato(quantidade, id) {
    let detalhe = 'quantidade'
    $.ajax({
        url: "ajax.php",
        data: {
            id: id,
            quantidade: quantidade,
            detalhe_quantidade: detalhe
        },
        success: function (response) {

            if (response != 'ok') {
                listar();
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Erro ao inserir quantidade',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}

function btnMudar(e) {
    e.preventDefault();
    const atual = document.getElementById('atual').value;
    const nova = document.getElementById('nova').value;
    if (atual == "" || nova == "") {
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Os campos estão vazios',
            showConfirmButton: false,
            timer: 2000
        })
    } else {
        const mudar = 'pass';
        $.ajax({
            url: "ajax.php",
            type: 'POST',
            data: {
                atual: atual,
                nova: nova,
                mudar: mudar
            },
            success: function (response) {
                if (response == 'ok') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Senha alterada!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                    document.querySelector('#frmPass').reset();
                    $("#nuevo_pass").modal("hide");
                } else if (response == 'dif') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'A senha atual está incorreta!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Erro ao alterar a senha!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
            }
        });
    }
}

function editarUsuario(id) {
    const action = "editarUsuario";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarUsuario: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#nome').val(datos.nome);
            $('#rol').val(datos.rol);
            $('#email').val(datos.email);
            $('#id').val(datos.id);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}

function editarPrato(id) {
    const action = "editarProduto";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarProduto: action,
            id: id
        },
        success: function (response) {
            const dados = JSON.parse(response);
            $('#prato').val(datos.nome);
            $('#preco').val(datos.preco);
            $('#foto_atual').val(datos.foto_atual);
            $('#id').val(dados.id);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}

function limpar() {
    $('#formulario')[0].reset();
    $('#id').val('');
    $('#btnAccion').val('Registrar');
}