<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OutingRepository::class)]
class Outing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $outing_name = null;

    #[ORM\ManyToOne(inversedBy: 'outings')]
    private ?Campus $id_campus = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $outing_date = null;

    #[ORM\ManyToOne(inversedBy: 'outings')]
    private ?City $id_city = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $registration_deadline = null;

    #[ORM\Column]
    private ?int $slots = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'outings')]
    private ?User  $id_organizer = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'outings')]
    private Collection $id_member;

    public function __construct()
    {
        $this->id_member = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getOutingName(): ?string
    {
        return $this->outing_name;
    }

    public function setOutingName(string $outing_name): static
    {
        $this->outing_name = $outing_name;

        return $this;
    }

    public function isUserRegistered(User $user): bool
    {
        return $this->id_member->contains($user);
    }

    public function getIdCampus(): ?Campus
    {
        return $this->id_campus;
    }

    public function setIdCampus(?Campus $id_campus): static
    {
        $this->id_campus = $id_campus;

        return $this;
    }

    public function getOutingDate(): ?DateTimeInterface
    {
        return $this->outing_date;
    }

    public function setOutingDate(DateTimeInterface $outing_date): static
    {
        $this->outing_date = $outing_date;

        return $this;
    }

    public function getIdCity(): ?City
    {
        return $this->id_city;
    }

    public function setIdCity(?City $id_city): static
    {
        $this->id_city = $id_city;

        return $this;
    }

    public function getRegistrationDeadline(): ?DateTimeInterface
    {
        return $this->registration_deadline;
    }

    public function setRegistrationDeadline(DateTimeInterface $registration_deadline): static
    {
        $this->registration_deadline = $registration_deadline;

        return $this;
    }

    public function getSlots(): ?int
    {
        return $this->slots;
    }

    public function setSlots(int $slots): static
    {
        $this->slots = $slots;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getIdOrganizer(): ?User
    {
        return $this->id_organizer;
    }

    public function setIdOrganizer(?User $id_organizer): static
    {
        $this->id_organizer = $id_organizer;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getIdMember(): Collection
    {
        return $this->id_member;
    }

    public function addIdMember(User $idMember): static
    {
        if (!$this->id_member->contains($idMember)) {
            $this->id_member->add($idMember);
        }

        return $this;
    }

    public function removeIdMember(User $idMember): static
    {
        $this->id_member->removeElement($idMember);

        return $this;
    }
}
