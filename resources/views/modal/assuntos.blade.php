<!-- Modal -->
<div class="modal fade" id="modal_assunto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Assunto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div id="errosAssunto" class="text-danger mb-2" role="alert"></div>
                <form id="formAssunto" class="row g-3">
                    <div class="col-md-3">
                        <label for="CodAs" class="form-label">Código</label>
                        <input type="number" name="CodAs" class="form-control" id="CodAs">
                    </div>
                    <div class="col-md-9">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" name="Descricao" class="form-control" id="Descricao" maxlength="20">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="salvarAssunto" onclick="salvarAsssunto(event)" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>
