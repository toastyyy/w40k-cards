<?php


namespace App\Service;


use App\Entity\PsychicPower;
use Doctrine\ORM\EntityManagerInterface;

class PsychicPowerSerializer implements PsychicPowerSerializerInterface
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function serialize(PsychicPower $psychicPower, $mode = 'list')
    {
        return [
            'id' => $psychicPower->getId(),
            'name' => $psychicPower->getName(),
            'warpCharge' => $psychicPower->getWarpCharge(),
            'range' => $psychicPower->getRange(),
            'details' => $psychicPower->getDetails(),
            'ref' => $psychicPower->getRef(),
        ];
    }

    public function deserialize($data): ?PsychicPower
    {
        if(isset($data->id)) {
            $power = $this->em->getRepository(PsychicPower::class)->find($data->id);
        } else {
            $power = new PsychicPower();
        }

        if($power) {
            $power->setName($data->name ?? $power->getName());
            $power->setWarpCharge($data->warpCharge ?? $power->getWarpCharge());
            $power->setRange($data->range ?? $power->getRange());
            $power->setDetails($data->details ?? $power->getDetails());
            $power->setRef($data->ref ?? $power->getRef());
            return $power;
        }
        return null;
    }
}
