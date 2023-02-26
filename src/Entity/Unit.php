<?php 
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class Unit {

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=160)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $points;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $movementSpeed;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $wounds;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $weaponSkill;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $ballisticSkill;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $save;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $leadership;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $attacks;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $strength;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $toughness;

    /**
     * @ORM\ManyToOne(targetEntity=Card::class, inversedBy="units")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $card;

    public function __construct() {
        $this->id = Uuid::uuid4();
    }

    public function getId() {
        return $this->id;
    }


    public function getPoints() {
        return $this->points;
    }

    public function getName() {
        return $this->name;
    }

    public function getImage() {
        return $this->image;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function setPoints($points) {
        $this->points = $points;
    }

    public function getWounds() {
        return $this->wounds;
    }

    public function getWeaponSkill() {
        return $this->weaponSkill;
    }

    public function getBallisticSkill() {
        return $this->ballisticSkill;
    }

    public function getSave() {
        return $this->save;
    }

    public function getLeadership() {
        return $this->leadership;
    }

    public function getAttacks() {
        return $this->attacks;
    }

    public function getStrength() {
        return $this->strength;
    }

    public function getToughness() {
        return $this->toughness;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getCard() {
        return $this->card;
    }

    public function setWounds($wounds) {
        $this->wounds = $wounds;
    }

    public function setWeaponSkill($weaponSkill) {
        $this->weaponSkill = $weaponSkill;
    }

    public function setBallisticSkill($ballisticSkill) {
        $this->ballisticSkill = $ballisticSkill;
    }

    public function setAttacks($attacks) {
        $this->attacks = $attacks;
    }

    public function setSave($save) {
        $this->save = $save;
    }

    public function setLeadership($leadership) {
        $this->leadership = $leadership;
    }

    public function setStrength($strength) {
        $this->strength = $strength;
    }

    public function setToughness($toughness) {
        $this->toughness = $toughness;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setCard($card) {
        $this->card = $card;
    }

    public function getMovementSpeed() {
        return $this->movementSpeed;
    }

    public function setMovementSpeed($speed) {
        $this->movementSpeed = $speed;
    }

    public function __clone()
    {
        $this->id = Uuid::uuid4();
    }
}
