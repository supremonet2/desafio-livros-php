<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DemoLivrosSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_seeder_creates_five_books_with_authors_and_subjects_for_the_report(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('livros', 5);
        $this->assertDatabaseCount('autors', 5);
        $this->assertDatabaseCount('assuntos', 5);
        $this->assertDatabaseCount('Livro_Autor', 8);
        $this->assertDatabaseCount('Livro_Assunto', 10);

        $linhas = DB::table('vw_relatorio_livros_por_autor')->where('livro_codigo', 1001)->get();

        $this->assertCount(4, $linhas);
        $this->assertSame([201, 203], $linhas->pluck('autor_codigo')->unique()->sort()->values()->all());

        $this->getJson('/api/relatorio/livros?autor=Ana')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonCount(2, 'data.0.livros');
    }
}
