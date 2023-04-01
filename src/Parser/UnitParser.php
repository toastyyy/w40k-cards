<?php

namespace App\Parser;

use App\Entity\SelectionInterface;
use App\Entity\Unit;

class UnitParser
{
    public function parse($data): Unit {
        $unit = new Unit();
        $unit->setName($data['@attributes']['name']);
        $unit->setMovementSpeed($data['characteristics']['characteristic'][0]);
        $unit->setWeaponSkill($data['characteristics']['characteristic'][1]);
        $unit->setBallisticSkill($data['characteristics']['characteristic'][2]);
        $unit->setStrength($data['characteristics']['characteristic'][3]);
        $unit->setToughness($data['characteristics']['characteristic'][4]);
        $unit->setWounds($data['characteristics']['characteristic'][5]);
        $unit->setAttacks($data['characteristics']['characteristic'][6]);
        $unit->setLeadership($data['characteristics']['characteristic'][7]);
        $unit->setSave($data['characteristics']['characteristic'][8]);

        return $unit;
    }
}
