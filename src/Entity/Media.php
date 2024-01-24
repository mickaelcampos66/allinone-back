<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 */
class Media {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"movies", "movies_find"})
     * @Groups({"shows", "shows_find"})
     * @Groups({"animes", "animes_find"})
     * @Groups({"mangas", "mangas_find"})
     * @Groups({"videogames", "videogames_find"})
     * @Groups({"books", "books_find"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movies", "movies_find"})
     * @Groups({"shows", "shows_find"})
     * @Groups({"animes", "animes_find"})
     * @Groups({"mangas", "mangas_find"})
     * @Groups({"videogames", "videogames_find"})
     * @Groups({"books", "books_find"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"movies", "movies_find"})
     * @Groups({"shows", "shows_find"})
     * @Groups({"animes", "animes_find"})
     * @Groups({"mangas", "mangas_find"})
     * @Groups({"videogames", "videogames_find"})
     * @Groups({"books", "books_find"})
     */
    private $summary;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"movies", "movies_find"})
     * @Groups({"shows", "shows_find"})
     * @Groups({"animes", "animes_find"})
     * @Groups({"mangas", "mangas_find"})
     * @Groups({"videogames", "videogames_find"})
     * @Groups({"books", "books_find"})
     */
    private $release_date;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"mangas_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $synopsis;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movies", "movies_find"})
     * @Groups({"shows", "shows_find"})
     * @Groups({"animes", "animes_find"})
     * @Groups({"mangas", "mangas_find"})
     * @Groups({"videogames", "videogames_find"})
     * @Groups({"books", "books_find"})
     */
    private $picture;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"movies_find"})
     */
    private $duration;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"mangas_find"})
     * @Groups({"books_find"})
     */
    private $nb_pages;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=Platform::class, inversedBy="medias")
     * @Groups({"videogames_find", "videogames"})
     */
    private $platforms;

    /**
     * @ORM\OneToMany(targetEntity=Season::class, mappedBy="media", orphanRemoval=true)
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     */
    private $seasons;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="medias",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"movies_find"})
     * @Groups({"shows_find"}) 
     * @Groups({"animes_find"})
     * @Groups({"mangas_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Character::class, inversedBy="medias")
     * @Groups({"movies_find"})
     * @Groups({"shows_find"}) 
     * @Groups({"animes_find"})
     * @Groups({"mangas_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $characters;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="medias")
     * @Groups({"movies", "movies_find"})
     * @Groups({"shows", "shows_find"})
     * @Groups({"animes", "animes_find"})
     * @Groups({"mangas", "mangas_find"})
     * @Groups({"videogames", "videogames_find"})
     * @Groups({"books", "books_find"})
     */
    private $genres;

    /**
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="medias")
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"mangas_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $authors;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="medias")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="media", orphanRemoval=true)
     * @Groups({"movies_find"})
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     * @Groups({"mangas_find"})
     * @Groups({"videogames_find"})
     * @Groups({"books_find"})
     */
    private $reviews;

    public function __construct() {
        $this->platforms = new ArrayCollection();
        $this->seasons = new ArrayCollection();
        $this->characters = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function __toString() {
        return $this->title . ' (' . $this->id . ')';
    }

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

    public function getSummary(): ?string {
        return $this->summary;
    }

    public function setSummary(string $summary): self {
        $this->summary = $summary;

        return $this;
    }

    public function getReleaseDate(): ?int {
        return $this->release_date;
    }

    public function setReleaseDate(int $release_date): self {
        $this->release_date = $release_date;

        return $this;
    }

    public function getSynopsis(): ?string {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getPicture(): ?string {
        return $this->picture;
    }

    public function setPicture(string $picture): self {
        $this->picture = $picture;

        return $this;
    }

    public function getDuration(): ?int {
        return $this->duration;
    }

    public function setDuration(?int $duration): self {
        $this->duration = $duration;

        return $this;
    }

    public function getNbPages(): ?int {
        return $this->nb_pages;
    }

    public function setNbPages(?int $nb_pages): self {
        $this->nb_pages = $nb_pages;

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
     * @return Collection<int, Platform>
     */
    public function getPlatforms(): Collection {
        return $this->platforms;
    }

    public function addPlatform(Platform $platform): self {
        if (!$this->platforms->contains($platform)) {
            $this->platforms[] = $platform;
        }

        return $this;
    }

    public function removePlatform(Platform $platform): self {
        $this->platforms->removeElement($platform);

        return $this;
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection {
        return $this->seasons;
    }

    public function addSeason(Season $season): self {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setMedia($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getMedia() === $this) {
                $season->setMedia(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category {
        return $this->category;
    }

    public function setCategory(?Category $category): self {
        $this->category = $category;

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
        }

        return $this;
    }

    public function removeCharacter(Character $character): self {
        $this->characters->removeElement($character);

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection {
        return $this->authors;
    }

    public function addAuthor(Author $author): self {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self {
        $this->authors->removeElement($author);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection {
        return $this->users;
    }

    public function addUser(User $user): self {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection {
        return $this->reviews;
    }

    public function addReview(Review $review): self {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setMedia($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getMedia() === $this) {
                $review->setMedia(null);
            }
        }

        return $this;
    }
}
