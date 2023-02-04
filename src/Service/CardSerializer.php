<?php


namespace App\Service;


use App\Entity\Card;
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
            'keywords' => $card->getKeywords()
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
