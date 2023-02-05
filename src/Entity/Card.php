<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class Card
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=160)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subtitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $quote;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $points;

    /**
     * @ORM\OneToMany(targetEntity=CardProperty::class, mappedBy="card", cascade={"ALL"})
     */
    private $properties;

    /**
     * @ORM\ManyToOne(targetEntity=Roster::class, inversedBy="cards")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $roster;

    /**
     * @ORM\OneToMany(targetEntity=Weapon::class, mappedBy="card", cascade={"ALL"})
     */
    private $weapons;

    /**
     * @ORM\OneToMany(targetEntity=Ability::class, mappedBy="card", cascade={"ALL"})
     */
    private $abilities;

    /**
     * @ORM\OneToMany(targetEntity=PsychicPower::class, mappedBy="card", cascade={"ALL"})
     */
    private $psychicPowers;

    /**
     * @ORM\OneToMany(targetEntity=Explosion::class, mappedBy="card", cascade={"ALL"})
     */
    private $explosions;

     /**
     * @ORM\OneToMany(targetEntity=Psyker::class, mappedBy="card", cascade={"ALL"})
     */
    private $psykers;

     /**
     * @ORM\OneToMany(targetEntity=Unit::class, mappedBy="card", cascade={"ALL"})
     */
    private $units;

    /**
     *@ORM\ManyToOne(targetEntity=Medium::class, cascade={"ALL"})
     */
    private $factionLogo;

    /**
     *@ORM\ManyToOne(targetEntity=Medium::class, cascade={"ALL"})
     */
    private $backgroundImage;

    /**
     *@ORM\ManyToOne(targetEntity=Medium::class, cascade={"ALL"})
     */
    private $boxBackground;

    /**
     *@ORM\ManyToOne(targetEntity=Medium::class, cascade={"ALL"})
     */
    private $unitImage;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $textColor;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $borderColor;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $keywords;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $useAutomaticBackgroundRemoval;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $imageTranslateX;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $imageTranslateY;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $imageScale;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->useAutomaticBackgroundRemoval = true;
        $this->keywords = [];
        $this->properties = new ArrayCollection();
        $this->weapons = new ArrayCollection();
        $this->abilities = new ArrayCollection();
        $this->psykers = new ArrayCollection();
        $this->psychicPowers = new ArrayCollection();
        $this->explosions = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->imageTranslateX = 0.0;
        $this->imageTranslateY = 0.0;
        $this->imageScale = 0.0;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return CardProperty[]|Collection
     */
    public function getProperties()
    {
        return $this->properties;
    }

    public function setProperty($key, $value, $type = null) {
        $property = $this->getProperty($key);
        if($property == null) {
            $property = new CardProperty();
            $this->properties->add($property);
            $property->setCard($this);
            $property->setPropertyKey($key);
        }
        $property->setPropertyValue($value);
        $property->setPropertyType($type);
    }

    /**
     * @param Collection|CardProperty[] $properties
     */
    public function setProperties($properties): void
    {
        foreach($properties as $p) {
            if(!$this->properties->contains($p)) {
                $this->properties->add($p);
                $p->setCard($this);
            }
        }
        foreach($this->properties as $p) {
            if(!$properties->contains($p)) {
                $this->properties->removeElement($p);
                $p->setCard(null);
            }
        }
    }

    public function getProperty($name) {
        foreach($this->getProperties() as $property) {
            if($property->getPropertyKey() == $name) {
                return $property;
            }
        }
        return null;
    }

    public function getPropertyValue($name) {
        $property = $this->getProperty($name);
        if($property) {
            return $property->getPropertyValue();
        }
        return null;
    }

    public function addProperty($name, $value) {
        $property = $this->getProperty($name);
        if($property == null) {
            $property = new CardProperty();
            $property->setPropertyKey($name);
            $this->properties->add($property);
            $property->setCard($this);
        }
        $property->setPropertyValue($value);
    }

    public function removeProperty($name) {
        foreach($this->properties as $prop) {
            if($prop->getPropertyKey() == $name) {
                $this->properties->removeElement($prop);
                $prop->setCard(null);
            }
        }
    }

    /**
     * @return Roster
     */
    public function getRoster()
    {
        return $this->roster;
    }

    /**
     * @param Roster $roster
     */
    public function setRoster(Roster $roster): void
    {
        $this->roster = $roster;
    }

    public function getWeapons() {
        return $this->weapons;
    }

    public function setWeapons($weapons) {
        if(is_array($weapons)) {
            $weapons = new ArrayCollection($weapons);
        }
        foreach($this->weapons as $weapon) {
            if(!$weapons->contains($weapon)) {
                $this->weapons->removeElement($weapon);
                $weapon->setUnit(null);
            }
        }
        foreach($weapons as $weapon) {
            if(!$this->weapons->contains($weapon)) {
                $this->weapons->add($weapon);
                $weapon->setUnit($this);
            }
        }
    }

    public function setAbilities($abilities) {
        if(is_array($abilities)) {
            $abilities = new ArrayCollection($abilities);
        }
        foreach($this->abilities as $ability) {
            if(!$abilities->contains($ability)) {
                $this->abilities->removeElement($ability);
                $ability->setUnit(null);
            }
        }
        foreach($abilities as $ability) {
            if(!$this->abilities->contains($ability)) {
                $this->abilities->add($ability);
                $ability->setUnit($this);
            }
        }
    }

    public function getAbilities() {
        return $this->abilities;
    }

    public function setPsychicPowers($psies) {
        if(is_array($psies)) {
            $psies = new ArrayCollection($psies);
        }
        foreach($this->psychicPowers as $psy) {
            if(!$psies->contains($psy)) {
                $this->psychicPowers->removeElement($psy);
                $psy->setCard(null);
            }
        }
        foreach($psies as $psy) {
            if(!$this->psychicPowers->contains($psy)) {
                $this->psychicPowers->add($psy);
                $psy->setCard($this);
            }
        }
    }

    public function setPsykers($psykers) {
        if(is_array($psykers)) {
            $psykers = new ArrayCollection($psykers);
        }
        foreach($this->psykers as $psyker) {
            if(!$psykers->contains($psyker)) {
                $this->psykers->removeElement($psyker);
                $psyker->setCard(null);
            }
        }
        foreach($psykers as $psyker) {
            if(!$this->psykers->contains($psyker)) {
                $this->psykers->add($psyker);
                $psyker->setCard($this);
            }
        }
    }

    public function setExplosions($explosions) {
        if(is_array($explosions)) {
            $explosions = new ArrayCollection($explosions);
        }
        foreach($this->explosions as $explosion) {
            if(!$explosions->contains($explosion)) {
                $this->explosions->removeElement($explosion);
                $explosion->setUnit(null);
            }
        }
        foreach($explosions as $explosion) {
            if(!$this->explosions->contains($explosion)) {
                $this->explosions->add($explosion);
                $explosion->setUnit($this);
            }
        }
    }

    public function setUnits($units) {
        if(is_array($units)) {
            $units = new ArrayCollection($units);
        }
        foreach($this->units as $unit) {
            if(!$units->contains($unit)) {
                $this->units->removeElement($unit);
                $unit->setUnit(null);
            }
        }
        foreach($units as $unit) {
            if(!$this->units->contains($unit)) {
                $this->units->add($unit);
                $unit->setUnit($this);
            }
        }
    }

    public function getUnits() {
        return $this->units;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getPoints() {
        return $this->points;
    }

    public function setPoints($points) {
        $this->points = $points;
    }

    public function addUnit(Unit $unit) {
        if(!$this->units->contains($unit)) {
            $this->units->add($unit);
            $unit->setCard($this);
        }
    }

    public function addAbility(Ability $ability) {
        if(!$this->abilities->contains($ability)) {
            $this->abilities->add($ability);
            $ability->setCard($this);
        }
    }    

    public function addPsyker(Psyker $psyker) {
        if(!$this->psykers->contains($psyker)) {
            $this->psykers->add($psyker);
            $psyker->setCard($this);
        }
    }
    
    public function addWeapon(Weapon $weapon) {
        if(!$this->weapons->contains($weapon)) {
            $this->weapons->add($weapon);
            $weapon->setCard($this);
        }
    }

    public function addPsychicPower(PsychicPower $psy) {
        if(!$this->psychicPowers->contains($psy)) {
            $this->psychicPowers->add($psy);
            $psy->setCard($this);
        }
    }   

    public function getPsykers() {
        return $this->psykers;
    }

    public function getPsychicPowers() {
        return $this->psychicPowers;
    }

    public function getExplosions() {
        return $this->explosions;
    }

    public function getQuote() {
        return $this->quote;
    }

    public function setQuote($quote) {
        $this->quote = $quote;
    }

    public function setFactionLogo($factionLogo) {
        $this->factionLogo = $factionLogo;
    }

    public function getFactionLogo() {
        return $this->factionLogo;
    }

    public function setBackgroundImage($backgroundImage) {
        $this->backgroundImage = $backgroundImage;
    }

    public function getBackgroundImage() {
        return $this->backgroundImage;
    }

    public function getBoxBackground() {
        return $this->boxBackground;
    }

    public function setBoxBackground($boxBackground) {
        $this->boxBackground = $boxBackground;
    }

    public function setUnitImage($unitImage) {
        $this->unitImage = $unitImage;
    }

    public function getUnitImage() {
        return $this->unitImage;
    }

    public function getTextColor() {
        return $this->textColor;
    }

    public function setTextColor($textColor) {
        $this->textColor = $textColor;
    }

    public function getBorderColor() {
        return $this->borderColor;
    }

    public function setBorderColor($borderColor) {
        $this->borderColor = $borderColor;
    }

    /**
     * @return mixed
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param mixed $subtitle
     */
    public function setSubtitle($subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    /**
     * @return array
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    /**
     * @param array $keywords
     */
    public function setKeywords(array $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * @return bool
     */
    public function isUseAutomaticBackgroundRemoval()
    {
        if($this->useAutomaticBackgroundRemoval === null) { return false; }
        return $this->useAutomaticBackgroundRemoval;
    }

    /**
     * @param bool $useAutomaticBackgroundRemoval
     */
    public function setUseAutomaticBackgroundRemoval(bool $useAutomaticBackgroundRemoval): void
    {
        $this->useAutomaticBackgroundRemoval = $useAutomaticBackgroundRemoval;
    }

    public function getImageTranslateX()
    {
        if(!$this->imageTranslateX) { return 0; }
        return $this->imageTranslateX;
    }

    public function setImageTranslateX($imageTranslateX): void
    {
        $this->imageTranslateX = $imageTranslateX;
    }

    public function getImageTranslateY()
    {
        if(!$this->imageTranslateY) { return 0; }
        return $this->imageTranslateY;
    }

    public function setImageTranslateY($imageTranslateY): void
    {
        $this->imageTranslateY = $imageTranslateY;
    }

    /**
     * @return float
     */
    public function getImageScale()
    {
        if(!$this->imageScale) { return 1; }
        return $this->imageScale;
    }

    /**
     * @param float $imageScale
     */
    public function setImageScale(float $imageScale): void
    {
        $this->imageScale = $imageScale;
    }
}
