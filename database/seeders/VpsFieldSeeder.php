<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VpsField;

class VpsFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            // WINDOWS
            [
                'key' => 'windows_user',
                'label' => 'Usuario Windows',
                'type' => 'text',
                'group' => 'windows',
                'encrypted' => false,
            ],
            [
                'key' => 'windows_password',
                'label' => 'Contraseña Windows',
                'type' => 'password',
                'group' => 'windows',
                'encrypted' => true,
            ],
            [
                'key' => 'windows_ip',
                'label' => 'IP del VPS',
                'type' => 'text',
                'group' => 'windows',
                'encrypted' => false,
            ],

            // ANYDESK
            [
                'key' => 'anydesk_id',
                'label' => 'ID AnyDesk',
                'type' => 'text',
                'group' => 'anydesk',
                'encrypted' => false,
            ],
            [
                'key' => 'anydesk_password',
                'label' => 'Contraseña AnyDesk',
                'type' => 'password',
                'group' => 'anydesk',
                'encrypted' => true,
            ],

            // SQL
            [
                'key' => 'sql_user',
                'label' => 'Usuario SQL',
                'type' => 'text',
                'group' => 'sql',
                'encrypted' => false,
            ],
            [
                'key' => 'sql_password',
                'label' => 'Contraseña SQL',
                'type' => 'password',
                'group' => 'sql',
                'encrypted' => true,
            ],
            [
                'key' => 'sql_host',
                'label' => 'Servidor SQL',
                'type' => 'text',
                'group' => 'sql',
                'encrypted' => false,
            ],
        ];

        foreach ($fields as $field) {
            VpsField::updateOrCreate(
                ['key' => $field['key']],
                $field
            );
        }
    }
}
