<?php

namespace Prhost\Packer\Classes;


use Prhost\Packer\Exceptions\ConveyorBeltException;
use Prhost\Packer\Exceptions\PackerException;

class ConveyorBelt
{
    /**
     * @var Product[]
     */
    protected $products = [];

    public function add(Product $product)
    {
        if (empty($product->getIdentifier())) {
            throw new ConveyorBeltException('Product identifier is required');
        }

        $token = md5($product->getIdentifier());

        if (isset($this->products[$token])) {
            $this->products[$token]->setQuantity($this->products[$token]->getQuantity() + 1);
        } else {
            $this->products[$token] = $product;
        }
    }

    /**
     * @param Product $product
     * @return ConveyorBelt
     * @throws ConveyorBeltException
     */
    public function take(Product $product): self
    {
        $token = md5($product->getIdentifier());

        if (isset($this->products[$token])) {
            $this->products[$token]->setQuantity($this->products[$token]->getQuantity() - 1);
            if ($this->products[$token]->getQuantity() <= 0) {
                unset($this->products[$token]);
                return $this;
            }
        } else {
            throw new ConveyorBeltException('Product not found in conveyorbelt');
        }

        return $this;
    }

    public function count(): int
    {
        return count($this->products);
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }
}
