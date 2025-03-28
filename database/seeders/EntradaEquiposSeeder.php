<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\EntradaEquipos;

class EntradaEquiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlPath = database_path('seeders/data/entradaequipos.sql');

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
