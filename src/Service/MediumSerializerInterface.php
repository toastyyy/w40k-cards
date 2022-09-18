<?php
namespace App\Service;

use App\Entity\Medium;

interface MediumSerializerInterface {
    public function serialize(Medium $medium, $mode = 'list');

    public function deserialize($data): ?Medium;
}