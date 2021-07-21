<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 * @ApiResource()
 */
class Question
{
    /**
     * @var string
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    private $slug;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(message="{{ value }} n'est pas une url valide")
     */
    private $mediaurl;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez remplir le champ")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner le titre de la question.")
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity=Answer::class)
     */
    private $answers;

    /**
     * @ORM\ManyToOne(targetEntity=Answer::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez renseigner la bonne réponse")
     */
    private $goodanswer;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMediaurl(): ?string
    {
        return $this->mediaurl;
    }

    public function setMediaurl(?string $mediaurl): self
    {
        $this->mediaurl = $mediaurl;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function __toString()
    {
        return $this->content;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        $this->answers->removeElement($answer);

        return $this;
    }

    public function getGoodanswer(): ?Answer
    {
        return $this->goodanswer;
    }

    public function setGoodanswer(?Answer $goodanswer): self
    {
        $this->goodanswer = $goodanswer;

        return $this;
    }
}
