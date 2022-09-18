<?php


namespace App\Service;


use App\Entity\Psyker;
use Doctrine\ORM\EntityManagerInterface;

class PsykerSerializer implements PsykerSerializerInterface
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function serialize(Psyker $psyker, $mode = 'list')
    {
        return [
            'id' => $psyker->getId(),
            'name' => $psyker->getName(),
            'cast' => $psyker->getCast(),
            'deny' => $psyker->getDeny(),
            'other' => $psyker->getOther(),
            'ref' => $psyker->getRef()
        ];
    }

    public function deserialize($data): ?Psyker
    {
        if(isset($data->id)) {
            $psyker = $this->em->getRepository(Psyker::class)->find($data->id);
        } else {
            $psyker = new Psyker();
        }

        if($psyker) {
            $psyker->setName($data->name ?? $psyker->getName());
            $psyker->setCast($data->cast ?? $psyker->getCast());
            $psyker->setDeny($data->deny ?? $psyker->getDeny());
            $psyker->setOther($data->other ?? $psyker->getOther());
            $psyker->setRef($data->ref ?? $psyker->getRef());
            return $psyker;
        }
        return null;
    }
}
