<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Savend;

class SavendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ejecuta el SQL en la base de datos
        DB::unprepared("INSERT INTO `savend` (`id`, `serial`, `codvend`, `descrip`, `codubic`, `activo`) VALUES
        (1, 'ENTP211521261801472', '01', 'CARLOS LOPEZ', NULL, 1),
        (2, 'ENTP211521261801472', '02', 'JULIO SANCHEZ', NULL, 1),
        (3, 'ENTP211521261801472', '05', 'NEOMAR RAVELO', NULL, 1),
        (4, 'ENTP211521261801472', '06', 'RAFAEL VIVENZIO', NULL, 1),
        (5, 'ENTP211521261801472', '07', 'F. ESPOSITO', NULL, 1),
        (6, 'ENTP211521261801472', '08', 'JESUS RODRIGUEZ', NULL, 1),
        (7, 'ENTP211521261801472', '09', 'AURA ROMERO', NULL, 1),
        (8, 'ENTP211521261801472', '10', 'VICTOR LEMUS', NULL, 1),
        (9, 'ENTP211521261801472', '11', 'KRISTIAN PADRON', NULL, 1),
        (10, 'ENTP211521261801472', '12', 'ZINAIS SALAS', NULL, 1),
        (11, 'ENTP211521261801472', '15', 'ROBERT APONTE', NULL, 1),
        (12, 'ENTP211521261801472', '16', 'JULIO FRANCO', NULL, 1),
        (13, 'ENTP211521261801472', '17', 'JHENSON PARRA', NULL, 1),
        (14, 'ENTP211521261801472', '18', 'DANIEL SOUSA', NULL, 1),
        (15, 'ENTP211521261801472', '23', 'KLEIVYN PIMENTEL', NULL, 1),
        (16, 'ENTP211521261801472', '24', 'CARLOS BARON', NULL, 1),
        (17, 'ENTP211521261801472', '27', 'ALIDA CARABALLO', NULL, 1),
        (18, 'ENTP211521261801472', '28', 'Gerald David Rauseo Bueno', NULL, 1),
        (19, 'ENTP211521261801472', '29', 'ALEJANDRA RIVAS', NULL, 0),
        (20, 'ENTP211521261801472', '30', 'KISMET RONDON', NULL, 0),
        (21, 'ENTP211521261801472', '31', 'DULCE SUAREZ', NULL, 0),
        (22, 'ENTP211521261801472', 'DSW', 'TIENDA VIRTUAL', NULL, 1),
        (23, 'ENTP211521261801472', '32', 'ANYOELY DIAZ', NULL, 0),
        (24, 'ENTP211521261801472', '33', 'Dayana Velázquez', NULL, 1),
        (25, 'ENTP211521261801472', '34', 'Gleber Rivas', NULL, 1);");

        $this->command->info('Se ha importado el SQL correctamente.');

    }
}
