<?php

namespace App\Parser;

use App\Entity\Weapon;

class WeaponParser
{
    public function parse($data): Weapon {
        $weapon = new Weapon();
        $weapon->setName($data['@attributes']['name']);
        $weapon->setRange($data['characteristics']['characteristic'][0]);
        $weapon->setType($data['characteristics']['characteristic'][1]);
        $weapon->setStrength($data['characteristics']['characteristic'][2]);
        $weapon->setPenetration($data['characteristics']['characteristic'][3]);
        $weapon->setDamage($data['characteristics']['characteristic'][4]);
        if(isset($data['characteristics']['characteristic'][5])) {
            $weapon->setAbilities($data['characteristics']['characteristic'][5]);
        }
        return $weapon;
    }
}
