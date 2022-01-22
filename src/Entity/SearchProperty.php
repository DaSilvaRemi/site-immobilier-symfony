<?php

namespace App\Entity;

use App\Repository\SearchPropertyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SearchPropertyRepository::class)
 */
class SearchProperty
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
    private $price_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbPieceMin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriceMax(): ?int
    {
        return $this->price_max;
    }

    public function setPriceMax(int $price_max): self
    {
        $this->price_max = $price_max;

        return $this;
    }

    public function getNbPieceMin(): ?int
    {
        return $this->nbPieceMin;
    }

    public function setNbPieceMin(int $nbPieceMin): self
    {
        $this->nbPieceMin = $nbPieceMin;

        return $this;
    }
}
