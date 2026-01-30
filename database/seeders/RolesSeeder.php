<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Permisos para productos
        Permission::create(['name' => 'crear productos']);
        Permission::create(['name' => 'editar productos']);
        Permission::create(['name' => 'ver productos']);
        Permission::create(['name' => 'eliminar productos']);
        Permission::create(['name' => 'eliminar platos']);
        Permission::create(['name' => 'eliminar menus']);

        // Permisos para otras entidades similares ...
        // Permission::create(['name' => 'crear platos']);

        // Crear roles
        $admin = Role::create(['name' => 'admin']);
        $usuario = Role::create(['name' => 'usuario']);

        // Asignar permisos al admin (todo lo que hay)
        $admin->syncPermissions(Permission::all());

        // Al usuario NORMAL le damos todos menos eliminar productos
        $usuario->syncPermissions([
            'crear productos',
            'editar productos',
            'ver productos',
            // (otros permisos que conciernan sin delete)
        ]);
    }
}