<?php

namespace App\Generator;

use Knp\Snappy\Pdf;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;

class PdfGenerator
{

    private $twig;

    private $snappy;

    private $parser;

    public function __construct(Environment $twig, Pdf $snappy, MarkdownParserInterface $parser)
    {
        $this->twig = $twig;
        $this->snappy = $snappy;
        $this->parser = $parser;
    }

    public function generate(Request $request, string $name, string $theme, bool $anonymous)
    {
        $locale = $request->getLocale();

        $cvFile = new \SplFileObject(sprintf('../src/Resources/cv/%s_%s.json', $name, $locale), 'r');
        $cvStyle = new \SplFileObject(sprintf('../public/build/cv_theme_%s.css', $theme), 'r');

        $html = sprintf(
            '
            <!DOCTYPE html>
            <html lang="%s">
                <head>
                    <meta name="viewport" content="width=device-width, 
                                                   initial-scale=1,
                                                   maximum-scale=1,
                                                   user-scalable=0" />
                    <style type="text/css">%s</style>
                </head>
                <body class="curriculum-vitae pdf" >%s</body>
            </html>',
            $locale,
            $cvStyle->fread($cvStyle->getSize()),
            $this->twig->render('team/_cv.html.twig', [
                'name' => $name,
                'theme' => $theme,
                'anonymous' => $anonymous,
                'data' => json_decode($cvFile->fread($cvFile->getSize()))
            ])
        );

        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContent($this->snappy->getOutputFromHtml($html, [
            'encoding' => 'utf-8',
            'margin-top' => 0,
            'margin-right' => 0,
            'margin-bottom' => 0,
            'margin-left' => 0,
        ]));

        return $response;
    }

    public function generateCourses(Request $request, string $name, string $theme = 'idci')
    {
        $locale = $request->getLocale();

        $courseStyle = new \SplFileObject(sprintf('../public/build/course_theme_%s.css', $theme), 'r');

        $html = sprintf(
            '
            <!DOCTYPE html>
            <html lang="%s">
                <head>
                    <meta name="viewport" content="width=device-width, 
                                                   initial-scale=1,
                                                   maximum-scale=1,
                                                   user-scalable=0" />
                    <style type="text/css">%s</style>
                </head>
                <body class="courses-pdf">%s</body>
            </html>',
            $locale,
            $courseStyle->fread($courseStyle->getSize()),
            $this->parser->transformMarkdown($this->twig->render(sprintf('courses/%s/%s.md', $locale, $name)))
        );

        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContent($this->snappy->getOutputFromHtml($html, [
            'encoding' => 'utf-8',
            'margin-top' => 0,
            'margin-right' => 0,
            'margin-bottom' => 0,
            'margin-left' => 0,
        ]));

        return $response;
    }
}
