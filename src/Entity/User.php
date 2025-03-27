<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "`user`")] // Change table name
class User implements UserInterface, PasswordAuthenticatedUserInterface{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'string', nullable: true)]
    private $password;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private $registrationCode;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $username;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $validatedRegistrationCodeAt;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    /**
     * @var Collection<int, Business>
     */
    #[ORM\OneToMany(targetEntity: Business::class, mappedBy: 'businessUser')]
    private Collection $businesses;

    public function __construct()
    {
        $this->businesses = new ArrayCollection();
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials() :void
    {
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRegistrationCode(): ?string
    {
        return $this->registrationCode;
    }

    public function setRegistrationCode(string $registrationCode): self
    {
        $this->registrationCode = $registrationCode;

        return $this;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, Business>
     */
    public function getBusinesses(): Collection
    {
        return $this->businesses;
    }

    public function addBusiness(Business $business): static
    {
        if (!$this->businesses->contains($business)) {
            $this->businesses->add($business);
            $business->setBusinessUser($this);
        }

        return $this;
    }

    public function removeBusiness(Business $business): static
    {
        if ($this->businesses->removeElement($business)) {
            // set the owning side to null (unless already changed)
            if ($business->getBusinessUser() === $this) {
                $business->setBusinessUser(null);
            }
        }

        return $this;
    }

    public function getValidatedRegistrationCodeAt(): ?\DateTime
    {
        return $this->validatedRegistrationCodeAt;
    }

    public function setValidatedRegistrationCodeAt(?\DateTime $validatedRegistrationCodeAt): self
    {
        $this->validatedRegistrationCodeAt = $validatedRegistrationCodeAt;

        return $this;
    }
}