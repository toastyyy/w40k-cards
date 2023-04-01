<?php

namespace App\Parser;

use App\Entity\Ability;

class AbilityParser
{
    public function parse($data): Ability {
        $ability = new Ability();
        $ability->setName($data['@attributes']['name']);
        $ability->setDescription($data['characteristics']['characteristic']);
        return $ability;
    }
}
