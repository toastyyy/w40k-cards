<?php


namespace App\Service;

use App\Entity\Medium;
use CURLFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class MediumSerializer implements MediumSerializerInterface
{
    private $em;
    private $kernel;
    private $container;
    public function __construct(EntityManagerInterface $em, KernelInterface $kernel, ContainerInterface $container) {
        $this->em = $em;
        $this->kernel = $kernel;
        $this->container = $container;
    }

    public function serialize(Medium $medium, $mode = 'list')
    {
        return [
            'id' => $medium->getId(),
            'file' => $this->container->getParameter('backend_url'). $medium->getFile(),
            'knockedOutBgFile' => $this->container->getParameter('backend_url'). $medium->getKnockedOutBgFile(),
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

                    /* automatically remove background */
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://background-remover:5000',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => array('file'=> new CURLFILE($this->kernel->getProjectDir() .'/public/uploads/'. $newFilename)),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);

                    $newBgRemoveFilename = $medium->getId(). '.bgremoved.'. $suffix;
                    $absoluteBgRemovePath = $this->kernel->getProjectDir(). '/public/uploads/'. $newBgRemoveFilename;
                    file_put_contents($absoluteBgRemovePath, $response);
                    $medium->setKnockedOutBgFile('/uploads/'. $newBgRemoveFilename);

                    $medium->setSize(0);
                    $medium->setType($type);
                    return $medium;
                }
                
            }
        }
        return null;
    }
}
