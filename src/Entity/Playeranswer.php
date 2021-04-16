<?php

namespace App\Entity;

use App\Repository\PlayeranswerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlayeranswerRepository::class)
 */
class Playeranswer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Choice(
     *     choices="Yes", "No",
     *     message="La valeur doit être 'Yes' ou 'No'."
     * )
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Quizz::class, inversedBy="playeranswers")
     * @Assert\NotBlank
     */
    private $quizz;

    /**
     * @ORM\ManyToOne(targetEntity=Answer::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $pickedanswer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getQuizz(): ?Quizz
    {
        return $this->quizz;
    }

    public function setQuizz(?Quizz $quizz): self
    {
        $this->quizz = $quizz;

        return $this;
    }

    public function getPickedanswer(): ?Answer
    {
        return $this->pickedanswer;
    }

    public function setPickedanswer(?Answer $pickedanswer): self
    {
        $this->pickedanswer = $pickedanswer;

        return $this;
    }
}
