<?php 
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class Weapon {

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=160)
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="wpnname", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", name="wpnrange", length=255, nullable=true)
     */
    private $range;

    /**
     * @ORM\Column(type="string", name="wpntype", length=255, nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $strength;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $penetration;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $damage;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $abilities;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ref;

    /**
     * @ORM\ManyToOne(targetEntity=Card::class, inversedBy="weapons")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $card;

    public function __construct() {
        $this->id = Uuid::uuid4();
    }

    public function getId() {
        return $this->id;
    }

    public function getUnit() {
        return $this->unit;
    }

    public function getName() {
        return $this->name;
    }

    public function getRange() {
        return $this->range;
    }

    public function getType() {
        return $this->type;
    }

    public function getStrength() {
        return $this->strength;
    }

    public function getPenetration() {
        return $this->penetration;
    }

    public function getDamage() {
        return $this->damage;
    }

    public function getAbilities() {
        return $this->abilities;
    }

    public function getRef() {
        return $this->ref;
    }

    public function getCard() {
        return $this->card;
    }


    public function setName($name) {
        $this->name = $name;
    }

    public function setRange($range) {
        $this->range = $range;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setStrength($strength) {
        $this->strength = $strength;
    }

    public function setPenetration($penetration) {
        $this->penetration = $penetration;
    }

    public function setDamage($damage) {
        $this->damage = $damage;
    }

    public function setAbilities($abilities) {
        $this->abilities = $abilities;
    }

    public function setRef($ref) {
        $this->ref = $ref;
    }

    public function setCard($card) {
        $this->card = $card;
    }
}