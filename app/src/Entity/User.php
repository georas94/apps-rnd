<?php

namespace App\Entity;

//use App\Entity\Traits\TimestampTrait;
//use App\Enum\UserRole;
//use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
//use App\Validator\Constraints as AppAssert;

#[HasLifecycleCallbacks]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Un utilisateur possédant le même email existe déjà')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
//    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 20)]
//    #[AppAssert\BurkinaPhone]
    private ?string $phoneNumber = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $isValid = false;

//    #[ORM\ManyToOne(inversedBy: 'users')]
//    private ?Company $company = null;
//
//    #[ORM\JoinTable(name: "user_ao_liked")]
//    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
//    #[ORM\InverseJoinColumn(name: "ao_id", referencedColumnName: "id")]
//    #[ORM\ManyToMany(targetEntity: AO::class, inversedBy: 'likedByUsers')]
//    private Collection $likedAos;
//
//    public function __construct()
//    {
//        $this->likedAos = new ArrayCollection();
//    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
        return array_unique($roles);
    }
//
//    public function rolesLibelles(): array
//    {
//        $roles = [];
//        foreach ($this->roles as $role) {
//            $roles[] = UserRole::from($role)->label();
//        }
//
//        return array_unique($roles);
//    }
    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }
    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(?bool $isValid): void
    {
        $this->isValid = $isValid;
    }
//
//    public function getCompany(): ?Company
//    {
//        return $this->company;
//    }
//
//    public function setCompany(?Company $company): static
//    {
//        $this->company = $company;
//
//        return $this;
//    }
//
//    /**
//     * @return Collection<int, AO>
//     */
//    public function getLikedAos(): Collection
//    {
//        return $this->likedAos;
//    }
//
//    public function addLikedAo(AO $likedAo): static
//    {
//        if (!$this->likedAos->contains($likedAo)) {
//            $this->likedAos->add($likedAo);
//        }
//
//        return $this;
//    }
//
//    public function removeLikedAo(AO $likedAo): static
//    {
//        $this->likedAos->removeElement($likedAo);
//
//        return $this;
//    }
}
