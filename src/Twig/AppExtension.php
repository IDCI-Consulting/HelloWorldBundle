<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\Environment;

class AppExtension extends AbstractExtension
{

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('shuffle', [$this, 'shuffleArray']),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('file_get_public_contents', [$this, 'getPublicFileContents']),
            new \Twig\TwigFunction('file_exists', [$this, 'isFileExists']),
            new \Twig\TwigFunction('get_age', [$this, 'getAge']),
            new \Twig\TwigFunction('build_aside_menu', [$this, 'buildAsideMenu']),
        ];
    }

    public function shuffleArray(array $array)
    {
        shuffle($array);
        return $array;
    }

    public function getPublicFileContents($filePath)
    {
        return file_get_contents(sprintf('%s/../../public/%s', __DIR__, $filePath));
    }

    public function isFileExists($filePath)
    {
        return file_exists(sprintf('%s/../%s', __DIR__, $filePath));
    }

    public function getAge($birthday, $format = 'Y-m-d')
    {
        if (is_string($birthday)) {
            $birthday = \DateTime::createFromFormat($format, $birthday);
        }

        if (!($birthday instanceof \DateTime)) {
            throw new \RuntimeException('The birthday is not a valid DateTime');
        }

        return $birthday
            ->diff(new \DateTime('now'))
            ->y
        ;
    }
}
