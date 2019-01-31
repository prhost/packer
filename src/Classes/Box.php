<?php

namespace Prhost\Packer\Classes;


use Prhost\Packer\Helpers\General;

class Box
{
    /**
     * Identifier da package
     *
     * @var string
     */
    protected $identifier = '';

    /**
     * Weight em kilos
     *
     * @var float
     */
    protected $weight;

    /**
     * Length em centímetros
     *
     * @var float
     */
    protected $length = 0.0;

    /**
     * Height em centímetros
     *
     * @var float
     */
    protected $height = 0.0;

    /**
     * Width em centímetros
     *
     * @var float
     */
    protected $width = 0.0;

    /**
     * Diameter em centímetros (Rolo/Prisma)
     *
     * @var float
     */
    protected $diameter = 0.0;

    /**
     * @var float
     */
    protected $cubing = 0.0;

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     * @return Package
     */
    public function setWeight(float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return float
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * @param float $length
     * @return Package
     */
    public function setLength(float $length): self
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * @param float $height
     * @return Package
     */
    public function setHeight(float $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * @param float $width
     * @return Package
     */
    public function setWidth(float $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return float
     */
    public function getDiameter(): float
    {
        return $this->diameter;
    }

    /**
     * @param float $diameter
     * @return Package
     */
    public function setDiameter(float $diameter): self
    {
        $this->diameter = $diameter;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return Package
     */
    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * Calcula e retorna a cubing
     *
     * @return float
     */
    public function getCubing(): float
    {
        if (!$this->cubing) {
            $this->cubing = General::calcCubing($this);
        }

        return $this->cubing;
    }

    /**
     * @param float $cubing
     * @return Package
     */
    public function setCubing(float $cubing): self
    {
        $this->cubing = $cubing;
        return $this;
    }
}
