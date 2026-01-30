<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesSeeder::class);

        // Crear usuario administrador
        $admin = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $user = User::factory()->create([
            'name' => 'User Test',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        // Asignarle el rol admin
        $admin->assignRole('admin');      
        $user->assignRole('usuario');      

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
