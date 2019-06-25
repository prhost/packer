<?php

namespace Prhost\Packer\Classes;


use Prhost\Packer\Exceptions\VolumeException;

class Volume extends Box
{
    /**
     * @var Package
     */
    protected $package;

    /**
     * @var Product[]
     */
    protected $products = [];

    protected $cubing = 0.0;

    protected $weight = 0.0;

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
     * @return string Position of product
     * @throws VolumeException
     */
    public function fitInPackage(Product $product): string
    {
        $position = Product::POSITION_STADING;

        if (!$this->package->isDynamicDimensions()) {

            if (!$product->getCubing() || $this->cubing + $product->getCubing() > $this->package->getCubing()) {
                throw new VolumeException('The product has exceeded the package cubing');
            }

            if (($this->weight + $product->getWeight()) > $this->package->getMaxWeight()) {
                throw new VolumeException('The product has exceeded the package max weight');
            }

            if ($product->isEitherSideIsUp()) {

                $position = $this->checkSideIsSmaller($product);
                $position = $this->checkTheSidesStartingPosition($product, $position);

            } else {
                if ($this->getHeight() + $product->getHeight() > $this->package->getHeight()) {
                    throw new VolumeException('The product has exceeded the package height');
                }

                if ($this->getWidth() + $product->getWidth() > $this->package->getWidth()) {
                    throw new VolumeException('The product has exceeded the package width');
                }

                if ($this->getLength() + $product->getLength() > $this->package->getLength()) {
                    throw new VolumeException('The product has exceeded the package length');
                }
            }
        }

        return $position;
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

        switch ($product->getPosition()) {
            case Product::POSITION_STADING:
                {
                    $this->height += $product->getHeight();
                    $this->width += $product->getWidth();
                    $this->length += $product->getLength();
                    break;
                }
            case Product::POSITION_LYDING_FORWARD:
                {
                    $this->height += $product->getHeight();
                    $this->width += $product->getLength();
                    $this->length += $product->getWidth();
                    break;
                }
            case Product::POSITION_ROTATED_TO_THE_SIDE:
                {
                    $this->width += $product->getWidth();
                    $this->height += $product->getLength();
                    $this->length += $product->getHeight();
                    break;
                }
            case Product::POSITION_LYDING_ON_THE_SIDE:
                {
                    $this->length += $product->getLength();
                    $this->height += $product->getWidth();
                    $this->width += $product->getHeight();
                    break;
                }
            default:
                {
                    $this->height += $product->getHeight();
                    $this->width += $product->getWidth();
                    $this->length += $product->getLength();
                    break;
                }
        }

        $this->cubing += $product->getCubing();
        $this->weight += $product->getWeight();
        $this->diameter += $product->getDiameter();

        return $this;
    }

    /**
     * Retorna o total de weight do volume
     *
     * @return float
     */
    public function getTotalWeight(): float
    {
        return $this->weight + $this->package->getWeight();
    }

    /**
     * Return the total cost of products
     *
     * @return float
     */
    public function getTotalCost(): float
    {
        $cost = 0.0;

        foreach ($this->products as $product) {
            $cost += $product->getQuantity() * $product->getCost();
        }

        return $cost;
    }

    public function toArray(): array
    {
        $list = [];

        if ($this->package->getIdentifier()) {
            $list['identifier'] = $this->package->getIdentifier();
        }

        if ($this->package->getLength()) {
            $list['length'] = number_format($this->package->getLength(), 2, ',', '.');
        }

        if ($this->package->getHeight()) {
            $list['height'] = number_format($this->package->getHeight(), 2, ',', '.');
        }

        if ($this->package->getWidth()) {
            $list['width'] = number_format($this->package->getWidth(), 2, ',', '.');
        }

        if ($this->getTotalWeight()) {
            $list['weight'] = number_format($this->getTotalWeight(), 2, ',', '.');
        }

        if ($this->getLength()) {
            $list['currentLength'] = number_format($this->getLength(), 2, ',', '.');
        }

        if ($this->getHeight()) {
            $list['currentHeight'] = number_format($this->getHeight(), 2, ',', '.');
        }

        if ($this->getWidth()) {
            $list['currentWidth'] = number_format($this->getWidth(), 2, ',', '.');
        }

        return $list;
    }

