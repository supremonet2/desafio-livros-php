
function salvarAutor(event) {
    event.preventDefault();

    const form = $('#formAutor');
    const id = form.data('id');

    limparErrosAutor();
    $('#salvarAutor').prop('disabled', true);

    $.ajax({
        url: id ? '/api/autors/' + id : '/api/autors',
        method: id ? 'PUT' : 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: autorSalvo,
        error: mostrarErrosAutor,
        complete: liberarBotaoSalvarAutor
    });
}

function autorSalvo() {
    bootstrap.Modal.getOrCreateInstance(
        document.getElementById('modal_autor')
    ).hide();

    sessionStorage.setItem('abaAposSalvar', 'cadastros-tab');
    window.location.reload();
}

function liberarBotaoSalvarAutor() {
    $('#salvarAutor').prop('disabled', false);
}

function mostrarErrosAutor(xhr) {
    if (xhr.status === 422 && xhr.responseJSON?.errors) {
        const erros = xhr.responseJSON.errors;

        $('#errosAutor').text(
            Object.values(erros).flat().join(' ')
        );

        return;
    }

    $('#errosAutor').text(
        'Não foi possível salvar o autor. Tente novamente.'
    );
}

function limparErrosAutor() {
    $('#errosAutor').text('');
    $('#formAutor .is-invalid').removeClass('is-invalid');
    $('#formAutor .invalid-feedback').text('');
}

function buscarAutores(id) {
    $.ajax({
        url: id ? '/api/autors/' + id : '/api/autors',
        method: 'GET',

        success: function (response) {
            if ($('#formAutor').data('id') != id) {
                return;
            }

            $('#CodAu').val(response.data.CodAu);
            $('#Nome').val(response.data.Nome);
            $('#salvarAutor').prop('disabled', false);
        },

        error: function () {
            if ($('#formAutor').data('id') == id) {
                $('#errosAutor').text('Não foi possível carregar o autor. Tente novamente.');
            }
        }
    });
}

function abrirModalAutor(id) {
    const form = $('#formAutor');
    form[0].reset();
    form.data('id', id ?? null);
    $('#CodAu').prop('readonly', Boolean(id));
    limparErrosAutor();
    $('#salvarAutor').prop('disabled', Boolean(id));

    if (id) {
        buscarAutores(id);
    }
    $('#modal_autor').modal('show');
}
