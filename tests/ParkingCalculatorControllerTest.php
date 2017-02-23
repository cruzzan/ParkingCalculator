<?php
namespace Tests;

use App\Http\Controllers\ParkingCalculationController;
use App\Parking;
use TestCase;

class ParkingCalculatorControllerTest extends TestCase
{
    private $controller;

    public function __construct($name = null, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->controller = new ParkingCalculationController();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseToTimestampExceptionOnBadData()
    {
        $this->controller->parseToTimestamp('Anna untz');
    }

    /**
     * Test to see that a valid date can be parsed to a timestamp
     */
    public function testParseDateToTimestamp()
    {
        $this->assertEquals(702604800, $this->controller->parseToTimestamp('1992-04-07'));
    }

    /**
     * Test to see that a timestamp (Basically any int) will return
     * the same value untouched
     */
    public function testParseTimestampToTimestamp()
    {
        $this->assertEquals(702604800, $this->controller->parseToTimestamp(702604800));
    }

    /**
     * Test to see that price is 0 when parking is outside payable time
     */
    public function testParkingOutsidePayableTimeCostZero()
    {
        $parking = new Parking(strtotime('2017-02-22 07:00:00'), strtotime('2017-02-22 09:00:00'));
        $this->assertEquals(0, $this->controller->calculateParkingCost($parking));
    }

    /**
     * Test to see that price is correct for parking in interval but below
     * max
     */
    public function testParkingInIntervalBelowMax()
    {
        $parkinglong = new Parking(strtotime('2017-02-22 09:00:00'), strtotime('2017-02-22 10:00:00'));
        $parkingshort = new Parking(strtotime('2017-02-22 09:00:00'), strtotime('2017-02-22 09:30:00'));

        $this->assertEquals(10, $this->controller->calculateParkingCost($parkinglong));
        $this->assertEquals(5, $this->controller->calculateParkingCost($parkingshort));
    }

    /**
     * Test to see that the max price can be hit
     */
    public function testParkingMaxFee()
    {
        $parkingJustMax = new Parking(strtotime('2017-02-22 09:00:00'), strtotime('2017-02-22 13:00:00'));
        $parkingWayOver = new Parking(strtotime('2017-02-22 09:00:00'), strtotime('2017-03-22 16:00:00'));

        $this->assertEquals(25, $this->controller->calculateParkingCost($parkingJustMax));
        $this->assertEquals(25, $this->controller->calculateParkingCost($parkingWayOver));
    }
}
