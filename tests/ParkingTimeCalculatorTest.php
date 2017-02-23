<?php
namespace Tests;

use TestCase;
use App\ParkingTimeCalculator;
use App\Parking;

class ParkingTimeCalculatorTest extends TestCase
{
    /**
     * Test that an exception is thrown when the payable interval is zero
     *
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionOnSameStartAndEndToGetPayableMinutes(){
        $calculator = new ParkingTimeCalculator();
        $parking = new Parking(0, 1);
        $calculator->getPayableMinutes($parking, '16:00', '16:00');
    }

    /**
     * Test that an exception is thrown when the payable interval is negative
     *
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionOnStartGreaterThanEndToGetPayableMinutes(){
        $calculator = new ParkingTimeCalculator();
        $parking = new Parking(0, 1);
        $calculator->getPayableMinutes($parking, '17:00', '16:00');
    }

    /**
     * Test that time calculation is correct when parking started during
     * the payable interval
     */
    public function testGetPayableTimeStartInsidePayable()
    {
        $calculator = new ParkingTimeCalculator();
        $parking = new Parking(strtotime('2017-02-13 15:15:00'), strtotime('2017-02-13 17:10:00'));
        $periodStart = '15:00';
        $periodEnd = '17:00';

        $this->assertEquals(105, $calculator->getPayableMinutes($parking, $periodStart, $periodEnd));
    }

    /**
     * Test that time calculation is correct when parking ended during
     * the payable interval
     */
    public function testGetPayableTimeEndInsidePayable()
    {
        $calculator = new ParkingTimeCalculator();
        $parking = new Parking(strtotime('2017-02-13 14:50:00'), strtotime('2017-02-13 16:30:00'));
        $periodStart = '15:00';
        $periodEnd = '17:00';

        $this->assertEquals(90, $calculator->getPayableMinutes($parking, $periodStart, $periodEnd));
    }

    /**
     * Test that time calculation is correct when parking started and ended
     * during the payable period
     */
    public function testGetPayableTimeBothInsidePayable()
    {
        $calculator = new ParkingTimeCalculator();
        $parking = new Parking(strtotime('2017-02-13 15:30:00'), strtotime('2017-02-13 16:30:00'));
        $periodStart = '15:00';
        $periodEnd = '17:00';

        $this->assertEquals(60, $calculator->getPayableMinutes($parking, $periodStart, $periodEnd));
    }

    /**
     * Test that time calculation is correct when payable period is
     * completely inside the parking period
     */
    public function testGetPayableTimeForFullSingleDay()
    {
        $calculator = new ParkingTimeCalculator();
        $parking = new Parking(strtotime('2017-02-13 12:00:00'), strtotime('2017-02-13 17:00:00'));
        $periodStart = '13:00';
        $periodEnd = '16:00';

        $this->assertEquals(180, $calculator->getPayableMinutes($parking, $periodStart, $periodEnd));
    }

    /**
     * Test that time calculation is correct when parking is
     * completely outside the payable period
     */
    public function testGetPayableTimeForSingleDayOutsidePayable()
    {
        $calculator = new ParkingTimeCalculator();
        $parking = new Parking(strtotime('2017-02-13 14:00:00'), strtotime('2017-02-13 15:00:00'));
        $periodStart = '15:00';
        $periodEnd = '16:00';

        $this->assertEquals(0, $calculator->getPayableMinutes($parking, $periodStart, $periodEnd));
    }

    /**
     * Test that time calculation is correct when parking spans
     * over multiple days
     */
    public function testGetPayableTimeForMultiDay()
    {
        $calculator = new ParkingTimeCalculator();
        $parking = new Parking(strtotime('2017-02-13 14:00:00'), strtotime('2017-02-17 16:00:00'));
        $periodStart = '13:00';
        $periodEnd = '16:00';

        $this->assertEquals(840, $calculator->getPayableMinutes($parking, $periodStart, $periodEnd));
    }
}
