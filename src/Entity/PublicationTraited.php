<?php

namespace App\Entity;

use App\Repository\PublicationTraitedRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=PublicationTraitedRepository::class)
 */
class PublicationTraited
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
     * @ORM\Column(type="string", length=255)
     */
    private $file_response;

    /**
     * @ORM\OneToOne(targetEntity=PublicationStudent::class, inversedBy="publicationTraited", cascade={"persist", "remove"})
     */
    private $pubStudent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileResponse(): ?string
    {
        return $this->file_response;
    }

    public function setFileResponse(string $file_response): self
    {
        $this->file_response = $file_response;

        return $this;
    }

    public function getPubStudent(): ?PublicationStudent
    {
        return $this->pubStudent;
    }

    public function setPubStudent(?PublicationStudent $pubStudent): self
    {
        $this->pubStudent = $pubStudent;

        return $this;
    }
}
