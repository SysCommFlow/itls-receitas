<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToReceitasTable extends Migration
{
    public function up()
    {
        Schema::table('receitas', function (Blueprint $table) {
            $table->softDeletes(); // Adiciona a coluna deleted_at
        });
    }

    public function down()
    {
        Schema::table('receitas', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Remove a coluna deleted_at
        });
    }
}
