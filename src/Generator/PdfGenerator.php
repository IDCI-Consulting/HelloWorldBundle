<?php

namespace App\Generator;

use Knp\Snappy\Pdf;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PdfGenerator
{

    private $twig;

    private $snappy;

    public function __construct(Environment $twig, Pdf $snappy)
    {
        $this->twig = $twig;
        $this->snappy = $snappy;
    }

    public function generate(Request $request, string $name, string $theme, bool $anonymous)
    {
        $locale = $request->getLocale();

        $cvFile = new \SplFileObject(sprintf('../src/Ressources/cv/%s_%s.json', $name, $locale), 'r');
        $data = json_decode($cvFile->fread($cvFile->getSize()));

        $cvStyle = new \SplFileObject(sprintf('../public/build/cv_theme_%s.css', $theme), 'r');
        $cssContent = $cvStyle->fread($cvStyle->getSize());

        $html = sprintf(
            '
            <!DOCTYPE html>
            <html lang="%s">
                <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
                    <style type="text/css">%s</style>
                </head>
                <body>%s</body>
            </html>',
            $locale,
            $cssContent,
            $this->twig->render('team/_cv.html.twig', [
                'name' => $name,
                'theme' => $theme,
                'anonymous' => $anonymous,
                'data' => $data
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
}
