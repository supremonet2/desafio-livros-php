<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Livro_Autor', function (Blueprint $table) {
            $table->integer('Livro_Codl');
            $table->integer('Autor_CodAu');

            $table->primary(['Livro_Codl', 'Autor_CodAu']);

            $table->foreign('Livro_Codl')
                ->references('Codl')
                ->on('livros');

            $table->foreign('Autor_CodAu')
                ->references('CodAu')
                ->on('autors');

            $table->index('Autor_CodAu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Livro_Autor');
    }
};
