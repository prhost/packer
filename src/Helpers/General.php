<?php

namespace Prhost\Packer\Helpers;


use Prhost\Packer\Classes\Box;

class General
{
    public static function calcCubing(Box $box, float $coefficient = 1.0): float
    {
        return ($box->getLength() * $box->getWidth() * $box->getHeight()) / $coefficient;
    }
}