<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConteudoLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'conteudo_id',
        'acao',
        'detalhes',
    ];
}
