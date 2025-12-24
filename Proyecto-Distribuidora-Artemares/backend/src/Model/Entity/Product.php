<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $price
 * @property int $stock
 * @property string $unit_quantity
 * @property string $unit
 * @property int $category_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Category $category
 * @property \App\Model\Entity\NutritionalInformation $nutritional_information
 * @property \App\Model\Entity\ProductImage $product_image
 * @property \App\Model\Entity\Order[] $orders
 * @property \App\Model\Entity\Recipe[] $recipes
 */
class Product extends Entity
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
        'name' => true,
        'description' => true,
        'price' => true,
        'stock' => true,
        'unit_quantity' => true,
        'unit' => true,
        'category_id' => true,
        'created' => true,
        'modified' => true,
        'category' => true,
        'nutritional_information' => true,
        'product_image' => true,
        'orders' => true,
        'recipes' => true,
    ];
}
