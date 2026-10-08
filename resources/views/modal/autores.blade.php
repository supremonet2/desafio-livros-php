<!-- Modal -->
<div class="modal fade" id="modal_autor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Autor</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div id="errosAutor" class="text-danger mb-2" role="alert"></div>
                <form id="formAutor" class="row g-3">

                    <div class="col-md-3">
                        <label for="CodAu" class="form-label">Código</label>
                        <input type="number" name="CodAu" class="form-control" id="CodAu">
                    </div>
                    <div class="col-md-9">
                        <label for="Nome" class="form-label">Nome</label>
                        <input type="text" name="Nome" class="form-control" id="Nome">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="salvarAutor" onclick="salvarAutor(event)" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>
