<?php

namespace App\Http\Controllers;

use App\Parking;
use App\ParkingTimeCalculator;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ParkingCalculationController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function calculate(Request $request) {
        $error = false;
        $dto = ['url' => route('calculate')];

        try {
            $start = $this->parseToTimestamp($request->get('time-start'));
            $end = $this->parseToTimestamp($request->get('time-end'));
            $parking = new Parking($start, $end);
        } catch(\Exception $exception) {
            $error = true;
            $dto['error'] = "Could not evaluate the given times";
        }

        if (!empty($parking) && !$error) {
            $paringPrice = $this->calculateParkingCost($parking);
            $dto['result'] = "Your parking costed $paringPrice SEK.";
        }


        return view(
            'home',
            $dto
        );
    }

    /**
     * @param mixed $input A timestamp or string representing a time and date
     *
     * @return int
     * @throws \InvalidArgumentException
     */
    public function parseToTimestamp($input)
    {
        if (is_numeric($input) && ($input <= PHP_INT_MAX) && ($input >= ~PHP_INT_MAX)) {
            return (int) $input;
        } elseif (($timestamp = strtotime($input)) !== false) {
            return (int) $timestamp;
        }

        throw new InvalidArgumentException("The given input could not be parsed to a timestamp");
    }

    /**
     * Calculates the cost of the payable time based on the parking rules:
     * * 5 SEK/h during 09:00 - 18:00
     * * 10 SEK/h for the first hour
     * * Maximum amount of 25 SEK
     *
     * @param Parking $parking
     *
     * @return string
     */
    public function calculateParkingCost(Parking $parking){
        $calculator = new ParkingTimeCalculator();
        $payableTime = $calculator->getPayableMinutes($parking, '09:00', '18:00');

        if ($payableTime < 60) {
            $cost = $payableTime * (10 / 60);
        } else {
            $cost = ($payableTime * (5 / 60)) + 5;
        }

        return number_format(($cost > 25 ? 25 : $cost), 2);
    }

}
