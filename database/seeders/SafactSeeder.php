<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Safact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SafactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     /// El archivo safact demasiado grande mejor cargar por medio de phpmyadmin
    public function run(): void
    {
        $sqlPath = database_path('seeders/data/safact.sql');

        // Verifica si el archivo existe
        if (File::exists($sqlPath)) {
            // Obtiene el contenido del archivo
            $sql = File::get($sqlPath);

            // Ejecuta el SQL en la base de datos
            DB::unprepared($sql);

            $this->command->info('Se ha importado el SQL correctamente.');
        } else {
            $this->command->error('No se encontró el archivo SQL.');
        }
    }
}
