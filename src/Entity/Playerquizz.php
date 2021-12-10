<?php

namespace App\Entity;

use App\Repository\PlayerquizzRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlayerquizzRepository::class)
 */
class Playerquizz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner votre nom de joueur.")
     */
    private $playername;


    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez renseigner la difficulté du Playerquizz.")
     */
    public $difficulty;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @ORM\OneToMany(targetEntity=Playeranswer::class, mappedBy="quizz")
     */
    private $playeranswers;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"unsigned":true, "default":1})
     */
    private $combo;

    public function __construct()
    {
        $this->playeranswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayername(): ?string
    {
        return $this->playername;
    }

    public function setPlayername(string $playername): self
    {
        $this->playername = $playername;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return Collection|Playeranswer[]
     */
    public function getPlayeranswers(): Collection
    {
        return $this->playeranswers;
    }

    public function addPlayeranswer(Playeranswer $playeranswer): self
    {
        if (!$this->playeranswers->contains($playeranswer)) {
            $this->playeranswers[] = $playeranswer;
            $playeranswer->setQuizz($this);
        }

        return $this;
    }

    public function removePlayeranswer(Playeranswer $playeranswer): self
    {
        if ($this->playeranswers->removeElement($playeranswer)) {
            // set the owning side to null (unless already changed)
            if ($playeranswer->getQuizz() === $this) {
                $playeranswer->setQuizz(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCombo(): ?int
    {
        return $this->combo;
    }

    public function setCombo(?int $combo): self
    {
        $this->combo = $combo;

        return $this;
    }
}
