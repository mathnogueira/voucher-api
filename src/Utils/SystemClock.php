<?php

namespace App\Utils;

class SystemClock implements ClockInterface
{
    public function now()
    {
        return date("Y-m-d H:i:s");
    }
}
