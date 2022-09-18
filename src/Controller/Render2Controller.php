<?php


namespace App\Controller;


use App\Service\ImageService;
use App\Service\Logger;
use App\Service\PdfService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class Render2Controller extends AbstractController
{
    private $kernel;
    private $stylesheet;
    private $catalogColors = [
        'Chaos - Death Guard' => '#3b442e',
        'Necrons' => '#3b442e'
    ];
    private $filenames = [];
    private $logger;

    public function __construct(KernelInterface $kernel, Logger $logger)
    {
        $this->kernel = $kernel;
        $this->logger = $logger;
    }

    public function renderCards(Request $request, PdfService $pdf, KernelInterface $kernel)
    {
        $data = $request->getContent();
        $data = json_decode($data);

        $zippedRosterFile = $kernel->getProjectDir() . '/var/roster/' . time() . '.rosz';
        file_put_contents($zippedRosterFile, base64_decode($data->data));

        $zip = new \ZipArchive();
        $rosterFile = null;
        if ($zip->open($zippedRosterFile) === TRUE) {
            $rosterFolder = $kernel->getProjectDir() . '/var/roster/' . time();
            $zip->extractTo($rosterFolder);
            $files = scandir($rosterFolder);
            foreach ($files as $file) {
                if (!is_file($rosterFolder . '/' . $file)) {
                    continue;
                }
                if (substr($file, strlen($file) - 4, 4) == '.ros') {
                    $rosterFile = $kernel->getProjectDir() . '/var/roster/' . time() . '_' . $file;
                    rename($rosterFolder . '/' . $file, $rosterFile);
                } else {
                    unlink($rosterFolder . '/' . $file);
                }
            }
            $zip->close();
            rmdir($rosterFolder);
            unlink($zippedRosterFile);
        } else {
            unlink($zippedRosterFile);
        }

        $xml = simplexml_load_string(file_get_contents($rosterFile), "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $data = json_decode($json, TRUE);

        $stylesheet = file_get_contents($kernel->getProjectDir() . '/public/assets/design2.css');

        $stylesheet = str_replace('/assets/bg-texture.png', 'data:image/png;base64,' . base64_encode(file_get_contents($kernel->getProjectDir() . '/public/assets/bg-texture.png')), $stylesheet);

        $catalogColors = [
            'Chaos - Death Guard' => '#283113'
        ];

        $rendered = $this->renderView('cards.html.twig', [
            'data' => $data,
            'stylesheet' => $stylesheet,
            'catalogColors' => $catalogColors
        ]);

        $filename = $pdf->createPdf($rendered, null, '--javascript-delay 1');

        return new BinaryFileResponse($filename);
    }

    public function renderCardsHtml(Request $request, PdfService $pdf, KernelInterface $kernel)
    {
        $data = $request->getContent();
        $data = json_decode($data);

        $zippedRosterFile = $kernel->getProjectDir() . '/var/roster/' . time() . '.rosz';
        file_put_contents($zippedRosterFile, base64_decode($data->data));

        $zip = new \ZipArchive();
        $rosterFile = null;
        if ($zip->open($zippedRosterFile) === TRUE) {
            $rosterFolder = $kernel->getProjectDir() . '/var/roster/' . time();
            $zip->extractTo($rosterFolder);
            $files = scandir($rosterFolder);
            foreach ($files as $file) {
                if (!is_file($rosterFolder . '/' . $file)) {
                    continue;
                }
                if (substr($file, strlen($file) - 4, 4) == '.ros') {
                    $rosterFile = $kernel->getProjectDir() . '/var/roster/' . time() . '_' . $file;
                    rename($rosterFolder . '/' . $file, $rosterFile);
                } else {
                    unlink($rosterFolder . '/' . $file);
                }
            }
            $zip->close();
            rmdir($rosterFolder);
            unlink($zippedRosterFile);
        } else {
            unlink($zippedRosterFile);
        }

        $xml = simplexml_load_string(file_get_contents($rosterFile), "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $data = json_decode($json, TRUE);

        $stylesheet = file_get_contents($kernel->getProjectDir() . '/public/assets/design2.css');

        $this->stylesheet = str_replace('/assets/bg-texture.png', 'data:image/png;base64,' . base64_encode(file_get_contents($kernel->getProjectDir() . '/public/assets/bg-texture.png')), $stylesheet);

        $rosterKey = sha1('w41k' . rand(0, 100000) . time());

        mkdir($kernel->getProjectDir() . '/public/cardstacks/' . $rosterKey);

        if (isset($data['forces']) && isset($data['forces']['force']) && !$this->isAssociative($data['forces']['force'])) {
            foreach ($data['forces']['force'] as $force) {
                if (isset($force['selections']) && isset($force['selections']['selection']) && !$this->isAssociative($force['selections']['selection'])) {
                    $this->logger->info('FORCE '. $force['@attributes']['id']);
                    mkdir($kernel->getProjectDir() . '/public/cardstacks/' . $rosterKey . '/' . $force['@attributes']['id']);
                    foreach ($force['selections']['selection'] as $selection) {
                        $this->renderSelection($rosterKey, $selection, $force, null);
                    }
                    $this->logger->info('ENDFORCE');
                }
            }
        } elseif(isset($data['forces']) && isset($data['forces']['force'])) {
            $force = $data['forces']['force'];
            if (isset($force['selections']) && isset($force['selections']['selection']) && !$this->isAssociative($force['selections']['selection'])) {
                $this->logger->info('FORCE '. $force['@attributes']['id']);
                mkdir($kernel->getProjectDir() . '/public/cardstacks/' . $rosterKey . '/' . $force['@attributes']['id']);
                foreach ($force['selections']['selection'] as $selection) {
                    $this->renderSelection($rosterKey, $selection, $force, null);
                }
                $this->logger->info('ENDFORCE');
            }
        }

        return new JsonResponse([
            'filenames' => $this->filenames,
            'roster_key' => $rosterKey
        ]);
    }

    public function test(Request $request, PdfService $pdf, KernelInterface $kernel)
    {
        $data = $request->getContent();
        $data = json_decode($data);

        $zippedRosterFile = $kernel->getProjectDir() . '/var/roster/' . time() . '.rosz';
        file_put_contents($zippedRosterFile, base64_decode($data->data));

        $zip = new \ZipArchive();
        $rosterFile = null;
        if ($zip->open($zippedRosterFile) === TRUE) {
            $rosterFolder = $kernel->getProjectDir() . '/var/roster/' . time();
            $zip->extractTo($rosterFolder);
            $files = scandir($rosterFolder);
            foreach ($files as $file) {
                if (!is_file($rosterFolder . '/' . $file)) {
                    continue;
                }
                if (substr($file, strlen($file) - 4, 4) == '.ros') {
                    $rosterFile = $kernel->getProjectDir() . '/var/roster/' . time() . '_' . $file;
                    rename($rosterFolder . '/' . $file, $rosterFile);
                } else {
                    unlink($rosterFolder . '/' . $file);
                }
            }
            $zip->close();
            rmdir($rosterFolder);
            unlink($zippedRosterFile);
        } else {
            unlink($zippedRosterFile);
        }

        $xml = simplexml_load_string(file_get_contents($rosterFile), "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $data = json_decode($json, TRUE);

        return new JsonResponse($data);
    }

    public function renderPdfAction(Request $request, ImageService $imageService) {
        $payload = json_decode($request->getContent());
        if($payload && isset($payload->path) && isset($payload->roster_key)) {
            if(!file_exists($this->kernel->getProjectDir(). '/public/cardstacks/'. $payload->roster_key. '/pdf')) {
                mkdir($this->kernel->getProjectDir(). '/public/cardstacks/'. $payload->roster_key. '/pdf');
            }
            $fullPath = $this->kernel->getProjectDir(). '/public/cardstacks/'. $payload->path;
            $filename = $imageService->createImage(file_get_contents($fullPath));
            $onlyName = explode('/', $payload->path);
            $onlyName = explode('.', $onlyName[count($onlyName) - 1])[0];
            rename($filename, $this->kernel->getProjectDir(). '/public/cardstacks/'. $payload->roster_key. '/pdf/'. $onlyName. '.jpg');
            return new JsonResponse(null);
        } else {
            return new JsonResponse(null, 400);
        }
    }

    public function downloadCards(Request $request, $rosterKey) {
        $path = $this->kernel->getProjectDir(). '/public/cardstacks/'. $rosterKey. '/pdf';
        if(is_dir($path)) {
            $zipPath = $this->kernel->getProjectDir(). '/public/cardstacks/'. $rosterKey. '/cards.zip';
            $zip = new \ZipArchive();
            if($zip->open($zipPath, \ZipArchive::CREATE)) {
                foreach (scandir($path) as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $zip->addFile($path. '/'. $file, '/'. $file);
                    }
                }
                $zip->close();
                $rosterPath = $this->kernel->getProjectDir(). '/public/cardstacks/'. $rosterKey;
                foreach (scandir($rosterPath) as $folder) {
                    if(is_dir($folder) && $folder != '.' && $folder != '..') {
                        $subfolder = $rosterPath. '/'. $folder;
                        foreach(scandir($subfolder) as $file) {
                            if(is_file($subfolder. '/'. $file)) {
                                unlink($subfolder. '/'. $file);
                            }
                        }
                        rmdir($subfolder);
                    }
                }
                $response = new BinaryFileResponse($zipPath);
                //unlink($zipPath);
                return $response;
            }
        }
        return new JsonResponse(null, 400);
    }

    private function renderSelection($rosterKey, $selection, $force, $owner = null)
    {
        $selection = $this->sanitizeSelection($selection);
        $this->logger->info(json_encode($selection), 'selections');
        if (isset($selection['@attributes']) && isset($selection['@attributes']['type'])) {
            $type = $selection['@attributes']['type'];
            if ($type == 'model' || $type == 'unit') {
                $this->renderUnit($rosterKey, $selection, $force, $owner);
            } elseif ($type == 'upgrade') {
                $this->renderUpgrade($rosterKey, $selection, $force, $owner);
            } else {
                $this->logger->info(json_encode($selection), 'unknown');
            }
        }
    }

    private function renderUpgrade($rosterKey, $unit, $force, $owner = null)
    {
        if (isset($unit['profiles']) && isset($unit['profiles']['profile'])) {
            $profiles = $unit['profiles']['profile'];

            if (isset($profiles['@attributes'])) {
                if ($profiles['@attributes']['typeName'] == 'Weapon') {
                    $this->renderWeapon($rosterKey, $unit, $force, $profiles, $owner);
                } elseif($profiles['@attributes']['typeName'] == 'Psychic Power') {
                    $this->renderPsy($rosterKey, $profiles, $force, $owner);
                }
            } else {
                foreach ($profiles as $profile) {
                    if (isset($profile['@attributes']) && isset($profile['@attributes']['typeName'])) {
                        if ($profile['@attributes']['typeName'] == 'Weapon') {
                            $this->renderWeapon($rosterKey, $unit, $force, $profile, $owner);
                        } elseif($profile['@attributes']['typeName'] == 'Psychic Power') {
                            $this->renderPsy($rosterKey, $profile, $force, $owner);
                        }
                    }
                }
            }
        }
    }

    private function renderUnit($rosterKey, $unit, $force, $owner = null)
    {
        if ($this->getUnitPoints($unit) > 0) {
            $html = null;
            if ($this->hasCategory($unit, 'Primarch') || $this->hasCategory($unit, 'Supreme Commander')) {
                $this->logger->info('MEGAUNIT '. $unit['@attributes']['name']);
                $html = $this->renderView('megaunit.html.twig', [
                    'unit' => $unit,
                    'force' => $force,
                    'catalogColors' => $this->catalogColors,
                    'inherited' => $owner,
                    'stylesheet' => $this->stylesheet
                ]);
            } else {
                $this->logger->info('NORMALUNIT '. $unit['@attributes']['name']);
                $html = $this->renderView('normalunit.html.twig', [
                    'unit' => $unit,
                    'force' => $force,
                    'catalogColors' => $this->catalogColors,
                    'inherited' => $owner,
                    'stylesheet' => $this->stylesheet
                ]);
            }
            if ($html) {
                $filename = 'unit-'. $this->slugify(explode(' w/', $unit['@attributes']['name'])[0]). '-' . substr(sha1(time() . json_encode($unit) . rand(0, 100)), 0, 8) . '.html';
                file_put_contents($this->kernel->getProjectDir() . '/public/cardstacks/' . $rosterKey . '/' . $force['@attributes']['id'] . '/' . $filename, $html);
                $this->filenames[] = $rosterKey . '/' . $force['@attributes']['id'] . '/' . $filename;
            }
        }

        if(isset($unit['profiles']) && isset($unit['profiles']['profile']) && !$this->isAssociative($unit['profiles']['profile'])) {
            foreach($unit['profiles']['profile'] as $profile) {
                if(isset($profile['@attributes']) && isset($profile['@attributes']['typeName'])) {
                    $type = $profile['@attributes']['typeName'];
                    if($type == 'Abilities') {
                        $this->renderAbility($rosterKey, $profile, $force, $unit);
                    }
                }
            }
        } elseif(isset($unit['profiles']) && isset($unit['profiles']['profile'])) {
            $profile = $unit['profiles']['profile'];
            $type = $profile['@attributes']['typeName'];
            if($type == 'Abilities') {
                $this->renderAbility($rosterKey, $profile, $force, $unit);
            }
        }



        if (isset($unit['selections']) && isset($unit['selections']['selection']) && !$this->isAssociative($unit['selections']['selection'])) {
            foreach ($unit['selections']['selection'] as $selection) {
                if(!isset($selection['@attributes']) && isset($unit['@attributes'])) {
                    $selection['@attributes'] = $unit['@attributes'];
                }
                if(!isset($selection['rules']) && isset($unit['rules'])) {
                    $selection['rules'] = $unit['rules'];
                }
                if(!isset($selection['categories']) && isset($unit['categories'])) {
                    $selection['categories'] = $unit['categories'];
                }
                $this->renderSelection($rosterKey, $selection, $force, $unit);
            }
        } elseif(isset($unit['selections']) && isset($unit['selections']['selection'])) {
            $selection = $unit['selections']['selection'];
            /* sometimes attributes need to be inherited from parent unit */
            if(!isset($selection['@attributes']) && isset($unit['@attributes'])) {
                $selection['@attributes'] = $unit['@attributes'];
            }
            if(!isset($selection['rules']) && isset($unit['rules'])) {
                $selection['rules'] = $unit['rules'];
            }
            if(!isset($selection['categories']) && isset($unit['categories'])) {
                $selection['categories'] = $unit['categories'];
            }
            $this->renderSelection($rosterKey, $selection, $force, $unit);
        }
    }

    private function renderAbility($rosterKey, $unit, $force, $owner = null)
    {
        $this->logger->info('ABILITY '. $unit['@attributes']['name']);
        $html = $this->renderView('ability.html.twig', [
            'unit' => $unit,
            'force' => $force,
            'owner' => $owner,
            'stylesheet' => $this->stylesheet
        ]);

        if ($html) {
            $filename = 'ability-'. $this->slugify($unit['@attributes']['name']). '-' . substr(sha1(time() . json_encode($unit) . rand(0, 100)), 0, 8) . '.html';
            file_put_contents($this->kernel->getProjectDir() . '/public/cardstacks/' . $rosterKey . '/' . $force['@attributes']['id'] . '/' . $filename, $html);
            $this->filenames[] = $rosterKey . '/' . $force['@attributes']['id'] . '/' . $filename;
        }
    }

    private function renderWeapon($rosterKey, $unit, $force, $profile, $owner = null)
    {
        $this->logger->info('WEAPON '. $unit['@attributes']['name']);
        $html = $this->renderView('weapon.html.twig', [
            'unit' => $unit,
            'force' => $force,
            'owner' => $owner,
            'profile' => $profile,
            'stylesheet' => $this->stylesheet
        ]);

        if ($html) {
            $filename = 'weapon-'. $this->slugify($unit['@attributes']['name']). '-'. substr(sha1(time() . json_encode($unit) . rand(0, 100)), 0, 8) . '.html';
            file_put_contents($this->kernel->getProjectDir() . '/public/cardstacks/' . $rosterKey . '/' . $force['@attributes']['id'] . '/' . $filename, $html);
            $this->filenames[] = $rosterKey . '/' . $force['@attributes']['id'] . '/' . $filename;
        }
    }

    private function renderPsy($rosterKey, $unit, $force, $owner = null)
    {
        $this->logger->info('PSY '. $unit['@attributes']['name']);
        $html = $this->renderView('psy.html.twig', [
            'unit' => $unit,
            'force' => $force,
            'owner' => $owner,
            'stylesheet' => $this->stylesheet
        ]);

        if ($html) {
            $filename = 'psy-'. $this->slugify($unit['@attributes']['name']). '-' . substr(sha1(time() . json_encode($unit) . rand(0, 100)), 0, 8) . '.html';
            file_put_contents($this->kernel->getProjectDir() . '/public/cardstacks/' . $rosterKey . '/' . $force['@attributes']['id'] . '/' . $filename, $html);
            $this->filenames[] = $rosterKey . '/' . $force['@attributes']['id'] . '/' . $filename;
        }
    }

    private function hasCategory($unit, $category)
    {
        if (isset($unit['categories']) && isset($unit['categories']['category']) && !$this->isAssociative($unit['categories']['category'])) {
            foreach ($unit['categories']['category'] as $c) {
                if (isset($c['@attributes']) && isset($c['@attributes']['name'])) {
                    if ($c['@attributes']['name'] == $category) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function getUnitPoints($unit)
    {
        if (isset($unit['costs']) && isset($unit['costs']['cost']) && !$this->isAssociative($unit['costs']['cost'])) {
            foreach ($unit['costs']['cost'] as $c) {
                if (isset($c['@attributes']) && isset($c['@attributes']['typeId']) && $c['@attributes']['typeId'] == 'points') {
                    if (isset($unit['@attributes']) && isset($unit['@attributes']['number'])) {
                        return intval($c['@attributes']['value'] / $unit['@attributes']['number']);
                    } else {
                        return intval($c['@attributes']['value']);
                    }
                }
            }
            return 0;
        }
        $this->logger->info(json_encode($unit), 'nocost');
        return 0;
    }

    private function slugify($string) {
        $delimiter = '-';
        // https://github.com/phalcon/incubator/blob/master/Library/Phalcon/Utils/Slug.php
        if (!extension_loaded('iconv')) {
            throw new \Exception('iconv module not loaded');
        }
        // Save the old locale and set the new locale to UTF-8
        $oldLocale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'en_US.UTF-8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        if (!empty($replace)) {
            $clean = str_replace((array)$replace, ' ', $clean);
        }
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = trim($clean, $delimiter);
        // Revert back to the old locale
        setlocale(LC_ALL, $oldLocale);
        return $clean;
    }

    private function isAssociative($arr) {
        return (array_merge($arr) !== $arr || count(array_filter($arr, 'is_string', ARRAY_FILTER_USE_KEY)) > 0);
    }

    private function sanitizeSelection($selection) {
        if(isset($selection['rules']) && isset($selection['rules']['rule']) && $this->isAssociative($selection['rules']['rule'])) {
            $selection['rules']['rule'] = [$selection['rules']['rule']];
        }
        if(isset($selection['categories']) && isset($selection['categories']['category']) && $this->isAssociative($selection['categories']['category'])) {
            $selection['categories']['category'] = [$selection['categories']['category']];
        }
        return $selection;
    }
}
