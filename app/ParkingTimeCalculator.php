<?php

namespace App;

class ParkingTimeCalculator
{
    /**
     * Method to get total number of payable minutes for a parking
     *
     * @param Parking $parking
     * @param string $payableStart Time of payable periods start 'hh:mm'
     * @param string $payableEnd Time of payable periods end 'hh:mm'
     *
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getPayableMinutes(Parking $parking, $payableStart, $payableEnd)
    {
        if ($payableStart >= $payableEnd) {
            throw new \InvalidArgumentException("Start can not be equal to or greater than end.");
        }

        $payablePeriodMinutes = $this->periodInMinutes(strtotime($payableStart), strtotime($payableEnd));
        $unadjustedTimeSpent = $this->parkingUnadjustedTime($parking, $payablePeriodMinutes);

        $startDiff = strtotime(date('H:i', $parking->getStart())) - strtotime($payableStart);
        $endDiff = strtotime(date('H:i', $parking->getEnd())) - strtotime($payableEnd);

        return $this->adjustTimeSpent($unadjustedTimeSpent,$startDiff, $endDiff);
    }

    /**
     * @param Parking $parking
     *
     * @return int
     */
    private function totalTimeInParking(Parking $parking)
    {
        return $parking->getEnd() - $parking->getStart();
    }

    /**
     * @param int $seconds
     *
     * @return float
     */
    private function secondsToWholeDays($seconds)
    {
        return ceil($seconds / 86400);
    }

    /**
     * @param int $start
     * @param int $end
     *
     * @return float
     */
    private function periodInMinutes($start, $end)
    {
        return ceil(($end - $start) / 60);
    }

    /**
     * @param Parking $parking
     * @param float $intervalSize
     *
     * @return float
     */
    private function parkingUnadjustedTime(Parking $parking, $intervalSize)
    {
        return $this->secondsToWholeDays($this->totalTimeInParking($parking)) * $intervalSize;
    }

    /**
     * @param float $unadjustedTime
     * @param int $diffStart The resulting number of seconds from parking start - period start
     * @param int $diffEnd The resulting number of seconds from parking end - period end
     *
     * @return float
     */
    private function adjustTimeSpent($unadjustedTime, $diffStart, $diffEnd)
    {
        $adjustedTime = $unadjustedTime;
        if ($diffStart > 0) {
            $adjustedTime -= round($diffStart / 60);
        }
        if ($diffEnd < 0) {
            $adjustedTime += round($diffEnd / 60);
        }

        return $adjustedTime;
    }
}
