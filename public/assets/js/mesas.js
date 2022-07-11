
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
                    "url": "/api/mesas",
                    "type": "GET"
                },
                "columns":[
                    {"data":"codigo"
                        , className: "text-center pedido-open"},
                    {"data":"descricao"
                        , className: "text-center pedido-open"},
                    {"data":"cliente"
                        , className: "text-center pedido-open"},
                    {"data":"valor"
                        , className: "text-center pedido-open"},
                    {"data":null,
                        defaultContent: '\
                                <button id="btnPedido" class="btn btn-sm btn-success"><i class="fa fa-dollar"></i></button> \
                                <button id="btnEditMesa" class="btn btn-circle btn-sm btn-warning" ><i class="fa fa-pencil"></i></button> \
                                <button id="btnDeleteMesa" class="btn btn-circle btn-sm btn-danger"><i class="fa fa-trash"></i></button> \
                            '
                    },
                    {"data":"ativo"
                        ,render: function(data, type, row){
                            return `Ativo${data}`;
                        }
                        ,visible: false
                        ,searchable: true
                    }
                ],
                "oSearch": {"sSearch": "Ativo1"},
                "iDisplayLength": 50,
            }
        ).on('dblclick','.pedido-open',function(){
            var data = table.row(this).data();
            if(data!=null && data!=undefined && data.idMesa!=null)
                window.location.href = `/pedido/${data.idMesa}/${data.idPedido??''}`;
        }).on('click','#btnEditMesa',function(){
            var data = table.row($(this).parents('tr')).data();
            if(data!=null && data!=undefined && data.idMesa!=null){

                $('#mdMesaDescricao').val(data.descricao);
                $('#mdMesaAtivo').prop('checked',data.ativo==1);
                $('#mesasModal').modal('show');
            }
        }).on('click','#btnPedido',function(){
            var data = table.row($(this).parents('tr')).data();
            if(data!=null && data!=undefined && data.idMesa!=null){
                window.location.href = `/pedido/${data.idMesa}/${data.idPedido??''}`;
            }
        }).on('click','#btnDeleteMesa',function(){
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
                            url: `/api/mesas/${data.idMesa}`,
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
                                text: 'A mesa foi apagada com sucesso.',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        });
                    }
                });
            }
        });
        $('#btnRefreshTable').click(function(){
            table.ajax.reload();
        });

        $('#btnAddMesa').click(function(){
            $('#mdMesaDescricao').val('');
            $('#mdMesaAtivo').prop('checked',true);
            $('#mesasModal').modal('show');
        });

        $('#btnFilterActive').click(function(){
            var button = $(this);
            if(button.hasClass('bg-gradient-success')){
                button.removeClass('bg-gradient-success');
                button.addClass('bg-gradient-warning');
                button.html('<i class="fa-regular fa-square-minus"></i>');
                table.search('').draw();
            }else if(button.hasClass('bg-gradient-warning')){
                button.removeClass('bg-gradient-warning');
                button.addClass('bg-gradient-danger');
                button.html('<i class="fa-regular fa-square"></i>');
                table.search('ativo0',).draw();
            }else{
                button.removeClass('bg-gradient-danger');
                button.addClass('bg-gradient-success');
                button.html('<i class="fa-regular fa-square-check"></i>');
                table.search('ativo1',).draw();
            }                     
        });

        window.tabela = table;
    })