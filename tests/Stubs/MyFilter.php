<?php

namespace Tests\Stubs;

use D2\Filter\Filter;

class MyFilter extends Filter
{
    protected function money($value)
    {
        return str_replace(",", ".", $value);
    }
}
