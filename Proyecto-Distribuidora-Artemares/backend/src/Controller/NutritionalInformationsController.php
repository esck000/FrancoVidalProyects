<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * NutritionalInformations Controller
 *
 * @property \App\Model\Table\NutritionalInformationsTable $NutritionalInformations
 */
class NutritionalInformationsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->NutritionalInformations->find()
            ->contain(['Products'])
            ->order(['NutritionalInformations.modified' => 'DESC']);

        // --- Búsqueda por nombre de producto ---
        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $query->matching('Products', function ($q) use ($search) {
                return $q->where(['Products.name ILIKE' => "%$search%"]);
            });
        }
        $nutritionalInformations = $this->paginate($query);

        $this->set(compact('nutritionalInformations', 'search'));
    }

    /**
     * View method
     *
     * @param string|null $id Nutritional Information id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $nutritionalInformation = $this->NutritionalInformations->get($id, contain: ['Products']);
        $this->set(compact('nutritionalInformation'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $nutritionalInformation = $this->NutritionalInformations->newEmptyEntity();
        if ($this->request->is('post')) {
            $nutritionalInformation = $this->NutritionalInformations->patchEntity($nutritionalInformation, $this->request->getData());
            if ($this->NutritionalInformations->save($nutritionalInformation)) {
                $this->Flash->success(__('The nutritional information has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The nutritional information could not be saved. Please, try again.'));
        }
        $products = $this->NutritionalInformations->Products->find('list', limit: 200)->all();
        $this->set(compact('nutritionalInformation', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Nutritional Information id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $nutritionalInformation = $this->NutritionalInformations->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $nutritionalInformation = $this->NutritionalInformations->patchEntity($nutritionalInformation, $this->request->getData());
            if ($this->NutritionalInformations->save($nutritionalInformation)) {
                $this->Flash->success(__('The nutritional information has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The nutritional information could not be saved. Please, try again.'));
        }
        $products = $this->NutritionalInformations->Products->find('list', limit: 200)->all();
        $this->set(compact('nutritionalInformation', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Nutritional Information id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $nutritionalInformation = $this->NutritionalInformations->get($id);
        if ($this->NutritionalInformations->delete($nutritionalInformation)) {
            $this->Flash->success(__('The nutritional information has been deleted.'));
        } else {
            $this->Flash->error(__('The nutritional information could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
