<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NutritionalInformation Entity
 *
 * @property int $id
 * @property int $product_id
 * @property string|null $measurement
 * @property string|null $calories
 * @property string|null $protein
 * @property string|null $total_fat
 * @property string|null $carbohydrates
 * @property string|null $sodium
 * @property string|null $cholesterol
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Product $product
 */
class NutritionalInformation extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'product_id' => true,
        'measurement' => true,
        'calories' => true,
        'protein' => true,
        'total_fat' => true,
        'carbohydrates' => true,
        'sodium' => true,
        'cholesterol' => true,
        'created' => true,
        'modified' => true,
        'product' => true,
    ];
}
