<?php


namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class PdfService
{
    private $container;
    private $key;
    private $kernel;
    private $endpoint = 'https://pdfservice.csc.cx/create.php';
    public function __construct(ContainerInterface $container, KernelInterface $kernel)
    {
        $this->container = $container;
        $this->kernel = $kernel;
    }

    public function createPdf($content, $footer = null, $options = ['margin' => ['top' => '0', 'left' => '0', 'right' => '0', 'bottom' => '0']]) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"http://pdf-service:3000/pdf");
        curl_setopt($ch, CURLOPT_POST, 1);

        $content = json_encode([
            'html' => $content,
            'options' => $options
        ]);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: '. strlen($content)
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $status = curl_getinfo($ch,CURLINFO_HTTP_CODE);

        curl_close($ch);

        if($status == 200) {
            $filename = $this->kernel->getProjectDir(). '/var/pdf/'. substr(md5(time()), 0, 10). '.pdf';
            file_put_contents($filename, $result);
            return $filename;
        } else {
            return $status. ' '. $result;
        }

        /*
        $this->key = $this->container->getParameter('pdf_creator_secret');

        if($this->key == null) {
            return 'pdf_creator_secret is not set. Please specify in parameters.';
        }


        if(is_array($options)) {
            $opts = '';
            foreach($options as $key => $val) {
                $opts .= ' --'. $key. ' '. $val;
            }
            $options = $opts;
        }

        $ch = curl_init ($this->endpoint. '?signature='. sha1($content. $this->key));
        curl_setopt_array ($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => [
                'html' => $content,
                'footer' => $footer,
                'options' => $options
            ],
            CURLOPT_HTTPHEADER => ['X-Signature: '. sha1($content. $this->key)]
        ]);

        $result = curl_exec ($ch);
        $status = curl_getinfo($ch,CURLINFO_HTTP_CODE);

        if($status == 200) {
            $filename = $this->kernel->getProjectDir(). '/'. substr(md5(time()), 0, 10). '.pdf';
            file_put_contents($filename, $result);
            return $filename;
        } else {
            return $status. ' '. $result;
        }*/
    }
}
