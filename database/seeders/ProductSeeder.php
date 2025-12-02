<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use StaticKidz\BedcaAPI\BedcaClient;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $client = new BedcaClient();

        // Obtener grupos
        $groups = $client->getFoodGroups()->food ?? [];

        foreach ($groups as $group) {

            // Obtener alimentos del grupo
            $foods = $client->getFoodsInGroup($group->fg_id)->food ?? [];

            foreach ($foods as $food) {

                // Obtener detalle completo
                $data = $client->getFood($food->f_id);

                // Normalizar el nodo "food"
                $detail = $data->food ?? $data;

                // OBTENER NUTRIENTES DERECHO
                $nutrients = $this->getNutrientList($detail);

                // Convertir a nutriente:valor
                $parsed = $this->parseNutrients($nutrients);

                // GUARDADO
                Product::updateOrCreate(
                    ['id' => (int) $food->f_id],
                    [
                        'name'                   => $detail->f_ori_name ?? 'Desconocido',
                        'calories'               => $parsed['energy'] ?? null,
                        'total_fat'              => $parsed['fat'] ?? null,
                        'saturated_fat'          => $parsed['saturated_fat'] ?? null,
                        'trans_fat'              => $parsed['trans_fat'] ?? null,
                        'polyunsaturated_fat'    => $parsed['poly_fat'] ?? null,
                        'monounsaturated_fat'    => $parsed['mono_fat'] ?? null,
                        'carbohydrates'          => $parsed['carbs'] ?? null,
                        'sugars'                 => $parsed['sugars'] ?? null,
                        'fiber'                  => $parsed['fiber'] ?? null,
                        'proteins'               => $parsed['protein'] ?? null,
                        'category_id'            => (int) $group->fg_id,
                    ]
                );
            }
        }
    }


    /**
     * Localiza la lista de nutrientes en todas las posibles estructuras de BEDCA.
     */
    private function getNutrientList($detail)
    {
        // OPCIÓN 1 → food->nutrients->nutrient
        if (isset($detail->nutrients->nutrient)) {
            return $detail->nutrients->nutrient;
        }

        if (isset($detail->food->nutrients->nutrient)) {
            return $detail->food->nutrients->nutrient;
        }

        // OPCIÓN 2 → food->items->item[n]->nutrients->nutrient
        if (isset($detail->food->items->item[0]->nutrients->nutrient)) {
            return $detail->food->items->item[0]->nutrients->nutrient;
        }

        // OPCIÓN 3 → nutrients a nivel root
        if (isset($detail->nutrient)) {
            return $detail->nutrient;
        }

        return []; // ningún nutriente encontrado
    }


    /**
     * Mapea códigos BEDCA → valores
     */
    private function parseNutrients($nutrients)
    {
        $map = [
            '208' => 'energy',
            '204' => 'fat',
            '606' => 'saturated_fat',
            '605' => 'trans_fat',
            '646' => 'poly_fat',
            '645' => 'mono_fat',
            '205' => 'carbs',
            '269' => 'sugars',
            '291' => 'fiber',
            '203' => 'protein',
        ];

        $result = [];

        foreach ($nutrients as $n) {
            $id  = $n->nutrient_id ?? null;
            $val = $n->nutrient_value ?? null;

            if (!$id || !isset($map[$id])) {
                continue;
            }

            if (is_numeric($val)) {
                $val = (float) $val;
            }

            $result[$map[$id]] = $val;
        }

        return $result;
    }
}
