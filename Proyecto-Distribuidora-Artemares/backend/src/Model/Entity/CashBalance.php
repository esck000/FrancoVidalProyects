<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CashBalance Entity
 *
 * @property int $id
 * @property string $expected_amount
 * @property string $actual_amount
 * @property string $difference
 * @property string|null $description
 * @property \Cake\I18n\Date $balance_date
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 */
class CashBalance extends Entity
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
        'expected_amount' => true,
        'actual_amount' => true,
        'difference' => true,
        'description' => true,
        'balance_date' => true,
        'created' => true,
        'modified' => true,
        'status' =>true,
    ];
}
