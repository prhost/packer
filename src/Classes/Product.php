<?php

namespace Prhost\Packer\Classes;


class Product extends Box
{
    /**
     * @var int
     */
    protected $quantity = 1;

    /**
     * @var bool
     */
    protected $eitherSideIsUp = false;

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
}
