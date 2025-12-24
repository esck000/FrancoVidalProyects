<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

class RecipesController extends AppController
{
    public function index()
    {
        $this->request->allowMethod(['get']);

        $recipes = $this->Recipes
            ->find()
            ->contain(['Products', 'RecipeImages'])
            ->order(['Recipes.name' => 'ASC'])
            ->all();

        $data = [];

        foreach ($recipes as $recipe) {
            $image = $recipe->recipe_image ?? null;

            $data[] = [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'description' => $recipe->description,
                'ingredients' => $recipe->ingredients,

                // imágenes (mismo patrón que products)
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

                // productos relacionados
                'products' => array_map(
                    fn($p) => [
                        'id' => $p->id,
                        'name' => $p->name,
                    ],
                    $recipe->products ?? []
                ),
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'recipes' => $data
            ], JSON_UNESCAPED_UNICODE));
    }

    /**
     * Convierte imagen BLOB a base64
     */
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
