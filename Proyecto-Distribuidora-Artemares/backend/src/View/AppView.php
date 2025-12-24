<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @link https://book.cakephp.org/5/en/views.html#the-app-view
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like adding helpers.
     *
     * e.g. `$this->addHelper('Html');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        // Templates personalizados del Paginator (Bootstrap)
        $this->Paginator->setTemplates([
            'prevActive'   => '<li class="page-item"><a class="page-link" href="{{url}}">&lt; Anterior</a></li>',
            'prevDisabled' => '<li class="page-item disabled"><span class="page-link">&lt; Anterior</span></li>',

            'nextActive'   => '<li class="page-item"><a class="page-link" href="{{url}}">Siguiente &gt;</a></li>',
            'nextDisabled' => '<li class="page-item disabled"><span class="page-link">Siguiente &gt;</span></li>',

            'number'       => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
            'current'      => '<li class="page-item active"><span class="page-link">{{text}}</span></li>',

            'ellipsis'     => '<li class="page-item disabled"><span class="page-link">…</span></li>',
        ]);
    }
}
