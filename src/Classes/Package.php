<?php

namespace Prhost\Packer\Classes;


class Package extends Box
{
    /**
     * @var float
     */
    protected $maxWeight = 0.0;

    protected $dynamicDimensions = false;

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

    /**
     * @return bool
     */
    public function isDynamicDimensions(): bool
    {
        return $this->dynamicDimensions;
    }

    /**
     * @param bool $dynamicDimensions
     */
    public function setDynamicDimensions(bool $dynamicDimensions): self
    {
        $this->dynamicDimensions = $dynamicDimensions;
        return $this;
    }

    /**
     * @param bool $dynamicDimensions
     */
    public function enableDynamicDimensions(): self
    {
        $this->dynamicDimensions = true;
        return $this;
    }

    /**
     * @param bool $dynamicDimensions
     */
    public function disableDynamicDimensions(): self 
    {
        $this->dynamicDimensions = false;
        return $this;
    }
}
