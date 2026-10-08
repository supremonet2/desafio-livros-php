<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $table = 'autors';
    protected $primaryKey = 'CodAu';
    public $incrementing = false;

    protected $fillable = [
        'Nome',
        'CodAu',
    ];

    public function livros()
    {
        return $this->belongsToMany(
            Livro::class,
            'Livro_Autor',
            'Autor_CodAu',
            'Livro_Codl'
        );
    }
}
