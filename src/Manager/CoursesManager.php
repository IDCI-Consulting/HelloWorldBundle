<?php

namespace App\Manager;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;

class CoursesManager 
{
    private $regex;

    private $twig;

    private $parser;

    public function __construct(Environment $twig, MarkdownParserInterface $parser)
    {
        $this->regex = "/".
                        "(##(?<title>.*))??".
                        "(\[description\](?<description>.*))??".
                        "\{(?<day>.*)\}".
                        "(?<content>[?.\n\wéàèçâô#=<>()\/ *';&\\\"'\-,:!]*?)/siU";
        $this->twig = $twig;
        $this->parser = $parser;
    }

    public function getCoursesData(Request $request, string $name): array
    {
        $content = $this->parser->transformMarkdown($this->twig->render(sprintf('courses/%s/%s.md', $request->getLocale(), $name)));
        preg_match_all($this->regex, $content, $matches);

        return $matches;
    }

    public function getTabCourses(Request $request)
    {
        $tabcourses = [];
        foreach($this->twig->getGlobals()['courses'] as $i => $course) {
            $tabcourses[$i] = $this->getCoursesData($request, $course['name']);
        }

        return $tabcourses;
    }
}