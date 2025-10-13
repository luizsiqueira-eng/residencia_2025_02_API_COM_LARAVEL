<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ConteudoStatusEnum;

class CreateConteudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $reflection = new ReflectionClass(ConteudoStatusEnum::class);
        $statusValues = array_values($reflection->getConstants());

        Schema::create('conteudos', function (Blueprint $table) use ($statusValues) {
            $table->id();

            $table->string('papel');
            $table->text('conteudo');
            $table->enum('status', $statusValues)->default(ConteudoStatusEnum::ESCRITO);
            $table->string('motivo_reprovacao')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conteudos');
    }
}
