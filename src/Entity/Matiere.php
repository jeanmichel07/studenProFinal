<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatiereRepository::class)
 */
class Matiere
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statu;

    /**
     * @ORM\OneToMany(targetEntity=Specialty::class, mappedBy="matiere")
     */
    private $specialties;

    /**
     * @ORM\OneToMany(targetEntity=PublicationStudent::class, mappedBy="matiere")
     */
    private $publicationStudents;

    public function __construct()
    {
        $this->specialties = new ArrayCollection();
        $this->publicationStudents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getStatu(): ?bool
    {
        return $this->statu;
    }

    public function setStatu(?bool $statu): self
    {
        $this->statu = $statu;

        return $this;
    }

    /**
     * @return Collection|Specialty[]
     */
    public function getSpecialties(): Collection
    {
        return $this->specialties;
    }

    public function addSpecialty(Specialty $specialty): self
    {
        if (!$this->specialties->contains($specialty)) {
            $this->specialties[] = $specialty;
            $specialty->setMatiere($this);
        }

        return $this;
    }

    public function removeSpecialty(Specialty $specialty): self
    {
        if ($this->specialties->removeElement($specialty)) {
            // set the owning side to null (unless already changed)
            if ($specialty->getMatiere() === $this) {
                $specialty->setMatiere(null);
            }
        }

        return $this;
    }

    public function __toString():string
    {
        return $this->nom;
    }

    /**
     * @return Collection|PublicationStudent[]
     */
    public function getPublicationStudents(): Collection
    {
        return $this->publicationStudents;
    }

    public function addPublicationStudent(PublicationStudent $publicationStudent): self
    {
        if (!$this->publicationStudents->contains($publicationStudent)) {
            $this->publicationStudents[] = $publicationStudent;
            $publicationStudent->setMatiere($this);
        }

        return $this;
    }

    public function removePublicationStudent(PublicationStudent $publicationStudent): self
    {
        if ($this->publicationStudents->removeElement($publicationStudent)) {
            // set the owning side to null (unless already changed)
            if ($publicationStudent->getMatiere() === $this) {
                $publicationStudent->setMatiere(null);
            }
        }

        return $this;
    }
}
