<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_activity = null;

    // #[ORM\Column]
    // private ?int $id_activity = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $activity_name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $tickets = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $start_ubication = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $end_ubication = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $price = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user', nullable: false)]
    private ?User $id_user = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    public function getIdActivity(): ?int
    {
        return $this->id_activity;
    }

    // public function getIdActivity(): ?int
    // {
    //     return $this->id_activity;
    // }

    // public function setIdActivity(int $id_activity): self
    // {
    //     $this->id_activity = $id_activity;

    //     return $this;
    // }

    public function getActivityName(): ?string
    {
        return $this->activity_name;
    }

    public function setActivityName(string $activity_name): self
    {
        $this->activity_name = $activity_name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTickets(): ?int
    {
        return $this->tickets;
    }

    public function setTickets(?int $tickets): self
    {
        $this->tickets = $tickets;

        return $this;
    }

    public function getStartUbication(): ?string
    {
        return $this->start_ubication;
    }

    public function setStartUbication(?string $start_ubication): self
    {
        $this->start_ubication = $start_ubication;

        return $this;
    }

    public function getEndUbication(): ?string
    {
        return $this->end_ubication;
    }

    public function setEndUbication(?string $end_ubication): self
    {
        $this->end_ubication = $end_ubication;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
