<?php


namespace App\Service;


use App\Entity\Card;
use App\Entity\Roster;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class CardSerializer implements CardSerializerInterface
{
    private $unitSerializer;
    private $weaponSerializer;
    private $abilitySerializer;
    private $psykerSerializer;
    private $psychicPowerSerializer;
    private $explosionSerializer;
    private $mediumSerializer;
    private $em;

    public function __construct(UnitSerializerInterface $unitSerializer, WeaponSerializerInterface $weaponSerializer, AbilitySerializerInterface $abilitySerializer, EntityManagerInterface $em,
        PsykerSerializerInterface $psykerSerializer, PsychicPowerSerializerInterface $psychicPowerSerializer, ExplosionSerializerInterface $explosionSerializer, MediumSerializerInterface $mediumSerializer)
    {
        $this->unitSerializer = $unitSerializer;
        $this->weaponSerializer = $weaponSerializer;
        $this->abilitySerializer = $abilitySerializer;
        $this->psykerSerializer = $psykerSerializer;
        $this->psychicPowerSerializer = $psychicPowerSerializer;
        $this->explosionSerializer = $explosionSerializer;
        $this->mediumSerializer = $mediumSerializer;
        $this->em = $em;
    }
    public function serialize(Card $card, $mode = 'list')
    {
        $serialized = [
            'id' => $card->getId(),
            'title' => $card->getTitle(),
            'subtitle' => $card->getSubtitle(),
            'quote' => $card->getQuote(),
            'borderColor' => $card->getBorderColor(),
            'textColor' => $card->getTextColor(),
            'keywords' => $card->getKeywords(),
            'useAutomaticBackgroundRemoval' => $card->isUseAutomaticBackgroundRemoval(),
            'imageTranslateX' => $card->getImageTranslateX(),
            'imageTranslateY' => $card->getImageTranslateY(),
            'imageScale' => $card->getImageScale(),
            'color1hue' => $card->getColor1hue(),
            'color1saturation' => $card->getColor1saturation(),
            'color1lightness' => $card->getColor1lightness(),
            'textColor1' => $card->getTextColor1(),
            'textColor2' => $card->getTextColor2(),
            'textColor3' => $card->getTextColor3(),
            'textColor4' => $card->getTextColor4(),
            'bgColor1' => $card->getBgColor1(),
            'bgColor2' => $card->getBgColor2(),
            'bgStyle' => $card->getBgStyle(),
            'kpiStyle' => $card->getKpiStyle()
        ];

        foreach($card->getProperties() as $prop) {
            $serialized[$prop->getPropertyKey()] = $prop->getPropertyValue();
        }

        $units = [];
        foreach($card->getUnits() as $u) {
            $units[] = $this->unitSerializer->serialize($u);
        }
        $serialized['units'] = $units;

        $weapons = [];
        foreach($card->getWeapons() as $w) {
            $weapons[] = $this->weaponSerializer->serialize($w);
        }
        $serialized['weapons'] = $weapons;

        $abilities = [];
        foreach($card->getAbilities() as $a) {
            $abilities[] = $this->abilitySerializer->serialize($a);
        }
        $serialized['abilities'] = $abilities;

        $psykers = [];
        foreach($card->getPsykers() as $p) {
            $psykers[] = $this->psykerSerializer->serialize($p);
        }
        $serialized['psykers'] = $psykers;

        $psychicPowers = [];
        foreach($card->getPsychicPowers() as $pp) {
            $psykers[] = $this->psychicPowerSerializer->serialize($pp);
        }
        $serialized['psychicPowers'] = $psychicPowers;

        $explosions = [];
        foreach($card->getExplosions() as $e) {
            $explosions[] = $this->explosionSerializer->serialize($e);
        }
        $serialized['explosions'] = $explosions;

        $serialized['backgroundImage'] = $card->getBackgroundImage() ? $this->mediumSerializer->serialize($card->getBackgroundImage()) : null;
        $serialized['factionLogo'] = $card->getFactionLogo() ? $this->mediumSerializer->serialize($card->getFactionLogo()) : null;
        $serialized['boxBackground'] = $card->getBoxBackground() ? $this->mediumSerializer->serialize($card->getBoxBackground()) : null;
        $serialized['unitImage'] = $card->getUnitImage() ? $this->mediumSerializer->serialize($card->getUnitImage()) : null;

        return $serialized;
    }

    public function deserialize($data): ?Card
    {
        if(isset($data->id)) {
            $card = $this->em->getRepository(Card::class)->find($data->id);
        } else {
            $card = new Card();
        }

        if($card) {
            $card->setTitle($data->title ?? $card->getTitle());
            $card->setSubtitle($data->subtitle ?? $card->getSubtitle());
            $card->setQuote($data->quote ?? $card->getQuote());
            $card->setUseAutomaticBackgroundRemoval(isset($data->useAutomaticBackgroundRemoval) ? $data->useAutomaticBackgroundRemoval : $card->isUseAutomaticBackgroundRemoval());
            $card->setImageTranslateX(isset($data->imageTranslateX) ? $data->imageTranslateX : $card->getImageTranslateX());
            $card->setImageTranslateY(isset($data->imageTranslateY) ? $data->imageTranslateY : $card->getImageTranslateY());
            $card->setImageScale(isset($data->imageScale) ? $data->imageScale : $card->getImageScale());
            $card->setColor1hue(isset($data->color1hue) ? $data->color1hue : $card->getColor1hue());
            $card->setColor1saturation(isset($data->color1saturation) ? $data->color1saturation : $card->getColor1saturation());
            $card->setColor1lightness(isset($data->color1lightness) ? $data->color1lightness : $card->getColor1lightness());
            $card->setTextColor1(isset($data->textColor1) ? $data->textColor1 : $card->getTextColor1());
            $card->setTextColor2(isset($data->textColor2) ? $data->textColor2 : $card->getTextColor2());
            $card->setTextColor3(isset($data->textColor3) ? $data->textColor3 : $card->getTextColor3());
            $card->setTextColor4(isset($data->textColor4) ? $data->textColor4 : $card->getTextColor4());
            $card->setBgColor1(isset($data->bgColor1) ? $data->bgColor1 : $card->getBgColor1());
            $card->setBgColor2(isset($data->bgColor2) ? $data->bgColor2 : $card->getBgColor2());
            $card->setBgStyle(isset($data->bgStyle) ? $data->bgStyle : $card->getBgStyle());
            $card->setKpiStyle(isset($data->kpiStyle) ? $data->kpiStyle : $card->getKpiStyle());

            if(isset($data->unitImage)) {
                $card->setUnitImage($this->mediumSerializer->deserialize($data->unitImage));
            }
            if(isset($data->factionLogo)) {
                $card->setFactionLogo($this->mediumSerializer->deserialize($data->factionLogo));
            }
            if(isset($data->backgroundImage)) {
                $card->setBackgroundImage($this->mediumSerializer->deserialize($data->backgroundImage));
            }
            if(isset($data->boxBackground)) {
                $card->setBoxBackground($this->mediumSerializer->deserialize($data->boxBackground));
            }
            if(isset($data->roster) && isset($data->roster->id)) {
                $roster = $this->em->getRepository(Roster::class)
                    ->find($data->roster->id);
                if($roster) {
                    $card->setRoster($roster);
                }
            }

            if(isset($data->units) && is_array($data->units)) {
                $unitCollection = new ArrayCollection();
                foreach($data->units as $u) {
                    $unit = $this->unitSerializer->deserialize($u);
                    if($unit) {
                        $unitCollection->add($unit);
                    }
                }
                $card->setUnits($unitCollection);
            }

            if(isset($data->weapons) && is_array($data->weapons)) {
                $weaponCollection = new ArrayCollection();
                foreach($data->weapons as $w) {
                    $weapon = $this->weaponSerializer->deserialize($w);
                    if($weapon) {
                        $weaponCollection->add($weapon);
                    }
                }
                $card->setWeapons($weaponCollection);
            }

            if(isset($data->abilities) && is_array($data->abilities)) {
                $abilityCollection = new ArrayCollection();
                foreach($data->abilities as $a) {
                    $ability = $this->abilitySerializer->deserialize($a);
                    if($ability) {
                        $abilityCollection->add($ability);
                    }
                }
                $card->setAbilities($abilityCollection);
            }

            if(isset($data->psykers) && is_array($data->psykers)) {
                $psykerCollection = new ArrayCollection();
                foreach($data->psykers as $p) {
                    $psyker = $this->psykerSerializer->deserialize($p);
                    if($psyker) {
                        $psykerCollection->add($psyker);
                    }
                }
                $card->setPsykers($psykerCollection);
            }

            if(isset($data->psychicPowers) && is_array($data->psychicPowers)) {
                $psychicPowersCollection = new ArrayCollection();
                foreach($data->psychicPowers as $p) {
                    $psychicPower = $this->psychicPowerSerializer->deserialize($p);
                    if($psychicPower) {
                        $psychicPowersCollection->add($psychicPower);
                    }
                }
                $card->setPsychicPowers($psychicPowersCollection);
            }

            if(isset($data->explosions) && is_array($data->explosions)) {
                $explosionsCollection = new ArrayCollection();
                foreach($data->explosions as $e) {
                    $explosion = $this->explosionSerializer->deserialize($e);
                    if($explosion) {
                        $explosionsCollection->add($explosion);
                    }
                }
                $card->setExplosions($explosionsCollection);
            }

            $card->setTextColor($data->textColor ?? $card->getTextColor());
            $card->setBorderColor($data->borderColor ?? $card->getBorderColor());
            return $card;
        } else {
            return null;
        }
        
    }
}
