<?php

namespace App\Controller\Website;

use App\Generator\PdfGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/{_locale}")
 */
class TeamController extends AbstractController
{
    /**
     * @Route("/team", name="team", methods={"GET"})
     */
    public function team()
    {
        return $this->render('team/team.html.twig');
    }

    /**
     * @Route("/cv/{theme}/{name}.pdf", name="cvpdf", methods={"GET"})
     */
    public function cvPdf(Request $request, string $name, string $theme, PdfGenerator $pdfGenerator)
    {
        return $pdfGenerator->generate($request, $name, $theme, $request->query->get('anonymous', false));
    }

    /**
     * @Route("/cv/{theme}/{name}.json", name="cvjson", methods={"GET"})
     */
    public function cvJson(Request $request, string $name) : JsonResponse
    {
        $cvFile = new \SplFileObject(sprintf('../src/Ressources/cv/%s_%s.json', $name, $request->getLocale()), 'r');

        return new JsonResponse(json_decode($cvFile->fread($cvFile->getSize())));
    }

    /**
     * @Route("/cv/{theme}/{name}", name="cv", methods={"GET"})
     */
    public function cv(Request $request, string $name, string $theme)
    {
        $cvFile = new \SplFileObject(sprintf('../src/Ressources/cv/%s_%s.json', $name, $request->getLocale()), 'r');

        return $this->render('team/cv.html.twig', [
            'name' => $name,
            'theme' => $theme,
            'data' => json_decode($cvFile->fread($cvFile->getSize()))
        ]);
    }
}
