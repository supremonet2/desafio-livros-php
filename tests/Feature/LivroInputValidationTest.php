<?php

namespace Tests\Feature;

use Tests\TestCase;

class LivroInputValidationTest extends TestCase
{
    public function test_rejects_a_future_year_and_a_malformed_brazilian_value(): void
    {
        $response = $this->postJson('/api/livros', [
            'titulo' => 'Livro de teste',
            'editora' => 'Editora de teste',
            'edicao' => '1',
            'ano' => '9999',
            'valor' => 'R$ 1,2,3',
        ]);

        $response->assertUnprocessable()
            ->assertJsonPath('errors.ano.0', 'O ano não pode ser posterior ao ano atual.')
            ->assertJsonPath('errors.valor.0', 'O valor deve ser numérico.');
    }

    public function test_accepts_a_year_and_brazilian_value_before_other_required_fields(): void
    {
        $response = $this->postJson('/api/livros', [
            'titulo' => 'Livro de teste',
            'editora' => 'Editora de teste',
            'edicao' => '1',
            'ano' => '2020',
            'valor' => "R$\u{00A0}1.234,56",
        ]);

        $response->assertUnprocessable()
            ->assertJsonMissingValidationErrors(['ano', 'valor'])
            ->assertJsonValidationErrorFor('autores');
    }
}
