<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 */
class Genre {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"movies_find","movies"})
     * @Groups({"shows_find", "shows"})
     * @Groups({"animes_find", "animes"})
     * @Groups({"mangas_find", "mangas"})
     * @Groups({"videogames_find", "videogames"})
     * @Groups({"books_find", "books"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movies_find","movies"})
     * @Groups({"shows_find", "shows"})
     * @Groups({"animes_find", "animes"})
     * @Groups({"mangas_find", "mangas"})
     * @Groups({"videogames_find", "videogames"})
     * @Groups({"books_find", "books"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=Media::class, mappedBy="genres")
     */
    private $medias;

    public function __construct() {
        $this->medias = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->name . ' (' . $this->id . ')';
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

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
            $media->addGenre($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self {
        if ($this->medias->removeElement($media)) {
            $media->removeGenre($this);
        }

        return $this;
    }
}
