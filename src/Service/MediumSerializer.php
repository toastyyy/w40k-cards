<?php


namespace App\Service;

use App\Entity\Medium;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class MediumSerializer implements MediumSerializerInterface
{
    private $em;
    private $kernel;
    public function __construct(EntityManagerInterface $em, KernelInterface $kernel) {
        $this->em = $em;
        $this->kernel = $kernel;
    }

    public function serialize(Medium $medium, $mode = 'list')
    {
        return [
            'id' => $medium->getId(),
            'file' => 'http://'. $_SERVER['SERVER_NAME']. $medium->getFile(),
            'size' => $medium->getSize(),
            'type' => $medium->getType()
        ];
    }

    public function deserialize($data): ?Medium
    {
        if(isset($data->id)) {
            $medium = $this->em->getRepository(Medium::class)->find($data->id);
            return $medium;
        } else if(isset($data->content) && isset($data->name)) {
            $decoded = base64_decode(explode('base64,', $data->content, 2)[1]);
            $type = strtolower(explode(';base64', explode('data:', $data->content,2)[1])[0]);
            if($decoded) {
                $medium = new Medium();
                $suffix = null;
                switch($type) {
                    case 'image/jpeg':
                        $suffix = 'jpg';
                        break;
                    case 'image/png':
                        $suffix = 'png';
                        break;
                }
                if($suffix) {
                    $newFilename = $medium->getId(). '.'. $suffix;
                    $absolutePath = $this->kernel->getProjectDir(). '/public/uploads/'. $newFilename;
                    file_put_contents($absolutePath, $decoded);
                    $medium->setFile('/uploads/'. $newFilename);
                    $medium->setSize(0);
                    $medium->setType($type);
                    return $medium;
                }
                
            }
        }
        return null;
    }
}
