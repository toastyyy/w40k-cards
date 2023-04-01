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
      * @ORM\OrderBy({"name":"ASC"})
     */
    private $units;

    /**
     *@ORM\ManyToOne(targetEntity=Medium::class, cascade={"ALL"})
     */
    private $factionLogo;

    /**
     *@ORM\ManyToOne(targetEntity=Medium::class, cascade={"PERSIST"})
     */
    private $backgroundImage;

    /**
     *@ORM\ManyToOne(targetEntity=Medium::class, cascade={"PERSIST"})
     */
    private $backsideImage;

    /**
     *@ORM\ManyToOne(targetEntity=Medium::class, cascade={"PERSIST"})
     */
    private $frontpageImage;

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

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $color1hue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $color1lightness;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $color1saturation;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $textColor1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $textColor2;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $textColor3;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $textColor4;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $bgColor1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $bgColor2;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $bgStyle;

    /**
     * @ORM\OneToMany(targetEntity=Model::class, mappedBy="card", orphanRemoval=true, cascade={"ALL"})
     */
    private $models;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $kpiStyle;

    /**
     * @ORM\OneToMany(targetEntity=Rule::class, mappedBy="card", orphanRemoval=true, cascade={"ALL"})
     */
    private $rules;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="card", orphanRemoval=true, cascade={"ALL"})
     */
    private $categories;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $big;


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
        $this->points = 0;
        $this->color1saturation = 100;
        $this->color1lightness = 100;
        $this->color1hue = 0;
        $this->textColor1 = '#000000';
        $this->textColor2 = '#000000';
        $this->textColor3 = '#000000';
        $this->textColor4 = '#000000';
        $this->bgColor1 = '#ddd';
        $this->bgColor2 = '#ac8947';
        $this->bgStyle = 'default';
        $this->kpiStyle = 'aos';
        $this->models = new ArrayCollection();
        $this->rules = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->big = false;
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
                $ability->setCard(null);
            }
        }
        foreach($abilities as $ability) {
            if(!$this->abilities->contains($ability)) {
                $this->abilities->add($ability);
                $ability->setCard($this);
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

    /**
     * @param $name
     * @return Weapon|null
     */
    public function getWeaponByName($name) {
        foreach($this->getWeapons() as $weapon) {
            if($weapon->getName() == $name) {
                return $weapon;
            }
        }
        return null;
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

    /**
     * @return int
     */
    public function getColor1hue(): int
    {
        if($this->color1hue === null) { return 0; }
        return $this->color1hue;
    }

    /**
     * @param int $color1hue
     */
    public function setColor1hue(int $color1hue): void
    {
        $this->color1hue = $color1hue;
    }

    /**
     * @return int
     */
    public function getColor1lightness(): int
    {
        if($this->color1lightness === null) { return 100; }
        return $this->color1lightness;
    }

    /**
     * @param int $color1lightness
     */
    public function setColor1lightness(int $color1lightness): void
    {
        $this->color1lightness = $color1lightness;
    }

    /**
     * @return int
     */
    public function getColor1saturation(): int
    {
        if($this->color1saturation === null) { return 100; }
        return $this->color1saturation;
    }

    /**
     * @param int $color1saturation
     */
    public function setColor1saturation(int $color1saturation): void
    {
        $this->color1saturation = $color1saturation;
    }

    /**
     * @return string
     */
    public function getTextColor1(): string
    {
        if($this->textColor1 === null) { return '#000000'; }
        return $this->textColor1;
    }

    /**
     * @param string $textColor1
     */
    public function setTextColor1(string $textColor1): void
    {
        $this->textColor1 = $textColor1;
    }

    /**
     * @return string
     */
    public function getTextColor2(): string
    {
        if($this->textColor2 === null) { return '#000000'; }
        return $this->textColor2;
    }

    /**
     * @param string $textColor2
     */
    public function setTextColor2(string $textColor2): void
    {
        $this->textColor2 = $textColor2;
    }

    /**
     * @return string
     */
    public function getTextColor3(): string
    {
        if($this->textColor3 === null) { return '#000000'; }
        return $this->textColor3;
    }

    /**
     * @param string $textColor3
     */
    public function setTextColor3(string $textColor3): void
    {
        $this->textColor3 = $textColor3;
    }

    /**
     * @return string
     */
    public function getTextColor4(): string
    {
        if($this->textColor4 === null) { return '#000000'; }
        return $this->textColor4;
    }

    /**
     * @param string $textColor4
     */
    public function setTextColor4(string $textColor4): void
    {
        $this->textColor4 = $textColor4;
    }

    /**
     * @return string
     */
    public function getBgColor1(): string
    {
        if($this->bgColor1 === null) { return '#ac8947'; }
        return $this->bgColor1;
    }

    /**
     * @param string $bgColor1
     */
    public function setBgColor1(string $bgColor1): void
    {
        $this->bgColor1 = $bgColor1;
    }

    /**
     * @return string
     */
    public function getBgColor2(): string
    {
        if($this->bgColor2 === null) { return '#dddddd'; }
        return $this->bgColor2;
    }

    /**
     * @param string $bgColor2
     */
    public function setBgColor2(string $bgColor2): void
    {
        $this->bgColor2 = $bgColor2;
    }

    /**
     * @return string
     */
    public function getBgStyle(): string
    {
        if($this->bgStyle === null) { return 'basic-light'; }
        return $this->bgStyle;
    }

    /**
     * @param string $bgStyle
     */
    public function setBgStyle(string $bgStyle): void
    {
        $this->bgStyle = $bgStyle;
    }

    /**
     * @return Collection<int, Model>
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(Model $model): self
    {
        if (!$this->models->contains($model)) {
            $this->models[] = $model;
            $model->setCard($this);
        }

        return $this;
    }

    public function removeModel(Model $model): self
    {
        if ($this->models->removeElement($model)) {
            // set the owning side to null (unless already changed)
            if ($model->getCard() === $this) {
                $model->setCard(null);
            }
        }

        return $this;
    }

    public function __clone()
    {
        $this->id = Uuid::uuid4();
        $units = new ArrayCollection();
        foreach($this->getUnits() as $u) {
            $nu = clone $u;
            $nu->setCard($this);
            $units->add($nu);
        }
        $this->units = $units;
        $abilities = new ArrayCollection();
        foreach($this->getAbilities() as $a) {
            $na = clone $a;
            $na->setCard($this);
            $abilities->add($na);
        }
        $this->abilities = $abilities;

        $properties = new ArrayCollection();
        foreach($this->getProperties() as $p) {
            $np = clone $p;
            $np->setCard($this);
            $properties->add($np);
        }
        $this->properties = $properties;

        $weapons = new ArrayCollection();
        foreach($this->getWeapons() as $w) {
            $nw = clone $w;
            $nw->setCard($this);
            $weapons->add($nw);
        }
        $this->weapons = $weapons;

        $psykers = new ArrayCollection();
        foreach($this->getPsykers() as $p) {
            $np = clone $p;
            $np->setCard($this);
            $psykers->add($np);
        }
        $this->psykers = $psykers;

        $powers = new ArrayCollection();
        foreach($this->getPsychicPowers() as $pp) {
            $npp = clone $pp;
            $npp->setCard($this);
            $powers->add($npp);
        }
        $this->psychicPowers = $powers;

        $models = new ArrayCollection();
        foreach($this->getModels() as $m) {
            $nm = clone $m;
            $nm->setCard($this);
            $models->add($nm);
        }
        $this->models = $models;

        $explosions = new ArrayCollection();
        foreach ($this->getExplosions() as $e) {
            $ne = clone $e;
            $ne->setCard($this);
            $explosions->add($ne);
        }
        $this->explosions = $explosions;
    }

    /**
     * @return string
     */
    public function getKpiStyle(): string
    {
        if(!$this->kpiStyle) { return 'aos'; }
        return $this->kpiStyle;
    }

    /**
     * @param string $kpiStyle
     */
    public function setKpiStyle(string $kpiStyle): void
    {
        $this->kpiStyle = $kpiStyle;
    }

    /**
     * @return Collection<int, Rule>
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function addRule(Rule $rule): self
    {
        if (!$this->rules->contains($rule)) {
            $this->rules[] = $rule;
            $rule->setCard($this);
        }

        return $this;
    }

    public function removeRule(Rule $rule): self
    {
        if ($this->rules->removeElement($rule)) {
            // set the owning side to null (unless already changed)
            if ($rule->getCard() === $this) {
                $rule->setCard(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setCard($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getCard() === $this) {
                $category->setCard(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBacksideImage()
    {
        return $this->backsideImage;
    }

    /**
     * @param mixed $backsideImage
     */
    public function setBacksideImage($backsideImage): void
    {
        $this->backsideImage = $backsideImage;
    }

    /**
     * @return mixed
     */
    public function getFrontpageImage()
    {
        return $this->frontpageImage;
    }

    /**
     * @param mixed $frontpageImage
     */
    public function setFrontpageImage($frontpageImage): void
    {
        $this->frontpageImage = $frontpageImage;
    }

    /**
     * @return false
     */
    public function getBig(): bool
    {
        return $this->big || false;
    }

    /**
     * @param false $big
     */
    public function setBig(bool $big): void
    {
        $this->big = $big;
    }
}
