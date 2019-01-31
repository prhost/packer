<?php

namespace Prhost\Packer\Classes;

use Prhost\Packer\Exceptions\VolumeException;

class Packer
{
    /**
     * @var ConveyorBelt
     */
    protected $conveyorBelt;
    /**
     * @var Package[]
     */
    protected $packages = [];

    /**
     * @var Product[]
     */
    protected $products = [];

    /**
     * @return Package[]
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    /**
     * @param Package[] $packages
     * @return Packer
     */
    public function setPackages(array $packages): self
    {
        $this->packages = $packages;
        return $this;
    }

    /**
     * @return ConveyorBelt
     */
    public function getConveyorBelt(): ConveyorBelt
    {
        return $this->conveyorBelt;
    }

    /**
     * @param ConveyorBelt $conveyorBelt
     * @return Packer
     */
    public function setConveyorBelt(ConveyorBelt $conveyorBelt): self
    {
        $this->conveyorBelt = $conveyorBelt;
        return $this;
    }

    public function organize(): array
    {
        $volumes = [];

        foreach ($this->getPackages() as $package) {

            $volume = new Volume();
            $volume->setPackage($package);

            foreach ($this->conveyorBelt->getProducts() as $key => $product) {

                $quantity = $product->getQuantity();

                while ($quantity > 0) {

                    try {
                        $volume->fitInPackage($product);

                        $productClone = clone $product;

                        $this->conveyorBelt->take($product);

                        $volume->addProduct($productClone);

                        $quantity--;

                        if ($quantity == 0 && $volume->getProducts() && $this->conveyorBelt->count() == 0) {
                            $volumes[] = $volume;
                            $volume->setPackage($package);
                        }

                    } catch (VolumeException $exception) {

                        if ($volume->getProducts()) {
                            $volumes[] = $volume;

                            //se nao couber, tenta adicionar em um novo volume
                            $volume = new Volume();
                            $volume->setPackage($package);
                        } else {
                            break;
                        }
                    }
                }
            }
        }

        return $volumes;
    }
}