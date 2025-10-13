<?php


namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

class ConteudoStatusEnum extends Enum {
    public const ESCRITO = "escrito";
    public const APROVADO = "aprovado";
    public const REPROVADO = "reprovado";

}