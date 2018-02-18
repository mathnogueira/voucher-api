<?php

namespace App\Utils;

class SystemClock implements IClock
{
    public function now()
    {
        return date("Y-m-d H:i:s");
    }
}