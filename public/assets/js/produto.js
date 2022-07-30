function editProduto(data){
    $('.modal-title').html('Editar Produto');
    if(data.idProduto!=null){
        $('#mdIdProduto').val(data.idProduto);
        $('#mdProdutoCodigo').val(data.codigo);
        $('#mdProdutoNome').val(data.nome);
        $('#mdFkTemporada').val(data.fkTemporada);
        $('#mdProdutoValor').val(data.valorUnit);
        $('#produtosModal').modal('show');
    }
}

$(document).ready(function() {
    table= $('#dataTable').DataTable(
        {
            "bFilter": true,
            "bLengthChange": true,
            "serverSide": false,                
            "language": {
                "url": "assets/vendor/datatables/dataTables.i18n.pt-br.json"
            },
            "ajax": {
                "url": "/api/produtos",
                "type": "GET"
            },
            "columns":[
                {"data":"codigo"
                    , className: "text-center row-open"},
                {"data":"nome"
                    , className: "text-center row-open"},
                {"data":"valorUnit"
                    , className: "text-center row-open"
                    , render: $.fn.dataTable.render.number( '.', ',', 2, 'R$ ' )
                },
                {"data":null,
                    defaultContent: '\
                            <button id="btnEditProduto" class="btn btn-circle btn-sm btn-warning" ><i class="fa fa-pencil"></i></button> \
                            <button id="btnDeleteProduto" class="btn btn-circle btn-sm btn-danger"><i class="fa fa-trash"></i></button> \
                        '
                },                    
            ],  
            "iDisplayLength": 50,
        }
    ).on('dblclick','.row-open',function(){
        var data = table.row(this).data();
        if(data!=null && data!=undefined && data.idProduto!=null)
            editProduto(data);
    }).on('click','#btnEditProduto',function(){
        var data = table.row($(this).parents('tr')).data();
        if(data!=null && data!=undefined && data.idMesa!=null){
            editMesa(data);
        }
    }).on('click','#btnDeleteProduto',function(){
        var data = table.row($(this).parents('tr')).data();
        if(data!=null && data!=undefined && data.idMesa!=null){
            swal.fire({
                title: 'Você tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, apague a mesa!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: `/api/produtos/${data.idProduto}`,
                        type: 'DELETE',
                        success: function(data){
                            table.ajax.reload();
                        }
                    }).fail(function(data){
                        swal.fire({
                            title: 'Erro!',
                            text: data.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }).always(function(){
                        swal.fire({
                            title: 'Apagado!',
                            text: 'A produto foi apagada com sucesso.',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });
                    });
                }
            });
        }
    });
}); 