<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conteudo;
use App\Enums\ConteudoStatusEnum;

class ConteudoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Conteudo::create([
            'papel' => 'redator',
            'conteudo' => 'Este é o primeiro conteúdo de exemplo.',
            'status' => ConteudoStatusEnum::ESCRITO,
        ]);
    }
}
