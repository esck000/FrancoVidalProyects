<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Recipes Controller
 *
 * @property \App\Model\Table\RecipesTable $Recipes
 */
class RecipesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Query base
        $query = $this->Recipes->find();

        // Búsqueda por nombre
        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $query->where(['Recipes.name ILIKE' => "%$search%"]);
        }

        // Paginación + ordenamiento dinámico habilitado
        $this->paginate = [
            'limit' => 20,
            'order' => ['name' => 'asc'], // orden por defecto
            /*'sortableFields' => ['id', 'name', 'modified', 'created']*/
        ];

        // Ejecutar paginación
        $recipes = $this->paginate($query);

        $this->set(compact('recipes', 'search'));
    }


    /**
     * View method
     *
     * @param string|null $id Recipe id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $recipe = $this->Recipes->get($id, contain: ['Products', 'RecipeImages']);
        $this->set(compact('recipe'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $recipe = $this->Recipes->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            #$recipe = $this->Recipes->patchEntity($recipe, $data);
            $recipe = $this->Recipes->patchEntity($recipe, $data, [
                'associated' => ['Products', 'RecipeImages']
            ]);
            // Procesar imagen
            $imageFile = $data['image_file'] ?? null;

            if ($imageFile && $imageFile->getError() === UPLOAD_ERR_OK) {
                $imageContent = file_get_contents($imageFile->getStream()->getMetadata('uri'));
                $mimeType = $imageFile->getClientMediaType();

                $imageSmall = $this->resizeImage($imageContent, 100, 100);
                $imageMedium = $this->resizeImage($imageContent, 300, 300);
                $imageLarge = $this->resizeImage($imageContent, 800, 800);

                $recipeImage = $this->Recipes->RecipeImages->newEntity([
                    'image_small' => $imageSmall,
                    'image_medium' => $imageMedium,
                    'image_large' => $imageLarge,
                    'mime_type_small' => $mimeType,
                    'mime_type_medium' => $mimeType,
                    'mime_type_large' => $mimeType,
                ]);

                $recipe->recipe_image = $recipeImage;
            }

            if ($this->Recipes->save($recipe, ['associated' => ['RecipeImages', 'Products']])) {
                $this->Flash->success(__('La receta ha sido guardada con éxito.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('La receta no pudo ser guardada. Intente nuevamente.'));
        }

        $products = $this->Recipes->Products->find('list', limit: 200)->all();
        $this->set(compact('recipe', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Recipe id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
public function edit($id = null)
{
    $recipe = $this->Recipes->get($id, contain: [
        'Products',
        'RecipeImages'
    ]);

    if ($this->request->is(['patch', 'post', 'put'])) {
        $data = $this->request->getData();

        //  NO incluir RecipeImages aquí
        $recipe = $this->Recipes->patchEntity($recipe, $data, [
            'associated' => ['Products']
        ]);

        $imageFile = $data['image_file'] ?? null;

        // -------------------------
        //  REEMPLAZAR / CREAR IMAGEN
        // -------------------------
        if ($imageFile && $imageFile->getError() === UPLOAD_ERR_OK) {

            $imageContent = $imageFile->getStream()->getContents();
            $mimeType = $imageFile->getClientMediaType();

            $imageSmall  = $this->resizeImage($imageContent, 100, 100);
            $imageMedium = $this->resizeImage($imageContent, 300, 300);
            $imageLarge  = $this->resizeImage($imageContent, 800, 800);

            if ($recipe->recipe_image) {
                // actualizar existente
                $recipe->recipe_image->image_small = $imageSmall;
                $recipe->recipe_image->image_medium = $imageMedium;
                $recipe->recipe_image->image_large = $imageLarge;
                $recipe->recipe_image->mime_type_small = $mimeType;
                $recipe->recipe_image->mime_type_medium = $mimeType;
                $recipe->recipe_image->mime_type_large = $mimeType;

                //  CLAVE: forzar persistencia
                $this->Recipes->RecipeImages->save($recipe->recipe_image);

            } else {
                // crear nueva
                $recipe->recipe_image = $this->Recipes->RecipeImages->newEntity([
                    'recipe_id' => $recipe->id,
                    'image_small' => $imageSmall,
                    'image_medium' => $imageMedium,
                    'image_large' => $imageLarge,
                    'mime_type_small' => $mimeType,
                    'mime_type_medium' => $mimeType,
                    'mime_type_large' => $mimeType,
                ]);
            }
        }

        // -------------------------
        // ELIMINAR IMAGEN (solo si NO hay imagen nueva)
        // -------------------------
        if (
            (!$imageFile || $imageFile->getError() !== UPLOAD_ERR_OK)
            && !empty($data['remove_image'])
            && $recipe->recipe_image
        ) {
            $this->Recipes->RecipeImages->delete($recipe->recipe_image);
            $recipe->recipe_image = null;
        }

        // -------------------------
        // GUARDAR RECETA
        // -------------------------
        if ($this->Recipes->save($recipe, [
            'associated' => ['Products', 'RecipeImages']
        ])) {
            $this->Flash->success(__('La receta ha sido actualizada con éxito.'));
            return $this->redirect(['action' => 'index']);
        }

        $this->Flash->error(__('La receta no pudo ser actualizada. Intente nuevamente.'));
    }

    $products = $this->Recipes->Products->find('list', limit: 200)->all();
    $this->set(compact('recipe', 'products'));
}

    #funcion para guardar imagenes con sus respectivas dimensiones
    private function resizeImage(string $binaryData, int $width, int $height): string
    {
        $src = imagecreatefromstring($binaryData);
        $origWidth = imagesx($src);
        $origHeight = imagesy($src);

        $ratio = min($width / $origWidth, $height / $origHeight);
        $newWidth = (int)($origWidth * $ratio);
        $newHeight = (int)($origHeight * $ratio);

        $dst = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        ob_start();
        imagepng($dst);
        $resizedData = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return $resizedData;
    }

    /**
     * Delete method
     *
     * @param string|null $id Recipe id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $recipe = $this->Recipes->get($id);
        if ($this->Recipes->delete($recipe)) {
            $this->Flash->success(__('The recipe has been deleted.'));
        } else {
            $this->Flash->error(__('The recipe could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
