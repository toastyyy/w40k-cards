<?php


namespace App\Twig;


use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class UnitImageExtension extends AbstractExtension
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('loadImage', [$this, 'loadImage']),
            new TwigFilter('hasImage', [$this, 'hasImage']),
            new TwigFilter('unitPoints', [$this, 'unitPoints']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('hasCategory', [$this, 'hasCategory']),
            new TwigFunction('filterProfilesByType', [$this, 'filterProfilesByType']),
        ];
    }

    public function hasCategory($unit, $category) {
        if(isset($unit['categories']) && isset($unit['categories']['category']) && is_array($unit['categories']['category'])) {
            foreach($unit['categories']['category'] as $c) {
                if(isset($c['@attributes']) && isset($c['@attributes']['name'])) {
                    if($c['@attributes']['name'] == $category) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function hasImage(string $unitName, $type = 'unit'): string
    {
        $unitName = $this->slugify($unitName);

        if($type == 'unit') {
            $pathWithoutExtension = $this->kernel->getProjectDir() . '/public/units/' . $unitName;
        } else if($type == 'weapon') {
            $pathWithoutExtension = $this->kernel->getProjectDir() . '/public/weapons/' . $unitName;
        } else if($type == 'ability') {
            $pathWithoutExtension = $this->kernel->getProjectDir() . '/public/abilities/' . $unitName;
        } else if($type == 'psy') {
            return true;
        }


        $availableExtensions = ['jpg', 'jpeg', 'png'];

        foreach ($availableExtensions as $ext) {
            $path = $pathWithoutExtension . '.' . $ext;
            if (file_exists($path) && is_readable($path)) {
                return true;
            }
        }

        return false;
    }


    public function loadImage(string $unitName, $type = 'unit'): string
    {
        $unitName = $this->slugify($unitName);

        if($type == 'unit') {
            $pathWithoutExtension = $this->kernel->getProjectDir() . '/public/units/' . $unitName;
        } else if($type == 'weapon') {
            $pathWithoutExtension = $this->kernel->getProjectDir() . '/public/weapons/' . $unitName;
        } else if($type == 'ability') {
            $pathWithoutExtension = $this->kernel->getProjectDir() . '/public/abilities/' . $unitName;
        } else if($type == 'psy') {
            $content = file_get_contents($this->kernel->getProjectDir() . '/public/assets/psychic.jpg');
            return 'data:image/jpeg;base64,' . base64_encode($content);
        }


        $availableExtensions = ['jpg', 'jpeg', 'png'];

        foreach ($availableExtensions as $ext) {
            $path = $pathWithoutExtension . '.' . $ext;
            if (file_exists($path) && is_readable($path)) {
                $mimeType = mime_content_type($path);
                $content = file_get_contents($path);
                return 'data:' . $mimeType . ';base64,' . base64_encode($content);
            }
        }

        $content = file_get_contents($this->kernel->getProjectDir() . '/public/units/placeholder.jpg');
        return 'data:image/jpeg;base64,' . base64_encode($content);
    }

    private function slugify($string, $replace = array(), $delimiter = '-')
    {
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

    public function unitPoints($unit): int
    {
        if (isset($unit['costs']) && isset($unit['costs']['cost']) && is_array($unit['costs']['cost'])) {
            foreach ($unit['costs']['cost'] as $c) {
                if (isset($c['@attributes']) && isset($c['@attributes']['typeId']) && $c['@attributes']['typeId'] == 'points') {
                    if(isset($unit['@attributes']) && isset($unit['@attributes']['number'])) {
                        return intval($c['@attributes']['value'] / $unit['@attributes']['number']);
                    } else {
                        return intval($c['@attributes']['value']);
                    }

                }
            }
            return 0;
        }
        return 0;
    }

    public function filterProfilesByType($profiles, $type) {
        return array_filter($profiles, function($p) use($type) {
           if(isset($p['@attributes']) && isset($p['@attributes']['typeName']) && $p['@attributes']['typeName'] == $type) {
               return true;
           } else {
               return false;
           }
        });
    }
}
