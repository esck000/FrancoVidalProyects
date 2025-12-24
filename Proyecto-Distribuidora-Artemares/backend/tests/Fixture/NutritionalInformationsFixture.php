<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * NutritionalInformationsFixture
 */
class NutritionalInformationsFixture extends TestFixture
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
                'measurement' => 'Lorem ipsum dolor sit amet',
                'calories' => 1.5,
                'protein' => 1.5,
                'total_fat' => 1.5,
                'carbohydrates' => 1.5,
                'sodium' => 1.5,
                'cholesterol' => 1.5,
                'created' => 1761705705,
                'modified' => 1761705705,
            ],
        ];
        parent::init();
    }
}
