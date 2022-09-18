<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class CardProperty
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=160)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $propertyKey;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $propertyType;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $propertyValue;

    /**
     * @ORM\ManyToOne(targetEntity=Card::class, inversedBy="properties")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $card;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
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
    public function getPropertyKey()
    {
        return $this->propertyKey;
    }

    /**
     * @param mixed $propertyKey
     */
    public function setPropertyKey($propertyKey): void
    {
        $this->propertyKey = $propertyKey;
    }

    /**
     * @return mixed
     */
    public function getPropertyType()
    {
        return $this->propertyType;
    }

    /**
     * @param mixed $propertyType
     */
    public function setPropertyType($propertyType): void
    {
        $this->propertyType = $propertyType;
    }

    /**
     * @return mixed
     */
    public function getPropertyValue()
    {
        $decoded = json_decode($this->propertyValue, true);
        if($decoded !== null) {
            return $decoded;
        } else {
            return $this->propertyValue;
        }
    }

    /**
     * @param mixed $propertyValue
     */
    public function setPropertyValue($propertyValue): void
    {
        if(!is_string($propertyValue)) {
            $propertyValue = json_encode($propertyValue);
        }
        $this->propertyValue = $propertyValue;
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param Card $card
     */
    public function setCard(Card $card): void
    {
        $this->card = $card;
    }
}
