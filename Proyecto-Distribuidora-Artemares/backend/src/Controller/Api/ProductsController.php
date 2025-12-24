<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

class ProductsController extends AppController
{
    public function index()
    {
        $this->request->allowMethod(['get']);

        $products = $this->Products
            ->find()
            ->contain([
                'Categories',
                'ProductImages',
                'NutritionalInformations',
                'Recipes'
            ])
            ->order(['Products.name' => 'ASC'])
            ->all();

        $data = [];

        foreach ($products as $product) {

            $image = $product->product_image ?? null;
            $nutrition = $product->nutritional_information ?? null;

            $data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => (int)$product->price,
                'unit' => $product->unit,
                'unit_quantity' => (float)$product->unit_quantity,

                // Categoría
                'category' => $product->category ? [
                    'name' => $product->category->name,
                ] : null,

                // Imágenes
                'images' => $image ? [
                    'small' => $this->imageBase64(
                        $image->image_small,
                        $image->mime_type_small
                    ),
                    'medium' => $this->imageBase64(
                        $image->image_medium,
                        $image->mime_type_medium
                    ),
                    'large' => $this->imageBase64(
                        $image->image_large,
                        $image->mime_type_large
                    ),
                ] : null,

                // Info nutricional
                'nutrition' => $nutrition ? [
                    'calories' => $nutrition->calories,
                    'protein' => $nutrition->protein,
                    'carbs' => $nutrition->carbohydrates,
                    'fat' => $nutrition->total_fat,
                    'sodium' => $nutrition->sodium,
                ] : null,

                // Recetas relacionadas (solo nombres)
                'recipes' => $product->recipes
                    ? array_map(
                        fn($r) => [
                            'id' => $r->id,
                            'name' => $r->name
                        ],
                        $product->recipes
                    )
                    : [],
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'products' => $data
            ], JSON_UNESCAPED_UNICODE));
    }

    private function imageBase64($binary, ?string $mime): ?string
    {
        if (!$binary || !$mime) {
            return null;
        }

        if (is_resource($binary)) {
            $binary = stream_get_contents($binary);
        }

        if (!is_string($binary)) {
            return null;
        }

        return 'data:' . $mime . ';base64,' . base64_encode($binary);
    }
}
