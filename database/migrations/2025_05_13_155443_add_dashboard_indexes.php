<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::table('atencioncliente', function (Blueprint $table) {
            $table->index('codestatus');
            $table->index('codconsultor');
        });

        Schema::table('entradaequipos', function (Blueprint $table) {
            $table->index('fecha');
            $table->index('codestatus');
        });
    }


    public function down(): void
    {
        Schema::table('atencioncliente', function (Blueprint $table) {
            $table->dropIndex(['codestatus']);
            $table->dropIndex(['codconsultor']);
        });

        Schema::table('entradaequipos', function (Blueprint $table) {
            $table->dropIndex(['fecha']);
            $table->dropIndex(['codestatus']);
        });
    }
};
