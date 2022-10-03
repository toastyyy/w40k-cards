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

    public function createPdf($content, $footer = null, $options = '') {
        /*$rootDir = $this->kernel->getProjectDir(). '/w40k-renderer';
        file_put_contents($rootDir. '/doc.html', $content);
        exec('cd '. $rootDir. ' && npm run electron-pdf');
        if(file_exists($rootDir. '/doc.pdf')) {
            $result = file_get_contents($rootDir. '/doc.pdf');
            $filename = $this->kernel->getProjectDir(). '/var/pdf/'. substr(md5(time()), 0, 10). '.pdf';
            file_put_contents($filename, $result);
            return $filename;
        } else{
            return null;
        }*/

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
        }
    }
}
