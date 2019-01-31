<?php

namespace Prhost\Packer\Classes;


class Package extends Box
{
    /**
     * @var float
     */
    protected $maxWeight = 0.0;

    /**
     * @return float
     */
    public function getMaxWeight(): float
    {
        return $this->maxWeight;
    }

    /**
     * @param float $maxWeight
     */
    public function setMaxWeight(float $maxWeight): void
    {
        $this->maxWeight = $maxWeight;
    }
}
