<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=160)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000, nullable=false)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity=Card::class, inversedBy="categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $card;

    public function __construct() {
        $this->id = Uuid::uuid4();
    }
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): self
    {
        $this->card = $card;

        return $this;
    }
}
