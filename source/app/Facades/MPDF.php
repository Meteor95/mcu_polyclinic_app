<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MPDF extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mpdf.service';
    }
}