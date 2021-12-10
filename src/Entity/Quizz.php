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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="quizz", orphanRemoval=true)
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=Playerquizz::class, mappedBy="quizz")
     */
    private $playerquizzs;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->playerquizzs = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setQuizz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuizz() === $this) {
                $question->setQuizz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Playerquizz[]
     */
    public function getPlayerquizzs(): Collection
    {
        return $this->playerquizzs;
    }

    public function addPlayerquizz(Playerquizz $playerquizz): self
    {
        if (!$this->playerquizzs->contains($playerquizz)) {
            $this->playerquizzs[] = $playerquizz;
            $playerquizz->setQuizz($this);
        }

        return $this;
    }

    public function removePlayerquizz(Playerquizz $playerquizz): self
    {
        if ($this->playerquizzs->removeElement($playerquizz)) {
            // set the owning side to null (unless already changed)
            if ($playerquizz->getQuizz() === $this) {
                $playerquizz->setQuizz(null);
            }
        }

        return $this;
    }


}
