<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LicenciasAActivar;
use App\Models\Saclie;

class LicenciasAActivarSeeder extends Seeder
{
    public function run(): void
    {
        $codclies = Saclie::pluck('codclie')->toArray();

        $licencias = [
            [
                'codclie' => $codclies[4],
                'descripcion' => 'Long prevent sound represent series.',
                'licencias' => 3,
                'fechadepago' => '2025-03-09',
                'monto' => 313.14,
                'activada' => false,
                'pagada' => true,
            ],
            [
                'codclie' => $codclies[2],
                'descripcion' => 'Factor set economic.',
                'licencias' => 5,
                'fechadepago' => '2025-03-17',
                'monto' => 395.34,
                'activada' => true,
                'pagada' => true,
            ],
            [
                'codclie' => $codclies[2],
                'descripcion' => 'Other gas.',
                'licencias' => 3,
                'fechadepago' => '2025-03-29',
                'monto' => 94.33,
                'activada' => true,
                'pagada' => false,
            ],
            [
                'codclie' => $codclies[8],
                'descripcion' => 'Generation yes campaign reality.',
                'licencias' => 4,
                'fechadepago' => '2025-03-17',
                'monto' => 336.27,
                'activada' => false,
                'pagada' => false,
            ],
            [
                'codclie' => $codclies[3],
                'descripcion' => 'Take game investment fine baby.',
                'licencias' => 3,
                'fechadepago' => '2025-03-05',
                'monto' => 96.88,
                'activada' => true,
                'pagada' => true,
            ],
            [
                'codclie' => $codclies[9],
                'descripcion' => 'Wide bag yourself.',
                'licencias' => 1,
                'fechadepago' => '2025-03-23',
                'monto' => 252.76,
                'activada' => false,
                'pagada' => false,
            ],
            [
                'codclie' => $codclies[5],
                'descripcion' => 'Lay door account.',
                'licencias' => 4,
                'fechadepago' => '2025-03-29',
                'monto' => 96.79,
                'activada' => true,
                'pagada' => false,
            ],
            [
                'codclie' => $codclies[5],
                'descripcion' => 'Movement pressure democratic.',
                'licencias' => 4,
                'fechadepago' => '2025-03-08',
                'monto' => 37.84,
                'activada' => true,
                'pagada' => true,
            ],
            [
                'codclie' => $codclies[1],
                'descripcion' => 'Great total.',
                'licencias' => 5,
                'fechadepago' => '2025-03-14',
                'monto' => 365.18,
                'activada' => true,
                'pagada' => true,
            ],
            [
                'codclie' => $codclies[0],
                'descripcion' => 'Though seem course.',
                'licencias' => 3,
                'fechadepago' => '2025-03-19',
                'monto' => 82.8,
                'activada' => true,
                'pagada' => true,
            ],
        ];

        foreach ($licencias as $data) {
            LicenciasAActivar::create($data);
        }

        echo "Seeder de licencias a activar ejecutado con éxito.\n";
    }
}
