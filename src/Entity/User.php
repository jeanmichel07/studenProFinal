<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="L'adresse email que vous avez utiliser existe déjà")
 */
class User implements UserInterface
{
    /**
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
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $school;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $level_study;

    /**
     * @ORM\OneToMany(targetEntity=PublicationStudent::class, mappedBy="student")
     */
    private $publicationStudents;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $level_study_occuped;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $speciality;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $file_cv;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture_student;

    /**

     * @ORM\Column(type="boolean")
     */
    private $first_connexion;
    /**
     * @ORM\OneToMany(targetEntity=LineProposition::class, mappedBy="User")
     */
    private $linePropositions;

    /**
     * @ORM\OneToMany(targetEntity=Specialty::class, mappedBy="user", cascade={"remove", "persist"})
     */
    private $specialties;


    public function __construct()
    {
        $this->publicationStudents = new ArrayCollection();
        $this->linePropositions = new ArrayCollection();
        $this->specialties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(?string $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getLevelStudy(): ?string
    {
        return $this->level_study;
    }

    public function setLevelStudy(?string $level_study): self
    {
        $this->level_study = $level_study;

        return $this;
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
            $publicationStudent->setStudent($this);
        }

        return $this;
    }

    public function removePublicationStudent(PublicationStudent $publicationStudent): self
    {
        if ($this->publicationStudents->removeElement($publicationStudent)) {
            // set the owning side to null (unless already changed)
            if ($publicationStudent->getStudent() === $this) {
                $publicationStudent->setStudent(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getLevelStudyOccuped(): ?string
    {
        return $this->level_study_occuped;
    }

    public function setLevelStudyOccuped(?string $level_study_occuped): self
    {
        $this->level_study_occuped = $level_study_occuped;

        return $this;
    }

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(?string $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getFileCv()
    {
        return $this->file_cv;
    }

    public function setFileCv($file_cv): self
    {
        $this->file_cv = $file_cv;

        return $this;
    }

    public function getPictureStudent()
    {
        return $this->picture_student;
    }

    public function setPictureStudent( $picture_student): self
    {
        $this->picture_student = $picture_student;

        return $this;
    }


    public function getFirstConnexion(): ?bool
    {
        return $this->first_connexion;
    }

    public function setFirstConnexion(bool $first_connexion)
    {
        $this->first_connexion = $first_connexion;
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
            $lineProposition->setUser($this);
        }

        return $this;
    }

    public function removeLineProposition(LineProposition $lineProposition): self
    {
        if ($this->linePropositions->removeElement($lineProposition)) {
            // set the owning side to null (unless already changed)
            if ($lineProposition->getUser() === $this) {
                $lineProposition->setUser(null);
            }
        }


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
            $specialty->setUser($this);
        }

        return $this;
    }

    public function removeSpecialty(Specialty $specialty): self
    {
        if ($this->specialties->removeElement($specialty)) {
            // set the owning side to null (unless already changed)
            if ($specialty->getUser() === $this) {
                $specialty->setUser(null);
            }
        }

        return $this;
    }
}
