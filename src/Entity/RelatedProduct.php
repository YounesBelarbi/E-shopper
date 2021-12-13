<?php

namespace App\Entity;

use App\Repository\RelatedProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RelatedProductRepository::class)
 */
class RelatedProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $relatedTo;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="relatedProduct")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelatedTo(): ?int
    {
        return $this->relatedTo;
    }

    public function setRelatedTo(int $relatedTo): self
    {
        $this->relatedTo = $relatedTo;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
