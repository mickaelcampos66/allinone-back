<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="category", orphanRemoval=true)
     */
    private $medias;

    public function __construct() {
        $this->medias = new ArrayCollection();
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
            $media->setCategory($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self {
        if ($this->medias->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getCategory() === $this) {
                $media->setCategory(null);
            }
        }

        return $this;
    }
}
