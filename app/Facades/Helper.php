<?php

namespace App\Facades;

class Helper
{
    public static function setActive($pattern = null, $include_class = false)
    {
        return ((request()->is($pattern)) ? (($include_class) ? 'class="active"' : 'active' ) : '');
    }
}