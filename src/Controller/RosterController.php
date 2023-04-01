<?php


namespace App\Controller;


use App\Entity\Card;
use App\Entity\Unit;
use App\Entity\Ability;
use App\Entity\Explosion;
use App\Entity\Psyker;
use App\Entity\PsychicPower;
use App\Entity\Weapon;
use App\Entity\Roster;
use App\Parser\CardParser;
use App\Service\RosterSerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/roster", name="api_roster_")
 */
class RosterController extends AbstractController
{
    private $roster;
    /**
     * @Route("/{id}", methods={"GET"}, name="show")
     */
    public function show(Request $request, $id, RosterSerializerInterface $rosterSerializer) {
        $roster = $this->getDoctrine()->getRepository(Roster::class)->find($id);
        if($roster) {
            return new JsonResponse($rosterSerializer->serialize($roster, 'show'));
        }
        return new JsonResponse(null, 404);
    }
    /**
     * @Route("", methods={"POST"}, name="create")
     */
    public function create(Request $request, KernelInterface $kernel, RosterSerializerInterface $rosterSerializer, CardParser $cardParser) {
        $data = $request->getContent();
        $data = json_decode($data);

        $zippedRosterFile = $kernel->getProjectDir() . '/var/roster/' . time() . '.rosz';
        file_put_contents($zippedRosterFile, base64_decode($data->data));

        $zip = new \ZipArchive();
        $rosterFile = null;
        if ($zip->open($zippedRosterFile) === TRUE) {
            $rosterFolder = $kernel->getProjectDir() . '/var/roster/' . time();
            $zip->extractTo($rosterFolder);
            $files = scandir($rosterFolder);
            foreach ($files as $file) {
                if (!is_file($rosterFolder . '/' . $file)) {
                    continue;
                }
                if (substr($file, strlen($file) - 4, 4) == '.ros') {
                    $rosterFile = $kernel->getProjectDir() . '/var/roster/' . time() . '_' . $file;
                    rename($rosterFolder . '/' . $file, $rosterFile);
                } else {
                    unlink($rosterFolder . '/' . $file);
                }
            }
            $zip->close();
            rmdir($rosterFolder);
            unlink($zippedRosterFile);
        } else {
            unlink($zippedRosterFile);
        }

        $xml = simplexml_load_string(file_get_contents($rosterFile), "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $data = json_decode($json, TRUE);

        $this->roster = new Roster();

        /* Each Force contains N selections. */
        if($data) {
            if (isset($data['forces']) && isset($data['forces']['force']) && !$this->isAssociative($data['forces']['force'])) {
                foreach ($data['forces']['force'] as $force) {
                    if (isset($force['selections']) && isset($force['selections']['selection']) && !$this->isAssociative($force['selections']['selection'])) {
                        foreach ($force['selections']['selection'] as $selection) {
                            $card = $cardParser->parse($selection);
                            if($card && $card->getTitle()) {
                                $this->roster->addCard($card);
                            }
                            //$this->handleSelection($selection, $force, null);
                        }
                    }
                }
            } elseif(isset($data['forces']) && isset($data['forces']['force'])) {
                $force = $data['forces']['force'];
                if (isset($force['selections']) && isset($force['selections']['selection']) && !$this->isAssociative($force['selections']['selection'])) {
                    foreach ($force['selections']['selection'] as $selection) {
                        $card = $cardParser->parse($selection);
                        if($card && $card->getTitle()) {
                            $this->roster->addCard($card);
                        }
                        //$this->handleSelection($selection, $force, null);
                    }
                }
            }
        }

        $this->getDoctrine()->getManager()->persist($this->roster);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($rosterSerializer->serialize($this->roster));
    }

    private function isAssociative($arr) {
        return (array_merge($arr) !== $arr || count(array_filter($arr, 'is_string', ARRAY_FILTER_USE_KEY)) > 0);
    }

    private function handleSelection($selection, $force, $owner = null, $card = null)
    {
        /* A selection can be of type upgrade, model (single figure) or unit. */
        $selection = $this->sanitizeSelection($selection);
        if (isset($selection['@attributes']) && isset($selection['@attributes']['type'])) {
            $type = $selection['@attributes']['type'];
            if ($type == 'model' || $type == 'unit') {
                $this->handleUnit($selection, $force, $owner, $card);
            } elseif ($type == 'upgrade') {
                $this->handleUpgrade($selection, $force, $owner, $card);
            }
        }
    }

    private function handleCategories($selection, $force, $owner = null, $card = null) {

    }

    private function sanitizeSelection($selection) {
        if(isset($selection['rules']) && isset($selection['rules']['rule']) && $this->isAssociative($selection['rules']['rule'])) {
            $selection['rules']['rule'] = [$selection['rules']['rule']];
        }
        if(isset($selection['categories']) && isset($selection['categories']['category']) && $this->isAssociative($selection['categories']['category'])) {
            $selection['categories']['category'] = [$selection['categories']['category']];
        }
        return $selection;
    }

    private function getUnitPoints($unit)
    {
        if (isset($unit['costs']) && isset($unit['costs']['cost']) && !$this->isAssociative($unit['costs']['cost'])) {
            foreach ($unit['costs']['cost'] as $c) {
                if (isset($c['@attributes']) && isset($c['@attributes']['typeId']) && $c['@attributes']['typeId'] == 'points') {
                    if (isset($unit['@attributes']) && isset($unit['@attributes']['number'])) {
                        return intval($c['@attributes']['value'] / $unit['@attributes']['number']);
                    } else {
                        return intval($c['@attributes']['value']);
                    }
                }
            }
            return 0;
        }
        return 0;
    }

    private function hasCategory($unit, $category)
    {
        if (isset($unit['categories']) && isset($unit['categories']['category']) && !$this->isAssociative($unit['categories']['category'])) {
            foreach ($unit['categories']['category'] as $c) {
                if (isset($c['@attributes']) && isset($c['@attributes']['name'])) {
                    if ($c['@attributes']['name'] == $category) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @return Card
     */
    private function handleUnit($unit, $force, $owner = null, $card = null)
    {
        $card = null;
        if ($this->getUnitPoints($unit) > 0) {
            $card = new Card();

            $card->setTitle(explode("w/", $unit['@attributes']['name'])[0]);
            $card->setPoints($this->getUnitPoints($unit));

            $profiles = [];
            if(isset($unit['profiles']['profile']['@attributes'])) {
                $profiles = [$unit['profiles']['profile']];
            } else if(isset($unit['profiles']['profile'])) {
                $profiles = $unit['profiles']['profile'];
            } else if(isset($owner['profiles']['profile']['@attributes'])) {
                $profiles = [$owner['profiles']['profile']];
            } else if(isset($owner['profiles']['profile'])) {
                $profiles = $owner['profiles']['profile'];
            }

            /* A unit has N profiles each of type Unit, Abilities or Psyker */

            foreach($profiles as $profile) {
                if(isset($profile['@attributes']) && isset($profile['@attributes']['typeName'])) {
                    $type = $profile['@attributes']['typeName'];
                    if($type == 'Unit') {
                        $u = new Unit();
                        $u->setMovementSpeed($profile['characteristics']['characteristic'][0]);
                        $u->setWeaponSkill($profile['characteristics']['characteristic'][1]);
                        $u->setBallisticSkill($profile['characteristics']['characteristic'][2]);
                        $u->setStrength($profile['characteristics']['characteristic'][3]);
                        $u->setToughness($profile['characteristics']['characteristic'][4]);
                        $u->setWounds($profile['characteristics']['characteristic'][5]);
                        $u->setAttacks($profile['characteristics']['characteristic'][6]);
                        $u->setLeadership($profile['characteristics']['characteristic'][7]);
                        $u->setSave($profile['characteristics']['characteristic'][8]);
                        $u->setName($profile['@attributes']['name']);
                        $u->setPoints($this->unitPoints($profile));
                        $card->addUnit($u);
                    } else if($type == 'Abilities') {
                        $ability = new Ability();
                        $ability->setName($profile['@attributes']['name']);
                        $ability->setDescription($profile['characteristics']['characteristic']);
                        $card->addAbility($ability);
                    } else if($type == 'Psyker') {
                        // todo
                        $psyker = new Psyker();
                        $psyker->setName($profile['@attributes']['name']);
                        $psyker->setCast($profile['characteristics']['characteristic'][0]);
                        $psyker->setDeny($profile['characteristics']['characteristic'][1]);
                        $card->addPsyker($psyker);
                    } else {
                        $test = $profile;
                    }
                }
            }

            /* A unit can have N rules. */
            if(isset($unit['rules']['rule']['@attributes'])) {
                $card->setProperty('rules', [$unit['rules']['rule']], 'rules');
            } elseif(isset($unit['rules']['rule'])) {
                $card->setProperty('rules', $unit['rules']['rule'], 'rules');
            }
            if(isset($unit['categories']['category']['@attributes'])) {
                $categories = [$unit['categories']['category']['name']];
                $card->setKeywords($categories);
            } elseif(isset($unit['categories']['category'])) {
                $categories = array_map(function($e) {
                    return $e['@attributes']['name'];
                }, $unit['categories']['category']);
                $card->setKeywords($categories);
            }
        }

        /* A Unit has N selections each of type */
        if (isset($unit['selections']) && isset($unit['selections']['selection']) && !$this->isAssociative($unit['selections']['selection'])) {
            foreach ($unit['selections']['selection'] as $selection) {
                if(!isset($selection['@attributes']) && isset($unit['@attributes'])) {
                    $selection['@attributes'] = $unit['@attributes'];
                }
                if(!isset($selection['rules']) && isset($unit['rules'])) {
                    $selection['rules'] = $unit['rules'];
                }
                if(!isset($selection['categories']) && isset($unit['categories'])) {
                    $selection['categories'] = $unit['categories'];
                }
                $this->handleSelection($selection, $force, $unit, $card);
            }
        } elseif(isset($unit['selections']) && isset($unit['selections']['selection'])) {
            $selection = $unit['selections']['selection'];
            /* sometimes attributes need to be inherited from parent unit */
            if(!isset($selection['@attributes']) && isset($unit['@attributes'])) {
                $selection['@attributes'] = $unit['@attributes'];
            }
            if(!isset($selection['rules']) && isset($unit['rules'])) {
                $selection['rules'] = $unit['rules'];
            }
            if(!isset($selection['categories']) && isset($unit['categories'])) {
                $selection['categories'] = $unit['categories'];
            }
            $this->handleSelection($selection, $force, $unit, $card);
        }
        if($card != null) {
            $this->roster->addCard($card);
        }
    }

    private function handleWeapon($unit, $force, $profile, $owner = null, $card = null)
    {
        if(!$card) { return; }
        $weapon = new Weapon();
        $weapon->setName($profile['@attributes']['name']);
        $weapon->setRange($profile['characteristics']['characteristic'][0]);
        $weapon->setType($profile['characteristics']['characteristic'][1]);
        $weapon->setStrength($profile['characteristics']['characteristic'][2]);
        $weapon->setPenetration($profile['characteristics']['characteristic'][3]);
        $weapon->setDamage($profile['characteristics']['characteristic'][4]);
        $weapon->setAbilities($profile['characteristics']['characteristic'][5]);
        $card->addWeapon($weapon);
    }

    private function handlePsy($unit, $force, $owner = null, $card = null)
    {
        if(!$card) { return; }
        $psy = new PsychicPower();
        $psy->setName($unit['@attributes']['name']);
        $psy->setWarpCharge($unit['characteristics']['characteristic'][0]);
        $psy->setDetails($unit['characteristics']['characteristic'][2]);
        $card->addPsychicPower($psy);
    }

    private function handleUpgrade($unit, $force, $owner = null, $card = null)
    {
        if (isset($unit['profiles']) && isset($unit['profiles']['profile'])) {
            $profiles = $unit['profiles']['profile'];

            if (isset($profiles['@attributes'])) {
                if ($profiles['@attributes']['typeName'] == 'Weapon') {
                    $this->handleWeapon($unit, $force, $profiles, $owner, $card);
                } elseif($profiles['@attributes']['typeName'] == 'Psychic Power') {
                    $this->handlePsy($profiles, $force, $owner, $card);
                }
            } else {
                foreach ($profiles as $profile) {
                    if (isset($profile['@attributes']) && isset($profile['@attributes']['typeName'])) {
                        if ($profile['@attributes']['typeName'] == 'Weapon') {
                            $this->handleWeapon($unit, $force, $profile, $owner, $card);
                        } elseif($profile['@attributes']['typeName'] == 'Psychic Power') {
                            $this->handlePsy($profile, $force, $owner, $card);
                        }
                    }
                }
            }
        }
    }

    private function unitPoints($unit): int
    {
        if (isset($unit['costs']) && isset($unit['costs']['cost']) && is_array($unit['costs']['cost'])) {
            foreach ($unit['costs']['cost'] as $c) {
                if (isset($c['@attributes']) && isset($c['@attributes']['typeId']) && $c['@attributes']['typeId'] == 'points') {
                    if(isset($unit['@attributes']) && isset($unit['@attributes']['number'])) {
                        return intval($c['@attributes']['value'] / $unit['@attributes']['number']);
                    } else {
                        return intval($c['@attributes']['value']);
                    }

                }
            }
            return 0;
        }
        return 0;
    }

}
