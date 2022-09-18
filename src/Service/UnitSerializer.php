<?php


namespace App\Service;


use App\Entity\Unit;
use Doctrine\ORM\EntityManagerInterface;

class UnitSerializer implements UnitSerializerInterface
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function serialize(Unit $unit, $mode = 'list')
    {
        return [
            'id' => $unit->getId(),
            'name' => $unit->getName(),
            'points' => $unit->getPoints(),
            'movementSpeed' => $unit->getMovementSpeed(),
            'wounds' => $unit->getWounds(),
            'weaponSkill' => $unit->getWeaponSkill(),
            'ballisticSkill' => $unit->getBallisticSkill(),
            'save' => $unit->getSave(),
            'leadership' => $unit->getLeadership(),
            'attacks' => $unit->getAttacks(),
            'strength' => $unit->getStrength(),
            'toughness' => $unit->getToughness(),
        ];
    }

    public function deserialize($data): ?Unit
    {
        if(isset($data->id)) {
            $unit = $this->em->getRepository(Unit::class)->find($data->id);
        } else {
            $unit = new Unit();
        }

        if($unit) {
            $unit->setName($data->name ?? $unit->getName());
            $unit->setPoints($data->points ?? $unit->getPoints());
            $unit->setMovementSpeed($data->movementSpeed ?? $unit->getMovementSpeed());
            $unit->setWounds($data->wounds ?? $unit->getWounds());
            $unit->setWeaponSkill($data->weaponSkill ?? $unit->getWeaponSkill());
            $unit->setBallisticSkill($data->ballisticSkill ?? $unit->getBallisticSkill());
            $unit->setSave($data->save ?? $unit->getSave());
            $unit->setLeadership($data->leadership ?? $unit->getLeadership());
            $unit->setAttacks($data->attacks ?? $unit->getAttacks());
            $unit->setStrength($data->strength ?? $unit->getStrength());
            $unit->setToughness($data->toughness ?? $unit->getToughness());
            return $unit;
        }
        return null;
    }
}
