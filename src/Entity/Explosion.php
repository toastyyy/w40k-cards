<?php 
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class Explosion implements SelectionInterface {

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=160)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $diceRoll;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $distance;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $mortalWounds;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ref;

    /**
     * @ORM\ManyToOne(targetEntity=Card::class, inversedBy="psykers")
     */
    private $card;


    public function __construct() {
        $this->id = Uuid::uuid4();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDiceRoll() {
        return $this->diceRoll;
    }

    public function getDistance() {
        return $this->distance;
    }

    public function getMortalWounds() {
        return $this->mortalWounds;
    }

    public function getRef() {
        return $this->ref;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDiceRoll($diceRoll) {
        $this->diceRoll = $diceRoll;
    }

    public function setDistance($distance) {
        $this->distance = $distance;
    }

    public function setMortalWounds($mortalWounds) {
        $this->mortalWounds = $mortalWounds;
    }

    public function setRef($ref) {
        $this->ref = $ref;
    }

    public function getCard() {
        return $this->card;
    }

    public function setCard($card) {
        $this->card = $card;
    }

    public function __clone()
    {
        $this->id = Uuid::uuid4();
    }
}
