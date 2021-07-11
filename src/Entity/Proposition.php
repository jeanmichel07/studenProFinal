<?php

namespace App\Entity;

use App\Repository\PropositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=PropositionRepository::class)
 */
class Proposition
{
    /*
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PublicationStudent::class, inversedBy="propositions")
     */
    private $PublicationStudent;

    /**
     * @ORM\OneToMany(targetEntity=LineProposition::class, mappedBy="Proposition")
     */
    private $linePropositions;

    public function __construct()
    {
        $this->linePropositions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublicationStudent(): ?PublicationStudent
    {
        return $this->PublicationStudent;
    }

    public function setPublicationStudent(?PublicationStudent $PublicationStudent): self
    {
        $this->PublicationStudent = $PublicationStudent;

        return $this;
    }

    /**
     * @return Collection|LineProposition[]
     */
    public function getLinePropositions(): Collection
    {
        return $this->linePropositions;
    }

    public function addLineProposition(LineProposition $lineProposition): self
    {
        if (!$this->linePropositions->contains($lineProposition)) {
            $this->linePropositions[] = $lineProposition;
            $lineProposition->setProposition($this);
        }

        return $this;
    }

    public function removeLineProposition(LineProposition $lineProposition): self
    {
        if ($this->linePropositions->removeElement($lineProposition)) {
            // set the owning side to null (unless already changed)
            if ($lineProposition->getProposition() === $this) {
                $lineProposition->setProposition(null);
            }
        }

        return $this;
    }
}
