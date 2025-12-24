<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProductImagesFixture
 */
class ProductImagesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'product_id' => 1,
                'image_small' => 'Lorem ipsum dolor sit amet',
                'image_medium' => 'Lorem ipsum dolor sit amet',
                'image_large' => 'Lorem ipsum dolor sit amet',
                'mime_type_small' => 'Lorem ipsum dolor sit amet',
                'mime_type_medium' => 'Lorem ipsum dolor sit amet',
                'mime_type_large' => 'Lorem ipsum dolor sit amet',
                'created' => 1761705727,
                'modified' => 1761705727,
            ],
        ];
        parent::init();
    }
}
