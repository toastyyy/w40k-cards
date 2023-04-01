<?php

namespace App\Parser;

use App\Entity\Psyker;

class PsykerParser
{
    public function parse($data): Psyker {
        $psyker = new Psyker();
        $psyker->setName($data['@attributes']['name']);
        $psyker->setCast($data['characteristics']['characteristic'][0]);
        $psyker->setDeny($data['characteristics']['characteristic'][1]);
        if(isset($data['characteristics']['characteristic'][2])) {
            $psyker->setRef($data['characteristics']['characteristic'][2]);
        }

        return $psyker;
    }
}
