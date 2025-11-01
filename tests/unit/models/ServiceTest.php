<?php

namespace tests\unit\models;

use app\models\Service;
use Codeception\Test\Unit;

class ServiceTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // Test validation rules
    public function testValidation()
    {
        $service = new Service();

        // Test required fields
        $this->assertFalse($service->validate());
        $this->assertArrayHasKey('name', $service->errors);
        $this->assertArrayHasKey('duration', $service->errors);
        $this->assertArrayHasKey('price', $service->errors);

        // Test with valid data
        $service->name = 'Test Service';
        $service->description = 'Test description';
        $service->duration = 60;
        $service->price = 100.50;

        $this->assertTrue($service->validate());
    }

    public function testNameLength()
    {
        $service = new Service();
        $service->name = str_repeat('a', 101); // Exceeds max length of 100
        $service->duration = 60;
        $service->price = 100;

        $this->assertFalse($service->validate(['name']));
    }

    public function testDurationIsInteger()
    {
        $service = new Service();
        $service->name = 'Test Service';
        $service->duration = 'invalid';
        $service->price = 100;

        $this->assertFalse($service->validate(['duration']));
    }

    public function testPriceIsNumeric()
    {
        $service = new Service();
        $service->name = 'Test Service';
        $service->duration = 60;
        $service->price = 'invalid';

        $this->assertFalse($service->validate(['price']));
    }

    public function testIsActiveDefault()
    {
        $service = new Service();
        $service->name = 'Test Service';
        $service->duration = 60;
        $service->price = 100;
        $service->validate();

        $this->assertEquals(1, $service->is_active);
    }

    public function testFindActive()
    {
        // This would require database setup
        // Just testing that the method exists and returns ActiveQuery
        $query = Service::findActive();
        $this->assertInstanceOf('yii\db\ActiveQuery', $query);
    }
}
