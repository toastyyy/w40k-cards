<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class Psyker {

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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $cast;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $deny;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $other;

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

    public function getCast() {
        return $this->cast;
    }

    public function getDeny() {
        return $this->deny;
    }

    public function getOther() {
        return $this->other;
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

    public function setCast($cast) {
        $this->cast = $cast;
    }

    public function setDeny($deny) {
        $this->deny = $deny;
    }

    public function setOther($other) {
        $this->other = $other;
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
