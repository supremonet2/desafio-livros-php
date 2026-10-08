<?php

namespace Tests\Feature;

use App\Models\Assunto;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LivroModalDataTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_initial_page_lists_saved_authors_and_subjects_in_book_selects(): void
    {
        DB::table('autors')->insert(['CodAu' => 233, 'Nome' => 'Ray']);
        DB::table('assuntos')->insert(['CodAs' => 7, 'Descricao' => 'Culinária']);

        $this->get('/livros')
            ->assertOk()
            ->assertSee('<option value="233">Ray</option>', false)
            ->assertSee('<option value="7">Culinária</option>', false);
    }

    public function test_book_api_returns_linked_authors_and_subjects_for_editing(): void
    {
        DB::table('livros')->insert([
            'Codl' => 10,
            'Titulo' => 'Livro de teste',
            'Editora' => 'Editora de teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020',
        ]);
        DB::table('autors')->insert(['CodAu' => 233, 'Nome' => 'Ray']);
        DB::table('assuntos')->insert(['CodAs' => 7, 'Descricao' => 'Culinária']);
        DB::table('Livro_Autor')->insert(['Livro_Codl' => 10, 'Autor_CodAu' => 233]);
        DB::table('Livro_Assunto')->insert(['Livro_Codl' => 10, 'Assunto_CodAs' => 7]);

        $this->getJson('/api/livros/10')
            ->assertOk()
            ->assertJsonPath('data.autores.0.CodAu', 233)
            ->assertJsonPath('data.assuntos.0.CodAs', 7);

        $this->assertSame([10], Assunto::findOrFail(7)->livros->pluck('Codl')->all());
    }
}
