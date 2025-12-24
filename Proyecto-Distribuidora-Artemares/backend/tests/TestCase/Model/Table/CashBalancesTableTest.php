<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CashBalancesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CashBalancesTable Test Case
 */
class CashBalancesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CashBalancesTable
     */
    protected $CashBalances;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.CashBalances',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('CashBalances') ? [] : ['className' => CashBalancesTable::class];
        $this->CashBalances = $this->getTableLocator()->get('CashBalances', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->CashBalances);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\CashBalancesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\CashBalancesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
