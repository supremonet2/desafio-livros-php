<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LivroPersistenceTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_create_saves_book_value_and_selected_authors_and_subjects(): void
    {
        DB::table('autors')->insert([
            ['CodAu' => 233, 'Nome' => 'Ray'],
            ['CodAu' => 234, 'Nome' => 'Maria'],
        ]);
        DB::table('assuntos')->insert(['CodAs' => 7, 'Descricao' => 'Culinária']);

        $this->postJson('/api/livros', [
            'Codl' => 10,
            'titulo' => 'Livro de teste',
            'editora' => 'Editora de teste',
            'edicao' => 2,
            'ano' => '2020',
            'valor' => 'R$ 1.234,56',
            'autores' => [233, 234],
            'assuntos' => [7],
        ])->assertCreated()
            ->assertJsonPath('data.Codl', 10)
            ->assertJsonPath('data.autores.0.CodAu', 233)
            ->assertJsonPath('data.assuntos.0.CodAs', 7);

        $this->assertDatabaseHas('livros', [
            'Codl' => 10,
            'Titulo' => 'Livro de teste',
            'AnoPublicacao' => '2020',
            'Valor' => '1234.56',
        ]);
        $this->assertDatabaseHas('Livro_Autor', ['Livro_Codl' => 10, 'Autor_CodAu' => 233]);
        $this->assertDatabaseHas('Livro_Autor', ['Livro_Codl' => 10, 'Autor_CodAu' => 234]);
        $this->assertDatabaseHas('Livro_Assunto', ['Livro_Codl' => 10, 'Assunto_CodAs' => 7]);
    }

    public function test_update_replaces_book_fields_and_selected_relationships(): void
    {
        DB::table('livros')->insert([
            'Codl' => 10,
            'Titulo' => 'Título antigo',
            'Editora' => 'Editora antiga',
            'Edicao' => 1,
            'AnoPublicacao' => '2019',
            'Valor' => '12.00',
        ]);
        DB::table('autors')->insert([
            ['CodAu' => 233, 'Nome' => 'Ray'],
            ['CodAu' => 234, 'Nome' => 'Maria'],
        ]);
        DB::table('assuntos')->insert([
            ['CodAs' => 7, 'Descricao' => 'Culinária'],
            ['CodAs' => 8, 'Descricao' => 'História'],
        ]);
        DB::table('Livro_Autor')->insert(['Livro_Codl' => 10, 'Autor_CodAu' => 233]);
        DB::table('Livro_Assunto')->insert(['Livro_Codl' => 10, 'Assunto_CodAs' => 7]);

        $this->putJson('/api/livros/10', [
            'Codl' => 10,
            'titulo' => 'Título novo',
            'editora' => 'Editora nova',
            'edicao' => 3,
            'ano' => '2021',
            'valor' => 'R$ 99,90',
            'autores' => [234],
            'assuntos' => [8],
        ])->assertOk()
            ->assertJsonPath('data.Titulo', 'Título novo')
            ->assertJsonPath('data.autores.0.CodAu', 234)
            ->assertJsonPath('data.assuntos.0.CodAs', 8);

        $this->assertDatabaseHas('livros', ['Codl' => 10, 'Titulo' => 'Título novo', 'Valor' => '99.90']);
        $this->assertDatabaseHas('Livro_Autor', ['Livro_Codl' => 10, 'Autor_CodAu' => 234]);
        $this->assertDatabaseMissing('Livro_Autor', ['Livro_Codl' => 10, 'Autor_CodAu' => 233]);
        $this->assertDatabaseHas('Livro_Assunto', ['Livro_Codl' => 10, 'Assunto_CodAs' => 8]);
        $this->assertDatabaseMissing('Livro_Assunto', ['Livro_Codl' => 10, 'Assunto_CodAs' => 7]);
    }

    public function test_delete_removes_book_and_its_author_and_subject_links(): void
    {
        DB::table('livros')->insert([
            'Codl' => 10,
            'Titulo' => 'Livro de teste',
            'Editora' => 'Editora de teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020',
            'Valor' => '12.00',
        ]);
        DB::table('autors')->insert(['CodAu' => 233, 'Nome' => 'Ray']);
        DB::table('assuntos')->insert(['CodAs' => 7, 'Descricao' => 'Culinária']);
        DB::table('Livro_Autor')->insert(['Livro_Codl' => 10, 'Autor_CodAu' => 233]);
        DB::table('Livro_Assunto')->insert(['Livro_Codl' => 10, 'Assunto_CodAs' => 7]);

        $this->deleteJson('/api/livros/10')->assertNoContent();

        $this->assertDatabaseMissing('livros', ['Codl' => 10]);
        $this->assertDatabaseMissing('Livro_Autor', ['Livro_Codl' => 10]);
        $this->assertDatabaseMissing('Livro_Assunto', ['Livro_Codl' => 10]);
        $this->assertDatabaseHas('autors', ['CodAu' => 233]);
        $this->assertDatabaseHas('assuntos', ['CodAs' => 7]);
    }
}
