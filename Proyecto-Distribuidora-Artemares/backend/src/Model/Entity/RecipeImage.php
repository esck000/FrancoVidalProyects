<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RecipeImage Entity
 *
 * @property int $id
 * @property int $recipe_id
 * @property string|resource $image_small
 * @property string|resource $image_medium
 * @property string|resource $image_large
 * @property string $mime_type_small
 * @property string $mime_type_medium
 * @property string $mime_type_large
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Recipe $recipe
 */
class RecipeImage extends Entity
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
        'recipe_id' => true,
        'image_small' => true,
        'image_medium' => true,
        'image_large' => true,
        'mime_type_small' => true,
        'mime_type_medium' => true,
        'mime_type_large' => true,
        'created' => true,
        'modified' => true,
        'recipe' => true,
    ];
}
