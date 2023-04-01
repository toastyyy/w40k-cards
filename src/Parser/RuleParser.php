<?php

namespace App\Parser;

use App\Entity\Rule;

class RuleParser
{
    public function parse($data): Rule {
        $rule = new Rule();
        $rule->setName($data['@attributes']['name']);
        $rule->setDescription($data['description']);
        $rule->setHidden($data['@attributes']['hidden'] == 'true');

        return $rule;
    }
}
