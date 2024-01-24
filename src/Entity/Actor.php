<?php

namespace App\Entity;

use App\Repository\ActorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ActorRepository::class)
 */
class Actor {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"videogames_find"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"videogames_find"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"videogames_find"})
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"videogames_find"})
     */
    private $nationality;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=Character::class, mappedBy="actors")
     */
    private $characters;

    public function __construct() {
        $this->characters = new ArrayCollection();
    }

    public function __toString() {
        return $this->lastname . ' ' . $this->firstname . ' (' . $this->id . ')';
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getFirstname(): ?string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPicture(): ?string {
        return $this->picture;
    }

    public function setPicture(string $picture): self {
        $this->picture = $picture;

        return $this;
    }

    public function getNationality(): ?string {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self {
        $this->nationality = $nationality;

        return $this;
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
     * @return Collection<int, Character>
     */
    public function getCharacters(): Collection {
        return $this->characters;
    }

    public function addCharacter(Character $character): self {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->addActor($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self {
        if ($this->characters->removeElement($character)) {
            $character->removeActor($this);
        }

        return $this;
    }
}
