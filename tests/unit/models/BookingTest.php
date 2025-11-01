<?php

namespace tests\unit\models;

use app\models\Booking;
use Codeception\Test\Unit;

class BookingTest extends Unit
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
        $booking = new Booking();

        // Test required fields
        $this->assertFalse($booking->validate());
        $this->assertArrayHasKey('user_id', $booking->errors);
        $this->assertArrayHasKey('service_id', $booking->errors);
        $this->assertArrayHasKey('booking_date', $booking->errors);
        $this->assertArrayHasKey('booking_time', $booking->errors);
    }

    public function testStatusDefault()
    {
        $booking = new Booking();
        $booking->user_id = 1;
        $booking->service_id = 1;
        $booking->booking_date = '2025-12-01';
        $booking->booking_time = '10:00:00';
        $booking->validate();

        $this->assertEquals(Booking::STATUS_PENDING, $booking->status);
    }

    public function testStatusValidation()
    {
        $booking = new Booking();
        $booking->user_id = 1;
        $booking->service_id = 1;
        $booking->booking_date = '2025-12-01';
        $booking->booking_time = '10:00:00';
        $booking->status = 'invalid_status';

        $this->assertFalse($booking->validate(['status']));
    }

    public function testValidStatuses()
    {
        $validStatuses = [
            Booking::STATUS_PENDING,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_CANCELLED,
            Booking::STATUS_COMPLETED
        ];

        foreach ($validStatuses as $status) {
            $booking = new Booking();
            $booking->user_id = 1;
            $booking->service_id = 1;
            $booking->booking_date = '2025-12-01';
            $booking->booking_time = '10:00:00';
            $booking->status = $status;

            $this->assertTrue($booking->validate(['status']), "Status {$status} should be valid");
        }
    }

    public function testGetStatuses()
    {
        $statuses = Booking::getStatuses();

        $this->assertIsArray($statuses);
        $this->assertArrayHasKey(Booking::STATUS_PENDING, $statuses);
        $this->assertArrayHasKey(Booking::STATUS_CONFIRMED, $statuses);
        $this->assertArrayHasKey(Booking::STATUS_CANCELLED, $statuses);
        $this->assertArrayHasKey(Booking::STATUS_COMPLETED, $statuses);
    }

    public function testUserIdMustBeInteger()
    {
        $booking = new Booking();
        $booking->user_id = 'not_an_integer';
        $booking->service_id = 1;
        $booking->booking_date = '2025-12-01';
        $booking->booking_time = '10:00:00';

        $this->assertFalse($booking->validate(['user_id']));
    }

    public function testServiceIdMustBeInteger()
    {
        $booking = new Booking();
        $booking->user_id = 1;
        $booking->service_id = 'not_an_integer';
        $booking->booking_date = '2025-12-01';
        $booking->booking_time = '10:00:00';

        $this->assertFalse($booking->validate(['service_id']));
    }
}
