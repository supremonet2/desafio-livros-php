<?php

return [
    'array' => 'O campo :attribute deve ser uma lista.',
    'decimal' => 'O campo :attribute deve ter :decimal casas decimais.',
    'digits' => 'O campo :attribute deve ter :digits dígitos.',
    'distinct' => 'O campo :attribute contém um valor repetido.',
    'exists' => 'O valor selecionado para :attribute é inválido.',
    'integer' => 'O campo :attribute deve ser um número inteiro.',
    'max' => [
        'array' => 'O campo :attribute não pode ter mais de :max itens.',
        'file' => 'O arquivo :attribute não pode ter mais de :max kilobytes.',
        'numeric' => 'O campo :attribute não pode ser maior que :max.',
        'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
    ],
    'min' => [
        'array' => 'O campo :attribute deve ter pelo menos :min itens.',
        'file' => 'O arquivo :attribute deve ter pelo menos :min kilobytes.',
        'numeric' => 'O campo :attribute deve ser pelo menos :min.',
        'string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
    ],
    'numeric' => 'O campo :attribute deve ser um número.',
    'required' => 'O campo :attribute é obrigatório.',
    'string' => 'O campo :attribute deve ser um texto.',
    'unique' => 'O valor de :attribute já está em uso.',

    'custom' => [],

    'attributes' => [
        'CodAu' => 'código do autor',
        'CodAs' => 'código do assunto',
        'Codl' => 'código do livro',
        'Nome' => 'nome do autor',
        'Descricao' => 'descrição do assunto',
        'titulo' => 'título',
        'editora' => 'editora',
        'edicao' => 'edição',
        'ano' => 'ano de publicação',
        'valor' => 'valor',
        'autores' => 'autores',
        'autores.*' => 'autor',
        'assuntos' => 'assuntos',
        'assuntos.*' => 'assunto',
    ],
];
