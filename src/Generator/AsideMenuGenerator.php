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

    public function generateAsideMenu(string $templatePath): array
    {
        $template = $this->twig->loadTemplate($templatePath);
        $page = $template->renderBlock('main_content', []);

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