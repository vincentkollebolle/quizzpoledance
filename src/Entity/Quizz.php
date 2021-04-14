<?php

namespace App\Entity;

use App\Repository\QuizzRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizzRepository::class)
 */
class Quizz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $playername;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @ORM\OneToMany(targetEntity=Playeranswer::class, mappedBy="quizz")
     */
    private $playeranswers;

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
}
