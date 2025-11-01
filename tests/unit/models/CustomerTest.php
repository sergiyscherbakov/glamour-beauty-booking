<?php

namespace tests\unit\models;

use app\models\Customer;
use Codeception\Test\Unit;

class CustomerTest extends Unit
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

    public function testValidation()
    {
        $customer = new Customer();

        // Test required fields
        $this->assertFalse($customer->validate());
        $this->assertArrayHasKey('name', $customer->errors);
        $this->assertArrayHasKey('email', $customer->errors);
        $this->assertArrayHasKey('phone', $customer->errors);

        // Test with valid data
        $customer->name = 'Test Customer';
        $customer->email = 'test@example.com';
        $customer->phone = '+380501234567';

        $this->assertTrue($customer->validate());
    }

    public function testEmailValidation()
    {
        $customer = new Customer();
        $customer->name = 'Test Customer';
        $customer->email = 'invalid-email';
        $customer->phone = '+380501234567';

        $this->assertFalse($customer->validate(['email']));
    }

    public function testNameLength()
    {
        $customer = new Customer();
        $customer->name = str_repeat('a', 101); // Exceeds max length
        $customer->email = 'test@example.com';
        $customer->phone = '+380501234567';

        $this->assertFalse($customer->validate(['name']));
    }

    public function testEmailLength()
    {
        $customer = new Customer();
        $customer->name = 'Test Customer';
        $customer->email = str_repeat('a', 90) . '@example.com'; // Exceeds max length
        $customer->phone = '+380501234567';

        $this->assertFalse($customer->validate(['email']));
    }

    public function testPhoneLength()
    {
        $customer = new Customer();
        $customer->name = 'Test Customer';
        $customer->email = 'test@example.com';
        $customer->phone = str_repeat('1', 21); // Exceeds max length

        $this->assertFalse($customer->validate(['phone']));
    }
}
