<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('codusuario')->unsigned()->unique();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            $table->integer('codtipodoc')->index();
            $table->string('documento')->index();
            $table->string('telefonocel')->nullable();
            $table->date('fecha')->nullable();
            $table->boolean('master')->default(false);
            $table->boolean('inactivo')->default(false);
            $table->longText('photo')->nullable();
        });

        $user = new \App\Models\User();
        $user->codusuario = 1000;
        $user->codtipodoc = 1;
        $user->documento = '27439674';
        $user->name = "Héctor Damas";
        $user->email = 'hectorgabrieldm@hotmail.com';
        $user->telefonocel = '04241930033';
        $user->fecha = now();
        $user->master = true;
        $user->inactivo = false;
        $user->photo = null;
        $user->password  = bcrypt('123456789'); // Convertir SHA-1 a bcrypt
        $user->role = "Directiva";
        $user->save();

        $user->codusuario = $user->id;
        $user->save();


        $consultor = new \App\Models\Consultor();
        $consultor->codconsultor = $user->id;
        $consultor->codusuario = $user->id;
        $consultor->nombre = $user->name;
        $consultor->inactivo = false;
        $consultor->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
