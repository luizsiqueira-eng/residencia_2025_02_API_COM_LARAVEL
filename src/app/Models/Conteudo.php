<?php

namespace App\Models;

use App\Enums\ConteudoStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;

class Conteudo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "papel",
        "conteudo",
        "status",
        "motivo_reprovacao"
    ];

    protected $casts = [
        'id' => 'integer',
    ];
    
    protected $attributes = [
        'status'=> ConteudoStatusEnum::ESCRITO,
    ];

    protected static function booted()
    {
        static::deleting(function ($conteudo) {
            if ($conteudo->status === ConteudoStatusEnum::APROVADO) {
                return false;
            }
        });
    }

     public function aprovar() {

        if ($this->status !== ConteudoStatusEnum::ESCRITO) {
            
            throw new Exception('Ação de aprovar não permitida para o estado atual.');
        }

        $this->status = ConteudoStatusEnum::APROVADO;
        $this->motivo_reprovacao = null; 
    
        return $this->save();
     }

    public function reprovar(string $motivo_reprovacao) {

        if ($this->status !== ConteudoStatusEnum::ESCRITO) {
            throw new Exception('Ação de reprovar não permitida para o estado atual.');
        }
        if (empty($motivo_reprovacao)) {
            throw new Exception('O motivo da reprovação é obrigatório.');
        }
        
        $this->status = ConteudoStatusEnum::REPROVADO;
        $this->motivo_reprovacao = $motivo_reprovacao;

        return $this->save();
    }

    public function statusEscritoAposEditarConteudoReprovado () {
        if ($this->status !== ConteudoStatusEnum::REPROVADO) {
            throw new Exception('O Conteúdo não foi reprovado, ação não disponível.');
        }

        $this->status = ConteudoStatusEnum::ESCRITO;
        $this->motivo_reprovacao = null;

        return $this->save();
    }

}