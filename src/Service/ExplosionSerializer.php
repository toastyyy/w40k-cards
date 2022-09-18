<?php


namespace App\Service;


use App\Entity\Explosion;
use Doctrine\ORM\EntityManagerInterface;

class ExplosionSerializer implements ExplosionSerializerInterface
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function serialize(Explosion $explosion, $mode = 'list')
    {
        return [
            'id' => $explosion->getId(),
            'name' => $explosion->getName(),
            'diceRoll' => $explosion->getDiceRoll(),
            'distance' => $explosion->getDistance(),
            'mortalWounds' => $explosion->getMortalWounds(),
            'ref' => $explosion->getRef()
        ];
    }

    public function deserialize($data): ?Explosion
    {
        if(isset($data->id)) {
            $explosion = $this->em->getRepository(Explosion::class)->find($data->id);
        } else {
            $explosion = new Explosion();
        }

        if($explosion) {
            $explosion->setName($data->name ?? $explosion->getName());
            $explosion->setDiceRoll($data->diceRoll ?? $explosion->getDiceRoll());
            $explosion->setDistance($data->distance ?? $explosion->getDistance());
            $explosion->setMortalWounds($data->mortalWounds ?? $explosion->getMortalWounds());
            $explosion->setRef($data->ref ?? $explosion->getRef());
            return $explosion;
        }
        return null;
    }
}
