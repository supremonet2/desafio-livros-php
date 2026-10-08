<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AutorAssuntoCrudTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_author_can_be_created_and_loaded_for_editing(): void
    {
        $this->postJson('/api/autors', ['CodAu' => 233, 'Nome' => 'Ray'])
            ->assertSuccessful()
            ->assertJsonPath('data.CodAu', 233);

        $this->getJson('/api/autors/233')
            ->assertOk()
            ->assertJsonPath('data.Nome', 'Ray');

        $this->assertDatabaseHas('autors', ['CodAu' => 233, 'Nome' => 'Ray']);
    }

    public function test_author_can_be_updated_without_changing_its_code(): void
    {
        DB::table('autors')->insert(['CodAu' => 233, 'Nome' => 'Ray']);

        $this->putJson('/api/autors/233', ['CodAu' => 233, 'Nome' => 'Raimundo'])
            ->assertOk()
            ->assertJsonPath('data.Nome', 'Raimundo');

        $this->assertDatabaseHas('autors', ['CodAu' => 233, 'Nome' => 'Raimundo']);
    }

    public function test_author_store_rejects_duplicate_code_with_custom_message(): void
    {
        DB::table('autors')->insert(['CodAu' => 233, 'Nome' => 'Ray']);

        $this->postJson('/api/autors', ['CodAu' => 233, 'Nome' => 'Outro autor'])
            ->assertUnprocessable()
            ->assertJsonPath('errors.CodAu.0', 'Este código de autor já está em uso.');

        $this->assertDatabaseCount('autors', 1);
    }

    public function test_author_update_inherits_name_validation_from_store(): void
    {
        DB::table('autors')->insert(['CodAu' => 233, 'Nome' => 'Ray']);

        $this->putJson('/api/autors/233', ['Nome' => str_repeat('A', 41)])
            ->assertUnprocessable()
            ->assertJsonPath('errors.Nome.0', 'O nome deve ter no máximo 40 caracteres.');

        $this->assertDatabaseHas('autors', ['CodAu' => 233, 'Nome' => 'Ray']);
    }

    public function test_unlinked_author_can_be_deleted(): void
    {
        DB::table('autors')->insert(['CodAu' => 233, 'Nome' => 'Ray']);

        $this->deleteJson('/api/autors/233')->assertNoContent();

        $this->assertDatabaseMissing('autors', ['CodAu' => 233]);
    }

    public function test_linked_author_cannot_be_deleted(): void
    {
        DB::table('livros')->insert(['Codl' => 10, 'Titulo' => 'Livro', 'Editora' => 'Editora', 'Edicao' => 1, 'AnoPublicacao' => '2020']);
        DB::table('autors')->insert(['CodAu' => 233, 'Nome' => 'Ray']);
        DB::table('Livro_Autor')->insert(['Livro_Codl' => 10, 'Autor_CodAu' => 233]);

        $this->deleteJson('/api/autors/233')
            ->assertConflict()
            ->assertJsonPath('message', 'Não é possível excluir o autor porque ele está vinculado a um livro.');

        $this->assertDatabaseHas('autors', ['CodAu' => 233]);
        $this->assertDatabaseHas('Livro_Autor', ['Livro_Codl' => 10, 'Autor_CodAu' => 233]);
    }

    public function test_subject_can_be_created_and_loaded_for_editing(): void
    {
        $this->postJson('/api/assuntos', ['CodAs' => 7, 'Descricao' => 'Culinária'])
            ->assertSuccessful()
            ->assertJsonPath('data.CodAs', 7);

        $this->getJson('/api/assuntos/7')
            ->assertOk()
            ->assertJsonPath('data.Descricao', 'Culinária');

        $this->assertDatabaseHas('assuntos', ['CodAs' => 7, 'Descricao' => 'Culinária']);
    }

    public function test_subject_can_be_updated_without_changing_its_code(): void
    {
        DB::table('assuntos')->insert(['CodAs' => 7, 'Descricao' => 'Culinária']);

        $this->putJson('/api/assuntos/7', ['CodAs' => 7, 'Descricao' => 'História'])
            ->assertOk()
            ->assertJsonPath('data.Descricao', 'História');

        $this->assertDatabaseHas('assuntos', ['CodAs' => 7, 'Descricao' => 'História']);
    }

    public function test_subject_description_cannot_exceed_database_limit(): void
    {
        $this->postJson('/api/assuntos', ['CodAs' => 7, 'Descricao' => str_repeat('A', 21)])
            ->assertUnprocessable()
            ->assertJsonPath('errors.Descricao.0', 'O assunto deve ter no máximo 20 caracteres.');

        $this->assertDatabaseMissing('assuntos', ['CodAs' => 7]);
    }

    public function test_subject_update_rejects_description_over_database_limit(): void
    {
        DB::table('assuntos')->insert(['CodAs' => 7, 'Descricao' => 'Culinária']);

        $this->putJson('/api/assuntos/7', ['CodAs' => 7, 'Descricao' => str_repeat('A', 21)])
            ->assertUnprocessable()
            ->assertJsonPath('errors.Descricao.0', 'O assunto deve ter no máximo 20 caracteres.');

        $this->assertDatabaseHas('assuntos', ['CodAs' => 7, 'Descricao' => 'Culinária']);
    }

    public function test_unlinked_subject_can_be_deleted(): void
    {
        DB::table('assuntos')->insert(['CodAs' => 7, 'Descricao' => 'Culinária']);

        $this->deleteJson('/api/assuntos/7')->assertNoContent();

        $this->assertDatabaseMissing('assuntos', ['CodAs' => 7]);
    }

    public function test_linked_subject_cannot_be_deleted(): void
    {
        DB::table('livros')->insert(['Codl' => 10, 'Titulo' => 'Livro', 'Editora' => 'Editora', 'Edicao' => 1, 'AnoPublicacao' => '2020']);
        DB::table('assuntos')->insert(['CodAs' => 7, 'Descricao' => 'Culinária']);
        DB::table('Livro_Assunto')->insert(['Livro_Codl' => 10, 'Assunto_CodAs' => 7]);

        $this->deleteJson('/api/assuntos/7')
            ->assertConflict()
            ->assertJsonPath('message', 'Não é possível excluir o assunto porque ele está vinculado a um livro.');

        $this->assertDatabaseHas('assuntos', ['CodAs' => 7]);
        $this->assertDatabaseHas('Livro_Assunto', ['Livro_Codl' => 10, 'Assunto_CodAs' => 7]);
    }
}
