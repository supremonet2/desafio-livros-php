

function salvarAsssunto(event) {
    event.preventDefault();

    const form = $('#formAssunto');
    const id = form.data('id');

    limparErrosAssunto();
    $('#salvarAssunto').prop('disabled', true);

    $.ajax({
        url: id ? '/api/assuntos/' + id : '/api/assuntos',
        method: id ? 'PUT' : 'POST',
        data: form.serialize(),
        dataType: 'json',

        success: assuntoSalvo,
        error: mostrarErrosAssunto,
        complete: liberarBotaoSalvarAssunto
    });
}

function assuntoSalvo() {
    bootstrap.Modal.getOrCreateInstance(
        document.getElementById('modal_assunto')
    ).hide();
    sessionStorage.setItem('abaAposSalvar', 'cadastros-tab');
    window.location.reload();
}

function mostrarErrosAssunto(xhr) {
    if (xhr.status === 422 && xhr.responseJSON?.errors) {
        const erros = xhr.responseJSON.errors;

        $('#errosAssunto').text(
            Object.values(erros).flat().join(' ')
        );

        return;
    }

    $('#errosAssunto').text(
        'Não foi possível salvar o assunto. Tente novamente.'
    );
}

function limparErrosAssunto() {
    $('#errosAssunto').text('');
    $('#formAssunto .is-invalid').removeClass('is-invalid');
    $('#formAssunto .invalid-feedback').text('');
}

function liberarBotaoSalvarAssunto() {
    $('#salvarAssunto').prop('disabled', false);
}

function buscarAssuntos(id) {
    $.ajax({
        url: id ? '/api/assuntos/' + id : '/api/assuntos',
        method: 'GET',

        success: function (response) {
            if ($('#formAssunto').data('id') != id) {
                return;
            }

            $('#CodAs').val(response.data.CodAs);
            $('#Descricao').val(response.data.Descricao);
            $('#salvarAssunto').prop('disabled', false);
        },

        error: function () {
            if ($('#formAssunto').data('id') == id) {
                $('#errosAssunto').text('Não foi possível carregar o assunto. Tente novamente.');
            }
        }
    });
}

function abrirModalAssunto(id) {
    const form = $('#formAssunto');
    form[0].reset();
    form.data('id', id ?? null);
    $('#CodAs').prop('readonly', Boolean(id));
    limparErrosAssunto();
    $('#salvarAssunto').prop('disabled', Boolean(id));

    if (id) {
        buscarAssuntos(id);
    }
    $('#modal_assunto').modal('show');
}
