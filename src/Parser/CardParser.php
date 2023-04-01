<?php

namespace App\Parser;

use App\Entity\Card;

class CardParser
{
    private $ruleParser;
    private $categoryParser;
    private $unitParser;
    private $abilityParser;
    private $psykerParser;
    private $psychicPowerParser;
    private $weaponParser;

    public function __construct(RuleParser $ruleParser, CategoryParser $categoryParser, UnitParser $unitParser, AbilityParser $abilityParser, PsykerParser $psykerParser, PsychicPowerParser $psychicPowerParser, WeaponParser $weaponParser) {
        $this->ruleParser = $ruleParser;
        $this->categoryParser = $categoryParser;
        $this->unitParser = $unitParser;
        $this->abilityParser = $abilityParser;
        $this->psykerParser = $psykerParser;
        $this->psychicPowerParser = $psychicPowerParser;
        $this->weaponParser = $weaponParser;
    }
    public function parse($data) {
        if(isset($data['@attributes']) && isset($data['@attributes']['type']) && $data['@attributes']['type'] == 'upgrade') { return null; }
        $card = new Card();
        if(isset($data['rules'])) {
            if(isset($data['rules']['rule']['@attributes'])) {
                $data['rules']['rule'] = [$data['rules']['rule']];
            }
            foreach($data['rules']['rule'] as $r) {
                $rule = $this->ruleParser->parse($r);
                $card->addRule($rule);
            }
        }

        if(isset($data['categories'])) {
            if(isset($data['categories']['category']['@attributes'])) {
                $data['categories']['category'] = [$data['categories']['category']];
            }
            foreach($data['categories']['category'] as $c) {
                $category = $this->categoryParser->parse($c);
                $card->addCategory($category);
            }
        }
        if(isset($data['costs'])) {

        }

        $card->setTitle($data['@attributes']['name']);

        if(isset($data['profiles'])) {
            if(isset($selection['profiles']['profile']['@attributes'])) {
                $selection['profiles']['profile'] = [$selection['profiles']['profile']];
            }
            foreach($data['profiles']['profile'] as $p) {
                switch($p['@attributes']['typeName']) {
                    case 'Unit':
                        $unit = $this->unitParser->parse($p);
                        $card->addUnit($unit);
                        break;
                    case 'Abilities':
                        $ability = $this->abilityParser->parse($p);
                        $card->addAbility($ability);
                        break;
                    case 'Psyker':
                        $psyker = $this->psykerParser->parse($p);
                        $card->addPsyker($psyker);
                        break;
                    case 'Psychic Power':
                        $power = $this->psychicPowerParser->parse($p);
                        $card->addPsychicPower($power);
                        break;
                    case 'Weapon':
                        $weapon = $this->weaponParser->parse($p);
                        $card->addWeapon($weapon);
                        break;
                }
            }
        }

        if(isset($data['selections'])) {
            if(isset($data['selections']['selection']['@attributes'])) {
                $data['selections']['selection'] = [$data['selections']['selection']];
            }
            foreach($data['selections']['selection'] as $selection) {
                if(isset($selection['@attributes'])) {
                    if($selection['@attributes']['type'] == 'upgrade') {
                        // Upgrades can contain profiles again
                        if(isset($selection['profiles'])) {
                            if(isset($selection['profiles']['profile']['@attributes'])) {
                                $selection['profiles']['profile'] = [$selection['profiles']['profile']];
                            }
                            foreach($selection['profiles']['profile'] as $p) {
                                if(isset($p['@attributes'])) {
                                    switch($p['@attributes']['typeName']) {
                                        case 'Unit':
                                            $unit = $this->unitParser->parse($p);
                                            $card->addUnit($unit);
                                            break;
                                        case 'Abilities':
                                            $ability = $this->abilityParser->parse($p);
                                            $card->addAbility($ability);
                                            break;
                                        case 'Psyker':
                                            $psyker = $this->psykerParser->parse($p);
                                            $card->addPsyker($psyker);
                                            break;
                                        case 'Psychic Power':
                                            $power = $this->psychicPowerParser->parse($p);
                                            $card->addPsychicPower($power);
                                            break;
                                        case 'Weapon':
                                            $weapon = $this->weaponParser->parse($p);
                                            $card->addWeapon($weapon);
                                            break;
                                    }
                                }
                            }
                        }

                    } elseif($selection['@attributes']['type'] == 'model') {

                        if(isset($selection['profiles'])) {
                            if(isset($selection['profiles']['profile']['@attributes'])) {
                                $selection['profiles']['profile'] = [$selection['profiles']['profile']];
                            }
                            foreach($selection['profiles']['profile'] as $p) {
                                if(isset($p['@attributes'])) {
                                    switch($p['@attributes']['typeName']) {
                                        case 'Unit':
                                            $unit = $this->unitParser->parse($p);
                                            $card->addUnit($unit);
                                            break;
                                        case 'Abilities':
                                            $ability = $this->abilityParser->parse($p);
                                            $card->addAbility($ability);
                                            break;
                                        case 'Psyker':
                                            $psyker = $this->psykerParser->parse($p);
                                            $card->addPsyker($psyker);
                                            break;
                                        case 'Psychic Power':
                                            $power = $this->psychicPowerParser->parse($p);
                                            $card->addPsychicPower($power);
                                            break;
                                        case 'Weapon':
                                            $weapon = $this->weaponParser->parse($p);
                                            $card->addWeapon($weapon);
                                            break;
                                    }
                                }
                            }
                        }
                        if(isset($selection['selections']['selection']['@attributes'])) {
                            $selection['selections']['selection'] = [$selection['selections']['selection']];
                        }
                        foreach($selection['selections']['selection'] as $subselection) {
                            if (isset($subselection['@attributes'])) {
                                if ($subselection['@attributes']['type'] == 'upgrade') {
                                    if(isset($subselection['profiles'])) {
                                        if(isset($subselection['profiles']['profile']['@attributes'])) {
                                            $subselection['profiles']['profile'] = [$subselection['profiles']['profile']];
                                        }
                                        foreach($subselection['profiles']['profile'] as $p) {
                                            if(isset($p['@attributes'])) {
                                                switch($p['@attributes']['typeName']) {
                                                    case 'Unit':
                                                        $unit = $this->unitParser->parse($p);
                                                        $card->addUnit($unit);
                                                        break;
                                                    case 'Abilities':
                                                        $ability = $this->abilityParser->parse($p);
                                                        $card->addAbility($ability);
                                                        break;
                                                    case 'Psyker':
                                                        $psyker = $this->psykerParser->parse($p);
                                                        $card->addPsyker($psyker);
                                                        break;
                                                    case 'Psychic Power':
                                                        $power = $this->psychicPowerParser->parse($p);
                                                        $card->addPsychicPower($power);
                                                        break;
                                                    case 'Weapon':
                                                        $weapon = $this->weaponParser->parse($p);
                                                        if($card->getWeaponByName($weapon->getName())) {
                                                            $wpn = $card->getWeaponByName($weapon->getName());
                                                            $wpn->setQuantity($wpn->getQuantity() + 1);
                                                        } else {
                                                            $card->addWeapon($weapon);
                                                        }
                                                        break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }

        return $card;
    }
}
