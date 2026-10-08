// --- Funcão generica que serve para (Autor, Livros, Assuntos)
function confirmaDeletar(tipo, id) {
    $('#formDelete').data('tipo', tipo).data('id', id);
    $('#errosDelete').text('');
    $('#modal_delete').modal('show');
}

function salvarLivro(event) {
    event.preventDefault();

    const form = $('#formLivro');
    const id = form.data('id');

    limparErrosLivro();
    $('#salvarLivro').prop('disabled', true);

    $.ajax({
        url: id ? '/api/livros/' + id : '/api/livros',
        method: id ? 'PUT' : 'POST',
        data: form.serialize(),
        dataType: 'json',

        success: livroSalvo,
        error: mostrarErrosLivro,
        complete: liberarBotaoSalvar
    });
}

function livroSalvo() {
    bootstrap.Modal.getOrCreateInstance(
        document.getElementById('modal_livro')
    ).hide();

    window.location.reload();
}

function mostrarErrosLivro(xhr) {
    if (xhr.status === 422 && xhr.responseJSON?.errors) {
        const erros = xhr.responseJSON.errors;

        $('#errosLivro').text(
            Object.values(erros).flat().join(' ')
        );

        return;
    }

    $('#errosLivro').text(
        'Não foi possível salvar o livro. Tente novamente.'
    );
}

function limparErrosLivro() {
    $('#errosLivro').text('');
    $('#formLivro .is-invalid').removeClass('is-invalid');
    $('#formLivro .invalid-feedback').text('');
}

function liberarBotaoSalvar() {
    $('#salvarLivro').prop('disabled', false);
}

function buscarLivros(id) {
    $.ajax({
        url: id ? '/api/livros/' + id : '/api/livros',
        method: 'GET',

        success: function (response) {
            if ($('#formLivro').data('id') != id) {
                return;
            }

            const livro = response.data;
            $('#Codl').val(livro.Codl);
            $('#titulo').val(livro.Titulo);
            $('#editora').val(livro.Editora);
            $('#edicao').val(livro.Edicao);
            $('#ano').val(livro.AnoPublicacao);
            $('#valor').val(livro.Valor == null ? '' : new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(Number(livro.Valor)));
            $('#autor')[0].tomselect.setValue(livro.autores.map(autor => String(autor.CodAu)), true);
            $('#assunto')[0].tomselect.setValue(livro.assuntos.map(assunto => String(assunto.CodAs)), true);
            $('#salvarLivro').prop('disabled', false);
        },

        error: function (xhr) {
            if ($('#formLivro').data('id') == id) {
                $('#errosLivro').text('Não foi possível carregar o livro. Tente novamente.');
            }
        }
    });
}

function abrirModalLivro(id) {
    const form = $('#formLivro');
    form[0].reset();
    form.data('id', id ?? null);
    $('#Codl').prop('readonly', Boolean(id));
    $('#salvarLivro').prop('disabled', Boolean(id));
    $('#assunto')[0].tomselect.clear();
    $('#autor')[0].tomselect.clear();
    limparErrosLivro();

    if (id) {
        buscarLivros(id);
    }
    $('#modal_livro').modal('show');
}

if (document.getElementById('formLivro')) {
    const anoInput = document.getElementById('ano');
    const opcoesAnos = document.getElementById('opcoesAnos');
    const anoAtual = new Date().getFullYear();
    let inicioPeriodo = anoAtual - 8;

    function mostrarAnos() {
        document.getElementById('intervaloAnos').textContent = `${inicioPeriodo}–${inicioPeriodo + 8}`;
        document.getElementById('anoAnterior').disabled = inicioPeriodo - 9 < 1000;
        document.getElementById('anoSeguinte').disabled = inicioPeriodo + 8 >= anoAtual;
        opcoesAnos.replaceChildren();

        for (let ano = inicioPeriodo; ano < inicioPeriodo + 9; ano++) {
            const botao = document.createElement('button');
            botao.type = 'button';
            botao.className = 'btn btn-sm btn-outline-secondary';
            botao.textContent = String(ano);
            botao.disabled = ano > anoAtual;
            botao.addEventListener('click', () => {
                anoInput.value = String(ano);
                bootstrap.Dropdown.getOrCreateInstance(anoInput).hide();
            });
            opcoesAnos.appendChild(botao);
        }
    }

    document.getElementById('anoAnterior').addEventListener('click', () => {
        inicioPeriodo -= 9;
        mostrarAnos();
    });

    document.getElementById('anoSeguinte').addEventListener('click', () => {
        inicioPeriodo += 9;
        mostrarAnos();
    });

    mostrarAnos();

    const valorInput = document.getElementById('valor');
    const formatoMoedaBR = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
    const padraoValorBR = /^(?:R\$\s*)?(?:\d{1,3}(?:\.\d{3})+|\d+)(?:,\d{1,2})?$/u;

    valorInput.addEventListener('input', () => {
        const digitado = valorInput.value.replace(/\D/g, '').replace(/^0+(?=\d)/, '');

        if (!digitado) {
            valorInput.value = '';
            return;
        }

        const valorEmCentavos = digitado.padStart(3, '0');
        const reais = valorEmCentavos.slice(0, -2).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        const centavos = valorEmCentavos.slice(-2);
        valorInput.value = `R$ ${reais},${centavos}`;
        valorInput.setSelectionRange(valorInput.value.length, valorInput.value.length);
    });

    valorInput.addEventListener('keydown', (event) => {
        if (event.key === 'Backspace' && /^R\$\s*0,00$/u.test(valorInput.value)) {
            valorInput.value = '';
            event.preventDefault();
        }
    });

    valorInput.addEventListener('blur', () => {
        const texto = valorInput.value.trim();
        if (!padraoValorBR.test(texto)) {
            return;
        }

        const numero = Number(texto.replace(/^R\$\s*/u, '').replaceAll('.', '').replace(',', '.'));
        valorInput.value = formatoMoedaBR.format(numero);
    });

    new TomSelect("#autor", {
        persist: false,
        createOnBlur: true,
        create: false,
        render: {
            no_results: () => '<div class="no-results">Nenhum autor encontrado.</div>'
        }
    });

    new TomSelect("#assunto", {
        persist: false,
        createOnBlur: true,
        create: false,
        render: {
            no_results: () => '<div class="no-results">Nenhum assunto encontrado.</div>'
        }
    });
}
