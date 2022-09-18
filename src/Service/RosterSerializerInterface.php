<?php


namespace App\Service;


use App\Entity\Roster;

interface RosterSerializerInterface
{
    public function serialize(Roster $roster, $mode = 'list');
    public function deserialize($data): ?Roster;
}
