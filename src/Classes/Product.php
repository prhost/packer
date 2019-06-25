<?php

namespace Prhost\Packer\Classes;


class Product extends Box
{
    const POSITION_STADING = 'standing';
    const POSITION_LYDING_FORWARD = 'lying_forward';
    const POSITION_LYDING_ON_THE_SIDE = 'lying_on_the_side';
    const POSITION_ROTATED_TO_THE_SIDE = 'rotated_to_the_side';

    /**
     * @var int
     */
    protected $quantity = 1;

    /**
     * @var float
     */
    protected $cost = 0.0;

    /**
     * @var bool
     */
    protected $eitherSideIsUp = false;

    /**
     * @var string
     */
    protected $position = self::POSITION_STADING;

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Product
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost(float $cost): self
    {
        $this->cost = $cost;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEitherSideIsUp(): bool
    {
        return $this->eitherSideIsUp;
    }

    /**
     * @param bool $eitherSideIsUp
     * @return Product
     */
    public function setEitherSideIsUp(bool $eitherSideIsUp): self
    {
        $this->eitherSideIsUp = $eitherSideIsUp;
        return $this;
    }

    public function enableEitherSideIsUp()
    {
        $this->eitherSideIsUp = true;
        return $this;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition(string $position): self
    {
        $this->position = $position;
        return $this;
    }
}
