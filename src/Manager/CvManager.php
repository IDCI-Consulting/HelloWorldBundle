<?php

namespace Manager;

use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CvManager
{
    /**
     * @var \Twig_Environment $twig
     */
    private $twig;

    /**
     * @var MarkdownParser $markdown
     */
    private $markdown;

    /**
     * Constructor.
     *
     * @param \Twig_Environment $twig
     * @param MarkdownParser $markdown
     */
    public function __construct(\Twig_Environment $twig, MarkdownParser $markdown)
    {
        $this->twig     = $twig;
        $this->markdown = $markdown;
    }

    public function prepareMarkdownForHtml($name, $locale)
    {
        $markdownCv = $this->buildAsMarkdown($name, $locale);

        $htmlCv = sprintf('<div class="%s">', $name);

        $sections = array(
            array(
                'fr' => 'CURRICULUM VITAE',
                'en' => 'CURRICULUM VITAE'
            ),
            array(
                'fr' => 'À PROPOS DE MOI',
                'en' => 'ABOUT ME'
            ),
            array(
                'fr' => 'EXPERIENCES PROFESSIONNELLES',
                'en' => 'PROFESSIONAL EXPERIENCES'
            ),
            array(
                'fr' => 'PROJETS PERSONNELS',
                'en' => 'PERSONAL PROJECTS'
            ),
            array(
                'fr' => 'FORMATION',
                'en' => 'EDUCATION'
            ),
            array(
                'fr' => 'OUTILS INFORMATIQUES',
                'en' => 'SKILLS'
            ),
            array(
                'fr' => 'EXPERIENCES PERSONNELLES',
                'en' => 'PERSONAL EXPERIENCES'
            ),
        );

        foreach ($sections as $key => $section) {
            if ($key == count($sections) -1) {
                preg_match("/### " . $section[$locale] . "(.*)$/sU", $markdownCv, $matches);
            } else {
                preg_match("/### " . $section[$locale] . "(.*)###/sU", $markdownCv, $matches);
            }

            if (isset($matches[0])) {
                $class = str_replace(' ', '_', strtolower($section['en']));
                $htmlCv .= sprintf(
                    '<section markdown="1" class="cv-part '. $class .'">%s</section>',
                    $matches[0]
                );
            }
        }

        $htmlCv .= '</div>';

        $htmlCv = preg_replace('/###<\/section>/', '</section>', $htmlCv);

        return $htmlCv;
    }

    public function buildAsMarkdown($name, $locale)
    {
        try {
            $content = $this->twig->render(sprintf('contents/cv/%s_%s.md', $name, $locale));
        } catch (\Exception $e) {
            throw new NotFoundHttpException(sprintf('The %s\'s cv doesn\'t exist', $name));
        }

        return $content;
    }
}
