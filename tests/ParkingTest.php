<?php
namespace Tests;

use App\Parking;
use TestCase;
use InvalidArgumentException;

class ParkingTest extends TestCase
{
    /**
     * Test that the the constructor returns a healthy instance
     */
    public function testInstanceOf()
    {
        $instance = new Parking(strtotime('2017-02-22'), strtotime('2017-02-23'));
        $this->assertInstanceOf('App\Parking', $instance);
    }

    /**
     * Test that an exception is thrown when an impossible interval is
     * supplied.
     *
     * @expectedException InvalidArgumentException
     */
    public function testExceptionOnBadDataToConstructor()
    {
        new Parking(strtotime('2017-02-22'), strtotime('1999-09-09'));
    }
}