    /**
     * @param Product $product
     * @return string
     * @throws VolumeException
     */
    protected function checkTheSidesStartingPosition(Product $product, string $position): string
    {
        switch ($position) {
            case Product::POSITION_STADING:
                {
                    if ($this->getWidth() + $product->getWidth() > $this->package->getWidth()
                        || $this->getHeight() + $product->getHeight() > $this->package->getHeight()
                        || $this->getLength() + $product->getLength() > $this->package->getLength()) {

                        if ($this->getWidth() + $product->getWidth() > $this->package->getWidth()
                            || $this->getLength() + $product->getHeight() > $this->package->getLength()
                            || $this->getHeight() + $product->getLength() > $this->package->getHeight()) {

                            if ($this->getLength() + $product->getLength() > $this->package->getLength()
                                || $this->getWidth() + $product->getHeight() > $this->package->getWidth()
                                || $this->getHeight() + $product->getWidth() > $this->package->getHeight()) {

                                if ($this->getHeight() + $product->getHeight() > $this->package->getHeight()
                                    || $this->getWidth() + $product->getLength() > $this->package->getWidth()
                                    || $this->getLength() + $product->getWidth() > $this->package->getLength()) {

                                    throw new VolumeException('The product has exceeded the package volume');
                                } else {
                                    $position = Product::POSITION_LYDING_FORWARD;
                                }
                            } else {
                                $position = Product::POSITION_LYDING_ON_THE_SIDE;
                            }
                        } else {
                            $position = Product::POSITION_ROTATED_TO_THE_SIDE;
                        }
                    } else {
                        $position = Product::POSITION_STADING;
                    }
                    break;
                }
            case Product::POSITION_LYDING_FORWARD:
                {
                    if ($this->getHeight() + $product->getHeight() > $this->package->getHeight()
                        || $this->getWidth() + $product->getLength() > $this->package->getWidth()
                        || $this->getLength() + $product->getWidth() > $this->package->getLength()) {

                        if ($this->getWidth() + $product->getWidth() > $this->package->getWidth()
                            || $this->getHeight() + $product->getHeight() > $this->package->getHeight()
                            || $this->getLength() + $product->getLength() > $this->package->getLength()) {

                            if ($this->getWidth() + $product->getWidth() > $this->package->getWidth()
                                || $this->getLength() + $product->getHeight() > $this->package->getLength()
                                || $this->getHeight() + $product->getLength() > $this->package->getHeight()) {

                                if ($this->getLength() + $product->getLength() > $this->package->getLength()
                                    || $this->getWidth() + $product->getHeight() > $this->package->getWidth()
                                    || $this->getHeight() + $product->getWidth() > $this->package->getHeight()) {

                                    throw new VolumeException('The product has exceeded the package volume');

                                } else {
                                    $position = Product::POSITION_LYDING_ON_THE_SIDE;
                                }
                            } else {
                                $position = Product::POSITION_ROTATED_TO_THE_SIDE;
                            }
                        } else {
                            $position = Product::POSITION_STADING;
                        }
                    } else {
                        $position = Product::POSITION_LYDING_FORWARD;
                    }
                    break;
                }
            case Product::POSITION_ROTATED_TO_THE_SIDE:
                {
                    if ($this->getWidth() + $product->getWidth() > $this->package->getWidth()
                        || $this->getLength() + $product->getHeight() > $this->package->getLength()
                        || $this->getHeight() + $product->getLength() > $this->package->getHeight()) {

                        if ($this->getWidth() + $product->getWidth() > $this->package->getWidth()
                            || $this->getHeight() + $product->getHeight() > $this->package->getHeight()
                            || $this->getLength() + $product->getLength() > $this->package->getLength()) {

                            if ($this->getLength() + $product->getLength() > $this->package->getLength()
                                || $this->getWidth() + $product->getHeight() > $this->package->getWidth()
                                || $this->getHeight() + $product->getWidth() > $this->package->getHeight()) {

                                if ($this->getHeight() + $product->getHeight() > $this->package->getHeight()
                                    || $this->getWidth() + $product->getLength() > $this->package->getWidth()
                                    || $this->getLength() + $product->getWidth() > $this->package->getLength()) {

                                    throw new VolumeException('The product has exceeded the package volume');
                                } else {
                                    $position = Product::POSITION_LYDING_FORWARD;
                                }
                            } else {
                                $position = Product::POSITION_LYDING_ON_THE_SIDE;
                            }
                        } else {
                            $position = Product::POSITION_STADING;
                        }
                    } else {
                        $position = Product::POSITION_ROTATED_TO_THE_SIDE;
                    }
                    break;
                }
            case Product::POSITION_LYDING_ON_THE_SIDE:
                {
                    if ($this->getLength() + $product->getLength() > $this->package->getLength()
                        || $this->getWidth() + $product->getHeight() > $this->package->getWidth()
                        || $this->getHeight() + $product->getWidth() > $this->package->getHeight()) {

                        if ($this->getWidth() + $product->getWidth() > $this->package->getWidth()
                            || $this->getHeight() + $product->getHeight() > $this->package->getHeight()
                            || $this->getLength() + $product->getLength() > $this->package->getLength()) {

                            if ($this->getWidth() + $product->getWidth() > $this->package->getWidth()
                                || $this->getLength() + $product->getHeight() > $this->package->getLength()
                                || $this->getHeight() + $product->getLength() > $this->package->getHeight()) {

                                if ($this->getHeight() + $product->getHeight() > $this->package->getHeight()
                                    || $this->getWidth() + $product->getLength() > $this->package->getWidth()
                                    || $this->getLength() + $product->getWidth() > $this->package->getLength()) {

                                    throw new VolumeException('The product has exceeded the package volume');
                                } else {
                                    $position = Product::POSITION_LYDING_FORWARD;
                                }
                            } else {
                                $position = Product::POSITION_ROTATED_TO_THE_SIDE;
                            }
                        } else {
                            $position = Product::POSITION_STADING;
                        }
                    } else {
                        $position = Product::POSITION_LYDING_ON_THE_SIDE;
                    }
                    break;
                }
            default:
                {
                    if ($this->getWidth() + $product->getWidth() > $this->package->getWidth()
                        || $this->getHeight() + $product->getHeight() > $this->package->getHeight()
                        || $this->getLength() + $product->getLength() > $this->package->getLength()) {

                        if ($this->getWidth() + $product->getWidth() > $this->package->getWidth()
                            || $this->getLength() + $product->getHeight() > $this->package->getLength()
                            || $this->getHeight() + $product->getLength() > $this->package->getHeight()) {

                            if ($this->getLength() + $product->getLength() > $this->package->getLength()
                                || $this->getWidth() + $product->getHeight() > $this->package->getWidth()
                                || $this->getHeight() + $product->getWidth() > $this->package->getHeight()) {

                                if ($this->getHeight() + $product->getHeight() > $this->package->getHeight()
                                    || $this->getWidth() + $product->getLength() > $this->package->getWidth()
                                    || $this->getLength() + $product->getWidth() > $this->package->getLength()) {

                                    throw new VolumeException('The product has exceeded the package volume');
                                } else {
                                    $position = Product::POSITION_LYDING_FORWARD;
                                }
                            } else {
                                $position = Product::POSITION_LYDING_ON_THE_SIDE;
                            }
                        } else {
                            $position = Product::POSITION_ROTATED_TO_THE_SIDE;
                        }
                    } else {
                        $position = Product::POSITION_STADING;
                    }
                    break;
                }
        }

        return $position;
    }

