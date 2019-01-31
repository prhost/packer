<?php

namespace Prhost\Packer\Classes;


use Prhost\Packer\Exceptions\VolumeException;

class Volume
{
    /**
     * @var Package
     */
    protected $package;

    /**
     * @var Product[]
     */
    protected $products = [];

    protected $currentCubing = 0.0;

    protected $currentWeight = 0.0;

    /**
     * @return Package
     */
    public function getPackage(): Package
    {
        return $this->package;
    }

    /**
     * @param Package $package
     * @return Volume
     */
    public function setPackage(Package $package): self
    {
        $this->package = $package;
        return $this;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * Valida se cade o product no pacote
     *
     * @param Product $product
     * @throws VolumeException
     */
    public function fitInPackage(Product $product)
    {
        if (!$product->getCubing() || $product->getCubing() > $this->package->getCubing()) {
            throw new VolumeException('The product has exceeded the package cubing');
        }

        if ($product->isEitherSideIsUp()) {
            if ($product->getWidth() > $this->package->getWidth() || $product->getHeight() > $this->package->getLength() || $product->getLength() > $this->package->getHeight()) {
                if ($product->getLength() > $this->package->getLength() || $product->getHeight() > $this->package->getWidth() || $product->getWidth() > $this->package->getHeight()) {
                    if ($product->getHeight() > $this->package->getHeight() || $product->getLength() > $this->package->getWidth() || $product->getWidth() > $this->package->getLength()) {
                        throw new VolumeException('The product has exceeded the package volume');
                    }
                }
            }
        } else {
            if ($product->getHeight() > $this->package->getHeight()) {
                throw new VolumeException('The product has exceeded the package height');
            }

            if ($product->getWidth() > $this->package->getWidth()) {
                throw new VolumeException('The product has exceeded the package width');
            }

            if ($product->getLength() > $this->package->getLength()) {
                throw new VolumeException('The product has exceeded the package length');
            }
        }

        if ($this->currentCubing + $product->getCubing() > $this->package->getCubing()) {
            throw new VolumeException('The product has exceeded the package cubing');
        }

        if (($this->package->getMaxWeight() > 0.0) && ($this->currentWeight + $product->getWeight() > $this->package->getMaxWeight())) {
            throw new VolumeException('The product has exceeded the package max weight');
        }
    }

    /**
     * @param Product $product
     * @return Volume
     */
    public function addProduct(Product $product): self
    {
        $token = md5($product->getIdentifier());

        if (isset($this->products[$token])) {
            $this->products[$token]->setQuantity($this->products[$token]->getQuantity() + 1);
        } else {
            $product->setQuantity(1);
            $this->products[$token] = $product;
        }

        $this->currentCubing += $product->getCubing();
        $this->currentWeight += $product->getWeight();
        return $this;
    }

    /**
     * Retorna o total de weight do volume
     *
     * @return float
     */
    public function getTotalWeight(): float
    {
        $weight = 0.0;
        foreach ($this->products as $product) {
            $weight += $product->getWeight();
        }

        return $weight + $this->package->getWeight();
    }

    public function toArray(): array
    {
        $list = [];

        if ($this->package->getLength()) {
            $list['length'] = number_format($this->package->getLength(), 2, ',', '.');
        }

        if ($this->package->getHeight()) {
            $list['height'] = number_format($this->package->getHeight(), 2, ',', '.');
        }

        if ($this->package->getWidth()) {
            $list['width'] = number_format($this->package->getWidth(), 2, ',', '.');
        }


        if ($this->package->getIdentifier()) {
            $list['identifier'] = $this->package->getIdentifier();
        }

        if ($this->getTotalWeight()) {
            $list['weight'] = number_format($this->getTotalWeight(), 2, ',', '.');
        }

        return $list;
    }
}
