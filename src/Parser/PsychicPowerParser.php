<?php

namespace App\Parser;

use App\Entity\PsychicPower;

class PsychicPowerParser
{
    public function parse($data): PsychicPower {
        $power = new PsychicPower();
        $power->setName($data['@attributes']['name']);
        $power->setWarpCharge($data['characteristics']['characteristic'][0]);
        $power->setRange($data['characteristics']['characteristic'][1]);
        $power->setDetails($data['characteristics']['characteristic'][2]);
        return $power;
    }
}
