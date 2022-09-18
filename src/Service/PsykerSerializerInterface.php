<?php


namespace App\Service;


use App\Entity\Psyker;

interface PsykerSerializerInterface
{
    public function serialize(Psyker $psyker, $mode = 'list');

    public function deserialize($psyker): ?Psyker;
}
