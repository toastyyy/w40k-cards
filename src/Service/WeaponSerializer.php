<?php


namespace App\Service;


use App\Entity\Weapon;
use Doctrine\ORM\EntityManagerInterface;

class WeaponSerializer implements WeaponSerializerInterface
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function serialize(Weapon $weapon, $mode = 'list')
    {
        return [
            'id' => $weapon->getId(),
            'name' => $weapon->getName(),
            'range' => $weapon->getRange(),
            'type' => $weapon->getType(),
            'strength' => $weapon->getStrength(),
            'penetration' => $weapon->getPenetration(),
            'damage' => $weapon->getDamage(),
            'abilities' => $weapon->getAbilities(),
            'ref' => $weapon->getRef(),
        ];
    }

    public function deserialize($data): ?Weapon
    {
        if(isset($data->id)) {
            $weapon = $this->em->getRepository(Weapon::class)->find($data->id);
        } else {
            $weapon = new Weapon();
        }

        if($weapon) {
            $weapon->setName($data->name ?? $weapon->getName());
            $weapon->setRange($data->range ?? $weapon->getRange());
            $weapon->setType($data->type ?? $weapon->getType());
            $weapon->setStrength($data->strength ?? $weapon->getStrength());
            $weapon->setPenetration($data->penetration ?? $weapon->getPenetration());
            $weapon->setDamage($data->damage ?? $weapon->getDamage());
            $weapon->setAbilities($data->abilities ?? $weapon->getAbilities());
            $weapon->setRef($data->ref ?? $weapon->getRef());
            return $weapon;
        }
        return null;
    }
}
