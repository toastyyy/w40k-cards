<?php


namespace App\Service;


use App\Entity\Roster;

class RosterSerializer implements RosterSerializerInterface
{
    private $cardSerializer;
    public function __construct(CardSerializerInterface $cardSerializer)
    {
        $this->cardSerializer = $cardSerializer;
    }

    public function serialize(Roster $roster, $mode = 'list')
    {
        $serialized = [
            'id' => $roster->getId(),
            'customName' => $roster->getCustomName()
        ];

        if($mode == 'show') {
            $cards = [];
            foreach($roster->getCards() as $card) {
                $cards[] = $this->cardSerializer->serialize($card, 'list');
            }
            $serialized['cards'] = $cards;
        }
        return $serialized;
    }

    public function deserialize($data): ?Roster
    {
        return null;
    }
}
