<?php

namespace AppBundle\Entity;

/**
 * Oamountproducts
 */
class Oamountproducts
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $amountproducts;

    /**
     * @var integer
     */
    private $product;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amountproducts
     *
     * @param integer $amountproducts
     *
     * @return Oamountproducts
     */
    public function setAmountproducts($amountproducts)
    {
        $this->amountproducts = $amountproducts;

        return $this;
    }

    /**
     * Get amountproducts
     *
     * @return integer
     */
    public function getAmountproducts()
    {
        return $this->amountproducts;
    }

    /**
     * Set product
     *
     * @param integer $product
     *
     * @return Oamountproducts
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return integer
     */
    public function getProduct()
    {
        return $this->product;
    }
}
