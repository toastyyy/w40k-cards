<?php 
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class Ability {

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=160)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Card::class, inversedBy="abilities")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $card;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ref;

    public function __construct() {
        $this->id = Uuid::uuid4();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
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

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setRef($ref) {
        $this->ref = $ref;
    }

    public function setCard($card) {
        $this->card = $card;
    }
}