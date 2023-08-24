<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Ya existe una cuenta con este email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_user = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $user_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $user_lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company_website = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dni = null;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: Activity::class)]
    private Collection $activities;

    #[ORM\ManyToMany(targetEntity: Activity::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'user_activity')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id_user')]
    #[ORM\InverseJoinColumn(name: 'activity_id', referencedColumnName: 'id_activity')]

    private Collection $customerActivities;

    public function __construct()
    {
        $this->roles = [];
        $this->activities = new ArrayCollection();
        $this->customerActivities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id_user;
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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function setUserName(string $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getUserLastname(): ?string
    {
        return $this->user_lastname;
    }

    public function setUserLastname(?string $user_lastname): self
    {
        $this->user_lastname = $user_lastname;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

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

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(?string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setIdUser($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->removeElement($activity)) {
            if ($activity->getIdUser() === $this) {
                $activity->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getCustomerActivities(): Collection
    {
        return $this->customerActivities;
    }

    public function addCustomerActivity(Activity $activity): self
    {
        if (!$this->customerActivities->contains($activity)) {
            $this->customerActivities->add($activity);
            $activity->addCustomerUser($this);
        }

        return $this;
    }

    public function removeCustomerActivity(Activity $activity): self
    {
        if ($this->customerActivities->removeElement($activity)) {
            $activity->removeUser($this);
        }

        return $this;
    }
}
