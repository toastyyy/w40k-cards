<?php 
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class PsychicPower {

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=160)
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="psyname", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $warpCharge;

    /**
     * @ORM\Column(type="string", name="psyrange", length=50, nullable=true)
     */
    private $range;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $details;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ref;

    /**
     * @ORM\ManyToOne(targetEntity=Card::class, inversedBy="psychicPowers")
     * @ORM\JoinColumn(onDelete="CASCADE")
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

    public function getWarpCharge() {
        return $this->warpCharge;
    }

    public function getRange() {
        return $this->range;
    }

    public function getDetails() {
        return $this->details;
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

    public function setWarpCharge($warpCharge) {
        $this->warpCharge = $warpCharge;
    }

    public function setDetails($details) {
        $this->details = $details;
    }

    public function setRef($ref) {
        $this->ref = $ref;
    }

    public function setCard($card) {
        $this->card = $card;
    }

    public function __clone()
    {
        $this->id = Uuid::uuid4();
    }
}
