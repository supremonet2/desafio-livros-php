<!-- Modal -->
<div class="modal fade" id="modal_livro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Livros</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div id="errosLivro" class="text-danger mb-2" role="alert"></div>
                <form id="formLivro" class="row g-3">
                    <div class="col-md-2">
                        <label for="Codl" class="form-label">Código do livro</label>
                        <input type="number" name="Codl" class="form-control" id="Codl" min="1">
                    </div>
                    <div class="col-md-5">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" name="titulo" class="form-control" id="titulo">
                    </div>
                    <div class="col-md-5">
                        <label for="editora" class="form-label">Editora</label>
                        <input type="text" name="editora" class="form-control" id="editora">
                    </div>

                    <div class="col-md-4">
                        <label for="edicao" class="form-label">Edição</label>
                        <input type="text" name="edicao" class="form-control" id="edicao">
                    </div>
                    <div class="col-md-4">
                        <label for="ano" class="form-label">Ano de publicação</label>
                        <div class="dropdown">
                            <input type="text" name="ano" class="form-control" id="ano"
                                placeholder="Escolha o ano" readonly data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" aria-expanded="false" autocomplete="off">
                            <div class="dropdown-menu p-3" style="min-width: 16rem">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="anoAnterior"
                                        aria-label="Período anterior">&lsaquo;</button>
                                    <span id="intervaloAnos"></span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="anoSeguinte"
                                        aria-label="Próximo período">&rsaquo;</button>
                                </div>
                                <div id="opcoesAnos" class="year-picker-grid"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="valor" class="form-label">Valor</label>
                        <input type="text" name="valor" class="form-control" id="valor"
                            inputmode="decimal" placeholder="R$ 0,00" autocomplete="off">
                    </div>
                    <div class="col-md-6">
                        <label for="autor" class="form-label">Autor</label>
                        <select id="autor" name="autores[]" multiple>
                            @foreach($autores as $autor)
                                <option value="{{ $autor->CodAu }}">{{ $autor->Nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="assunto" class="form-label">Assunto</label>
                        <select id="assunto" name="assuntos[]" multiple>
                            @foreach($assuntos as $assunto)
                                <option value="{{ $assunto->CodAs }}">{{ $assunto->Descricao }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="salvarLivro" onclick="salvarLivro(event)" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>
