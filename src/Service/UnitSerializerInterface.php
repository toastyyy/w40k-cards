<?php


namespace App\Service;


use App\Entity\Unit;

interface UnitSerializerInterface
{
    public function serialize(Unit $unit, $mode = 'list');

    public function deserialize($data): ?Unit;
}
