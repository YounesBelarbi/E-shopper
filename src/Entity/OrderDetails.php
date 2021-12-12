<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderDetailsRepository::class)
 */
class OrderDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $productName;

    /**
     * @ORM\Column(type="float")
     */
    private $productPrice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $subtotalHT;

    /**
     * @ORM\Column(type="float")
     */
    private $taxe;

    /**
     * @ORM\Column(type="float")
     */
    private $subtotalTTC;

    /**
     * @ORM\ManyToOne(targetEntity=Orders::class, inversedBy="orderDetails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $relatedOrder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->productPrice;
    }

    public function setProductPrice(float $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSubtotalHT(): ?float
    {
        return $this->subtotalHT;
    }

    public function setSubtotalHT(float $subtotalHT): self
    {
        $this->subtotalHT = $subtotalHT;

        return $this;
    }

    public function getTaxe(): ?float
    {
        return $this->taxe;
    }

    public function setTaxe(float $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getSubtotalTTC(): ?float
    {
        return $this->subtotalTTC;
    }

    public function setSubtotalTTC(float $subtotalTTC): self
    {
        $this->subtotalTTC = $subtotalTTC;

        return $this;
    }

    public function getRelatedOrder(): ?Orders
    {
        return $this->relatedOrder;
    }

    public function setRelatedOrder(?Orders $relatedOrder): self
    {
        $this->relatedOrder = $relatedOrder;

        return $this;
    }
}
