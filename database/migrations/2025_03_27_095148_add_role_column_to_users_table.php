<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['Directiva', 'Gerencia', 'Analista'])->nullable()->default('Analista');
        });

        $users = User::all();
        foreach($users as $user){
            if($user->email == "hectorgabrieldm@hotmail.com"){
                $user->role = 'Directiva';
                $user->save();
            }
        }


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role']);
        });
    }
};
