<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('menus')->insert([
            'position' => 28,
            'codmenu' => 148,
            'nombre' => 'VPS',
            'id' => 'mnuvps',
            'ruta' => 'vps',
            'logo' => 'dns',
            'logo_boostrap' => 'fas fa-server',
            'collapse' => 0,
            'master' => 0,
            'registra' => 1,
            'vertodo' => 1,
            'inactivo' => 0,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
