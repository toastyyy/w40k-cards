<?php


namespace App\Service;


use App\Entity\Card;

interface CardSerializerInterface
{
    public function serialize(Card $card, $mode = 'list');

    public function deserialize($data): ?Card;
}
