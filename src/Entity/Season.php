<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SeasonRepository::class)
 */
class Season {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     */
    private $season_number;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"shows_find"})
     * @Groups({"animes_find"})
     */
    private $nb_episodes;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Media::class, inversedBy="seasons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $media;

    public function __toString()
    {
        return (string)$this->season_number;
    }
    public function getId(): ?int {
        return $this->id;
    }

    public function getSeasonNumber(): ?int {
        return $this->season_number;
    }

    public function setSeasonNumber(int $season_number): self {
        $this->season_number = $season_number;

        return $this;
    }

    public function getNbEpisodes(): ?int {
        return $this->nb_episodes;
    }

    public function setNbEpisodes(int $nb_episodes): self {
        $this->nb_episodes = $nb_episodes;

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
}
