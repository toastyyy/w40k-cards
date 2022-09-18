<?php


namespace App\Service;


use App\Entity\Weapon;

interface WeaponSerializerInterface
{
    public function serialize(Weapon $weapon, $mode = 'list');

    public function deserialize($data): ?Weapon;
}
