<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 * specifying table name because character is a reserved word in SQL
 * @ORM\Table(name="`character`")
 */
class Character {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"mangas_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"mangas_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $role;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=Media::class, mappedBy="characters")
     */
    private $medias;

    /**
     * @ORM\ManyToMany(targetEntity=Actor::class, inversedBy="characters")
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"videogames_find"})
     */
    private $actors;

    public function __construct() {
        $this->medias = new ArrayCollection();
        $this->actors = new ArrayCollection();
    }

    public function __toString() {
        return $this->role . ' (' . $this->id . ')';
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getRole(): ?string {
        return $this->role;
    }

    public function setRole(string $role): self {
        $this->role = $role;

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
     * @return Collection<int, Media>
     */
    public function getMedias(): Collection {
        return $this->medias;
    }

    public function addMedia(Media $media): self {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->addCharacter($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self {
        if ($this->medias->removeElement($media)) {
            $media->removeCharacter($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Actor>
     */
    public function getActors(): Collection {
        return $this->actors;
    }

    public function addActor(Actor $actor): self {
        if (!$this->actors->contains($actor)) {
            $this->actors[] = $actor;
        }

        return $this;
    }

    public function removeActor(Actor $actor): self {
        $this->actors->removeElement($actor);

        return $this;
    }
}
