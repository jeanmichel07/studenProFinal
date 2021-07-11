<?php

namespace App\Entity;

use App\Repository\PublicationStudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PublicationStudentRepository::class)
 */
class PublicationStudent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $explication_visio;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $delay_of_treatement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="publicationStudents")
     */
    private $student;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $availability;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity=Proposition::class, mappedBy="PublicationStudent")
     */
    private $propositions;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="publicationStudents")
     */
    private $matiere;

    public function __construct()
    {
        $this->propositions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExplicationVisio(): ?bool
    {
        return $this->explication_visio;
    }

    public function setExplicationVisio(?bool $explication_visio): self
    {
        $this->explication_visio = $explication_visio;

        return $this;
    }

    public function getDelayOfTreatement(): ?string
    {
        return $this->delay_of_treatement;
    }

    public function setDelayOfTreatement(?string $delay_of_treatement): self
    {
        $this->delay_of_treatement = $delay_of_treatement;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getAvailability(): ?\DateTimeInterface
    {
        return $this->availability;
    }

    public function setAvailability(?\DateTimeInterface $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(?int $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection|Proposition[]
     */
    public function getPropositions(): Collection
    {
        return $this->propositions;
    }

    public function addProposition(Proposition $proposition): self
    {
        if (!$this->propositions->contains($proposition)) {
            $this->propositions[] = $proposition;
            $proposition->setPublicationStudent($this);
        }

        return $this;
    }

    public function removeProposition(Proposition $proposition): self
    {
        if ($this->propositions->removeElement($proposition)) {
            // set the owning side to null (unless already changed)
            if ($proposition->getPublicationStudent() === $this) {
                $proposition->setPublicationStudent(null);
            }
        }

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }
}
