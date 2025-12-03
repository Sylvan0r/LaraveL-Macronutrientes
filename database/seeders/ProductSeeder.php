<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use StaticKidz\BedcaAPI\BedcaClient;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $client = new BedcaClient();
        $groups = $client->getFoodGroups()->food ?? [];

        foreach ($groups as $group) {
            $foods = $client->getFoodsInGroup($group->fg_id)->food ?? [];

            foreach ($foods as $food) {
                $detail = $client->getFood($food->f_id)->food ?? $food;
                $nutrients = $detail->foodvalue ?? [];

                $parsed = $this->parseNutrients($nutrients);

                Product::updateOrCreate(
                    ['external_id' => $food->f_id],
                    [
                        'name'        => $food->f_ori_name,
                        'category_id' => $group->fg_id,
                        'calories'            => $parsed['calories'] ?? null,
                        'total_fat'           => $parsed['total_fat'] ?? null,
                        'saturated_fat'       => $parsed['saturated_fat'] ?? null,
                        'colesterol'           => $parsed['colesterol'] ?? null,
                        'polyunsaturated_fat' => $parsed['polyunsaturated_fat'] ?? null,
                        'monounsaturated_fat' => $parsed['monounsaturated_fat'] ?? null,
                        'carbohydrates'       => $parsed['carbohydrates'] ?? null,
                        'fiber'               => $parsed['fiber'] ?? null,
                        'proteins'            => $parsed['proteins'] ?? null,
                    ]
                );
            }
        }
    }

private function parseNutrients(array $nutrients)
{
    $map = [
        '409' => 'calories',
        '410' => 'total_fat',
        '299' => 'saturated_fat',
        '433' => 'colesterol',
        '287' => 'polyunsaturated_fat',
        '282' => 'monounsaturated_fat',
        '53'  => 'carbohydrates',
        '307' => 'fiber',
        '416' => 'proteins'
    ];

    $result = [];
    foreach ($nutrients as $n) {
        $id = $n->c_id ?? null;
        $val = $n->best_location ?? null;

        if (!$id || !isset($map[$id])) continue;

        // Evitar objetos o valores no numéricos
        if (!is_numeric($val)) continue;

        $result[$map[$id]] = (float)$val;
    }

    return $result;
}

}
