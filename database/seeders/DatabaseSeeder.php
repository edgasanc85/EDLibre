<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Administrador Principal del Sistema
        // IMPORTANTE: Editar estos datos antes de ejecutar php artisan db:seed para el primer ingreso
        User::factory()->create([
            'tipo_documento' => 'Cédula Ciudadanía',
            'numero_documento' => '1234567890',
            'name' => 'Administrador Sistema',
            'email' => 'admin@edgasanc.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);

        // Niveles Jerárquicos
        $niveles = [
            ['id' => 1, 'nombre' => 'Comunes', 'activo' => true],
            ['id' => 2, 'nombre' => 'Directivo', 'activo' => true],
            ['id' => 3, 'nombre' => 'Asesor', 'activo' => true],
            ['id' => 4, 'nombre' => 'Profesional', 'activo' => true],
            ['id' => 5, 'nombre' => 'Técnico', 'activo' => true],
            ['id' => 6, 'nombre' => 'Asistencial', 'activo' => true],
        ];

        foreach ($niveles as $nivel) {
            \App\Models\Nivel::firstOrCreate(['id' => $nivel['id']], $nivel);
        }

        // Sembrar Competencias y Conductas de Ley
        $this->call([
            CompetenciaSeeder::class,
            ConductaSeeder::class,
        ]);
    }
}
