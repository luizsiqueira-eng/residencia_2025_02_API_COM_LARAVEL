<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyConteudoIdForeignInConteudoLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conteudo_logs', function (Blueprint $table) {
            $table->dropForeign(['conteudo_id']);

            $table->foreign('conteudo_id')
                  ->references('id')
                  ->on('conteudos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conteudo_logs', function (Blueprint $table) {
            $table->dropForeign(['conteudo_id']);

            $table->foreign('conteudo_id')
                  ->references('id')
                  ->on('conteudos')
                  ->onDelete('cascade');
        });
    }
}
