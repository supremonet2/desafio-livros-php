<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/dashboard/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.6.2/dist/css/tom-select.css" rel="stylesheet">

    <style>
        .form-check-input {
            border-color: green !important;
        }

        .btn-add {
            margin-bottom: 20px;
        }

        .year-picker-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .25rem;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="@yield('container-class', 'container')">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.6.2/dist/js/tom-select.complete.min.js"></script>
    <script src="{{ asset('js/livros.js') }}"></script>
    <script src="{{ asset('js/relatorio.js') }}"></script>
    <script src="{{ asset('js/autores.js') }}"></script>
    <script src="{{ asset('js/assuntos.js') }}"></script>

</body>

</html>
