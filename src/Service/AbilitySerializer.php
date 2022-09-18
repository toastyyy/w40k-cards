<?php


namespace App\Service;


use App\Entity\Ability;
use Doctrine\ORM\EntityManagerInterface;

class AbilitySerializer implements AbilitySerializerInterface
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function serialize(Ability $ability, $mode = 'list')
    {
        return [
            'id' => $ability->getId(),
            'name' => $ability->getName(),
            'description' => $ability->getDescription(),
            'ref' => $ability->getRef()
        ];
    }

    public function deserialize($data): ?Ability
    {
        if(isset($data->id)) {
            $ability = $this->em->getRepository(Ability::class)->find($data->id);
        } else {
            $ability = new Ability();
        }

        if($ability) {
            $ability->setName($data->name ?? $ability->getName());
            $ability->setDescription($data->description ?? $ability->getDescription());
            $ability->setRef($data->ref ?? $ability->getRef());
            return $ability;
        }
        return null;
    }
}
