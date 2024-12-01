<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_activity = null;

    #[ORM\Column(length: 255)]
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

    #[ORM\Column(length: 255,  nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

  
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'customerActivities')]
    private $customerUsers;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company_website = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $start_coord = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $end_coord = null;

    #[ORM\Column(length: 255, nullable: true, options: ['default' => 'Disponible'])]
    private ?string $state;

    #[ORM\Column(nullable: true)]
    private ?int $scores = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 2, scale: 1, nullable: true)]
    private ?string $average_score = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $scorelist = null;

    public function __construct()
    {
        $this->customerUsers = new ArrayCollection();
        $this->scores = 0;
        $this->average_score = '0.0';
    }
    
   

    public function getIdActivity(): ?int
    {
        return $this->id_activity;
    }

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCustomerUsers(): Collection
    {
        return $this->customerUsers;
    }

    public function addCustomerUser(User $user): self
    {
        if (!$this->customerUsers->contains($user)) {
            $this->customerUsers->add($user);
            $user->addCustomerActivity($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->customerUsers->removeElement($user)) {
            $user->removeCustomerActivity($this);
        }

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(?string $company_name): self
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getCompanyWebsite(): ?string
    {
        return $this->company_website;
    }

    public function setCompanyWebsite(?string $company_website): self
    {
        $this->company_website = $company_website;

        return $this;
    }

    public function getStartCoord(): ?string
    {
        return $this->start_coord;
    }

    public function setStartCoord(?string $start_coord): self
    {
        $this->start_coord = $start_coord;

        return $this;
    }

    public function getEndCoord(): ?string
    {
        return $this->end_coord;
    }

    public function setEndCoord(?string $end_coord): self
    {
        $this->end_coord = $end_coord;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getScores(): ?int
    {
        return $this->scores;
    }

    public function setScores(?int $scores): static
    {
        $this->scores = $scores;

        return $this;
    }

    public function getAverageScore(): ?string
    {
        return $this->average_score;
    }

    public function setAverageScore(?string $average_score): static
    {
        $this->average_score = $average_score;

        return $this;
    }

    public function addScore(float $score): self
{
    if (null === $this->scorelist) {
        $this->scorelist = [];
    }

    $this->scorelist[] = round($score, 1);

    return $this;
}
public function getScoreList(): ?array
{
    return $this->scorelist;
}
}