    protected function checkSideIsSmaller(Product $product): string
    {
        $heightStading = $heightLydingForward = $heightRotatedSide = $heightLydingSide = $this->height;
        $widthStading = $widthLydingForward = $widthRotatedSide = $widthLydingSide = $this->width;
        $lengthStading = $lengthLydingForward = $lengthRotatedSide = $lengthLydingSide = $this->length;

        $heightStading += $product->getHeight();
        $widthStading += $product->getWidth();
        $lengthStading += $product->getLength();

        $heightLydingForward += $product->getHeight();
        $widthLydingForward += $product->getLength();
        $lengthLydingForward += $product->getWidth();

        $widthRotatedSide += $product->getWidth();
        $heightRotatedSide += $product->getLength();
        $lengthRotatedSide += $product->getHeight();

        $lengthLydingSide += $product->getLength();
        $heightLydingSide += $product->getWidth();
        $widthLydingSide += $product->getHeight();

        $cubingStading = $heightStading * $widthStading * $lengthStading;
        $cubingLydingForward = $heightLydingForward * $widthLydingForward * $lengthLydingForward;
        $cubingRotatedSide = $heightRotatedSide * $widthRotatedSide * $lengthRotatedSide;
        $cubingLydingSide = $heightLydingSide * $widthLydingSide * $lengthLydingSide;

        if ($cubingStading >= $cubingLydingForward && $cubingStading >= $cubingRotatedSide && $cubingStading >= $cubingLydingSide) {
            return Product::POSITION_STADING;
        }

        if ($cubingLydingForward >= $cubingStading && $cubingLydingForward >= $cubingRotatedSide && $cubingLydingForward >= $cubingLydingSide) {
            return Product::POSITION_LYDING_FORWARD;
        }

        if ($cubingRotatedSide >= $cubingLydingForward && $cubingRotatedSide >= $cubingStading && $cubingRotatedSide >= $cubingLydingSide) {
            return Product::POSITION_ROTATED_TO_THE_SIDE;
        }

        if ($cubingLydingSide >= $cubingLydingForward && $cubingLydingSide >= $cubingRotatedSide && $cubingLydingSide >= $cubingStading) {
            return Product::POSITION_LYDING_ON_THE_SIDE;
        }
    }
}
