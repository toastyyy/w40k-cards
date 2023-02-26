<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class Roster
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=160)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $customName;

    /**
     * @ORM\OneToMany(targetEntity=Card::class, mappedBy="roster", cascade={"ALL"})
     */
    private $cards;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->cards = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCustomName()
    {
        return $this->customName;
    }

    /**
     * @param mixed $customName
     */
    public function setCustomName($customName): void
    {
        $this->customName = $customName;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * @param Collection|Card[] $cards
     */
    public function setCards($cards): void
    {
        foreach($cards as $c) {
            if(!$this->cards->contains($c)) {
                $this->cards->add($c);
                $c->setRoster($this);
            }
        }

        foreach($this->cards as $c) {
            if(!$cards->contains($c)) {
                $this->cards->removeElement($c);
                $c->setRoster(null);
            }
        }
    }

    public function addCard(Card $card) {
        $this->cards->add($card);
        $card->setRoster($this);
    }
}
