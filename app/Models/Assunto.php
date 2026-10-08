<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assunto extends Model
{
    protected $table = 'assuntos';
    protected $primaryKey = 'CodAs';
    public $incrementing = false;

    protected $fillable = [
        'Descricao',
        'CodAs',
    ];

    public function livros()
    {
        return $this->belongsToMany(
            Livro::class,
            'Livro_Assunto',
            'Assunto_CodAs',
            'Livro_Codl'
        );
    }
}
