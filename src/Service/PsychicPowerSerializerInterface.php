<?php


namespace App\Service;


use App\Entity\PsychicPower;

interface PsychicPowerSerializerInterface
{
    public function serialize(PsychicPower $psychicPower, $mode = 'list');

    public function deserialize($data): ?PsychicPower;
}
