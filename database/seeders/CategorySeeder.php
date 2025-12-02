<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use StaticKidz\BedcaAPI\BedcaClient;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $client = new BedcaClient();

        // Estructura real de la API:
        $groups = $client->getFoodGroups()->food;

        foreach ($groups as $group) {
            Category::updateOrCreate(
                ['id' => (int) $group->fg_id],
                ['name' => $group->fg_ori_name] // Nombre español
            );
        }
    }
}
