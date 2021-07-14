<?php

namespace App\Entity;

use App\Repository\LinePropositionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=LinePropositionRepository::class)
 */
class LineProposition
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
     * @ORM\ManyToOne(targetEntity=Proposition::class, inversedBy="linePropositions")
     */
    private $Proposition;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="linePropositions")
     */
    private $User;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\OneToOne(targetEntity=Prestation::class, mappedBy="lineProposition", cascade={"persist", "remove"})
     */
    private $prestations;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProposition(): ?Proposition
    {
        return $this->Proposition;
    }

    public function setProposition(?Proposition $Proposition): self
    {
        $this->Proposition = $Proposition;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getPrestations(): ?Prestation
    {
        return $this->prestations;
    }

    public function setPrestations(Prestation $prestations): self
    {
        // set the owning side of the relation if necessary
        if ($prestations->getLineProposition() !== $this) {
            $prestations->setLineProposition($this);
        }

        $this->prestations = $prestations;

        return $this;
    }
}
