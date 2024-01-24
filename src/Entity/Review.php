<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review {
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"mangas_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $content;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"mangas_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $rating;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"mangas_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Media::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $media;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"mangas_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $user_review;

    /**
     * @ORM\Column(type="boolean", options={"default":"false"})
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"mangas_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $isValidated;

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function setContent(?string $content): self {
        $this->content = $content;

        return $this;
    }

    public function getRating(): ?int {
        return $this->rating;
    }

    public function setRating(int $rating): self {
        $this->rating = $rating;

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

    public function getMedia(): ?Media {
        return $this->media;
    }

    public function setMedia(?Media $media): self {
        $this->media = $media;

        return $this;
    }

    public function getUserReview(): ?User {
        return $this->user_review;
    }

    public function setUserReview(?User $user_review): self {
        $this->user_review = $user_review;

        return $this;
    }

    public function isIsValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): self
    {
        $this->isValidated = $isValidated;

        return $this;
    }
}
