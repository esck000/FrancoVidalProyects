<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // COMPORTAMIENTO ESPECIAL PARA API
        if ($this->request->getParam('prefix') === 'Api') {
            // permitir acceso sin login
            $this->Authentication->allowUnauthenticated(['index']);

            // forzar JSON, no vistas
            $this->viewBuilder()->setClassName('Json');
            $this->disableAutoRender();
        }
    }
}
