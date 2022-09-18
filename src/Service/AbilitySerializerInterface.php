<?php


namespace App\Service;


use App\Entity\Ability;

interface AbilitySerializerInterface
{
    public function serialize(Ability $ability, $mode = 'list');

    public function deserialize($data): ?Ability;
}
