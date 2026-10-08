<!-- Modal -->
<div class="modal fade" id="modal_delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Aviso de Exclusão</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div id="errosDelete" class="text-danger" role="alert"></div>
                <form id="formDelete" class="row">
                    <div class="col-md-12">
                        <p>Deseja realmente excluir?</p>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="confirmarDelete" onclick="confirmDeletar()" class="btn btn-primary">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDeletar() {
        const form = $('#formDelete');
        const tipo = form.data('tipo');
        const id = form.data('id');
        $('#confirmarDelete').prop('disabled', true);

        $.ajax({
            url: `/api/${tipo}/${id}`,
            method: 'DELETE',
            success: function() {
                $('#modal_delete').modal('hide');
                window.location.reload();
            },
            error: function(xhr) {
                $('#errosDelete').text(xhr.responseJSON?.message ?? 'Não foi possível excluir este registro.');
            },
            complete: function() {
                $('#confirmarDelete').prop('disabled', false);
            }
        });
    }
</script>
