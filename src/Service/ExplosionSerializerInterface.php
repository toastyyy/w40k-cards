<?php


namespace App\Service;


use App\Entity\Explosion;

interface ExplosionSerializerInterface
{
    public function serialize(Explosion $explosion, $mode = 'list');

    public function deserialize($data): ?Explosion;
}
