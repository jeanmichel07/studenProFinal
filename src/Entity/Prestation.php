<?php

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrestationRepository::class)
 */
class Prestation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $tarif;

    /**
     * @ORM\OneToOne(targetEntity=LineProposition::class, inversedBy="prestations", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $lineProposition;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarif(): ?string
    {
        return $this->tarif;
    }

    public function setTarif(string $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getLineProposition(): ?LineProposition
    {
        return $this->lineProposition;
    }

    public function setLineProposition(LineProposition $lineProposition): self
    {
        $this->lineProposition = $lineProposition;

        return $this;
    }
}
