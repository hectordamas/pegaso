<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\{
    UserSeeder, 
    ConsultorSeeder, 
    SaclieSeeder, 
    AtencionClienteSeeder, 
    CalendarioSeeder, 
    EntradaEquiposSeeder, 
    ChatEntradaSeeder,
    SafactSeeder,
    SavendSeeder,
    ChatProyectoSeeder,
    LlamadaSeeder,
};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ConsultorSeeder::class,
            SaclieSeeder::class,
            AtencionClienteSeeder::class,
            CalendarioSeeder::class,
            EntradaEquiposSeeder::class,
            ChatEntradaSeeder::class,
            SavendSeeder::class,
            /// SafactSeeder::class  El archivo safact demasiado grande mejor cargar por medio de phpmyadmin
            ChatProyectoSeeder::class,
            LlamadaSeeder::class
        ]);
    }
}
