<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reponse;

    /**
     * @ORM\Column(type="smallint")
     */
    private $reponse_expected;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="reponses", fetch="EAGER")
     * @ORM\JoinColumn(name="id_question", nullable=false)
     */
    private $question;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getid_question(): ?int
    {
        return $this->id_question;
    }
    
    public function getIdQuestion(): ?int
    {
        return $this->id_question;
    }

    public function setId_question(int $id_question): self
    {
        $this->id_question = $id_question;

        return $this;
    }

    public function setIdQuestion(int $id_question): self
    {
        $this->id_question = $id_question;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getreponse_expected(): ?int
    {
        return $this->reponse_expected;
    }

    public function getReponseExpected(): ?int
    {
        return $this->reponse_expected;
    }

    public function setreponse_expected(int $reponse_expected): self
    {
        $this->reponse_expected = $reponse_expected;

        return $this;
    }

    public function setReponseExpected(int $reponse_expected): self
    {
        $this->reponse_expected = $reponse_expected;

        return $this;
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
}
