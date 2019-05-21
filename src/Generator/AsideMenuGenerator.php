<?php

namespace App\Generator;

use Twig\Environment;

class AsideMenuGenerator
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generateAsideMenu(string $templateName, array $vars = [], string $blockName = 'main_content'): array
    {
        $vars = array_merge($this->twig->getGlobals(), $vars);
        
        $page = $this->twig->loadTemplate($templateName)->renderBlock($blockName, $vars);

        $asideMenu = [];

        // Get all sections in the DOM with an id
        preg_match_all('/(?<section><section[ ]*id.*<\/section>)/siU', $page, $matched_sections);

        // Retrieve the ids and the text (inside h2 tag which is child of header)
        foreach ($matched_sections['section'] as $i => $section) {
            preg_match_all('/<section[ ]*id="(?<id>.+)".*<h2.*>(?<title>.*)</siU', $section, $matches);
            foreach ($matches['id'] as $j => $id) {
                $asideMenu[$id] = trim($matches['title'][$j]);
            }
        }

        return $asideMenu;
    }
}
