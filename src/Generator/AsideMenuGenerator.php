<?php

namespace App\Generator;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use App\Manager\BlogManager;
use Cocur\Slugify\SlugifyInterface;

class AsideMenuGenerator
{
    private $twig;

    private $blogManager;

    private $slugifier;

    public function __construct(Environment $twig, BlogManager $blogManager, SlugifyInterface $slugifier)
    {
        $this->twig = $twig;
        $this->blogManager = $blogManager;
        $this->slugifier = $slugifier;
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

    public function generateArticleAsideMenu(Request $request, string $file): array
    {
        $articleAsideMenu = [];
        $article = $this->blogManager->getArticleContent($request, $file);
        preg_match_all('/(?<title><h2>.*<\/h2>|<h3>.*<\/h3>)/', $article, $matched_title);

        foreach ($matched_title['title'] as $key => $title) {
            preg_match('/>(?<content>(.*))</', $title, $matched_content);
            preg_match('/<(?<tag>(..))>/', $title, $matched_tag);
        

            $articleAsideMenu[$key] = sprintf(
                '<a href="#%s" class="title-%s">%s</a>',
                $this->slugifier->slugify($matched_content['content']),
                $matched_tag['tag'],
                $matched_content['content']
            );
        }

        return $articleAsideMenu;
    }
}
