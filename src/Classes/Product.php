<?php

namespace Prhost\Packer\Classes;


class Product extends Box
{
    /**
     * @var int
     */
    protected $quantity = 1;

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
}
