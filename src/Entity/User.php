<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface {

    public function __construct() {
        $this->medias = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

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
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=Media::class, mappedBy="users")
     */
    private $medias;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="user_review", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"mangas_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $username;

    public function getId(): ?int {
        return $this->id;
    }

    public function __toString(){
        return $this->username;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): self {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string {
        return (string) $this->email;
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials() {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string {
        return (string) $this->username;
    }


    public function getCreatedAt(): ?\DateTimeImmutable {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedias() {
        return $this->medias;
    }

    public function addMedia(Media $media): self {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->addUser($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self {
        if ($this->medias->removeElement($media)) {
            $media->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews() {
        return $this->reviews;
    }

    public function addReview(Review $review): self {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setUserReview($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUserReview() === $this) {
                $review->setUserReview(null);
            }
        }

        return $this;
    }

    public function setUsername(string $username): self {
        $this->username = $username;

        return $this;
    }
}
