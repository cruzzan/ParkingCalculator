<?php

namespace App;


class Parking
{
    private $start;
    private $end;

    /**
     * Parking constructor.
     *
     * @param $start
     * @param $end
     * @throws \InvalidArgumentException
     */
    function __construct($start, $end)
    {
        if ($start >= $end) {
            throw new \InvalidArgumentException("Start can not be equal to or greater than end.");
        }
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     *
     * @return $this
     */
    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     *
     * @return $this
     */
    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }
}
