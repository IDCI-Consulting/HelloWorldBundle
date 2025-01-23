<?php

namespace App\Twig;

use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $publicDir;
    private $entrypointLookup;

    public function __construct(EntrypointLookupInterface $entrypointLookup, string $publicDir)
    {
        $this->publicDir = $publicDir;
        $this->entrypointLookup = $entrypointLookup;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('encore_entry_css_source', [$this, 'getEncoreEntryCssSource']),
            new TwigFunction('get_age', [$this, 'getAge']),
        ];
    }

    public function getEncoreEntryCssSource(string $entryName): string
    {
        $this->entrypointLookup->reset();
        $files = $this->entrypointLookup->getCssFiles($entryName);

        $source = '';
        foreach ($files as $file) {
            $source .= file_get_contents($this->publicDir.'/'.$file);
        }

        return $source;
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