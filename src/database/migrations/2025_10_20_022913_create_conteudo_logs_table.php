<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConteudoLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conteudo_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conteudo_id')->constrained()->onDelete('cascade');
            $table->string('acao'); 
            $table->text('detalhes')->nullable();    


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
        Schema::dropIfExists('conteudo_logs');
    }
}
