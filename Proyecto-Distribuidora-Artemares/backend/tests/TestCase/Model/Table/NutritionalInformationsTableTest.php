<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NutritionalInformationsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NutritionalInformationsTable Test Case
 */
class NutritionalInformationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\NutritionalInformationsTable
     */
    protected $NutritionalInformations;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.NutritionalInformations',
        'app.Products',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('NutritionalInformations') ? [] : ['className' => NutritionalInformationsTable::class];
        $this->NutritionalInformations = $this->getTableLocator()->get('NutritionalInformations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->NutritionalInformations);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\NutritionalInformationsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\NutritionalInformationsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
