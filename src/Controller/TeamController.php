<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/{_locale}/team", requirements={"_locale": "fr|en"}, name="team_")
 */
class TeamController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $pdfGeneratorClient)
    {
        $this->httpClient = $pdfGeneratorClient;
    }

    /**
     * @Route("/", methods={"GET"}, name="list"),
     */
    public function list(Request $request): Response
    {
        return $this->render('team/list.html.twig');
    }

    /**
     * @Route("/{slug}.{_format}", methods={"GET"}, name="member", requirements={"_format"="json|html|pdf"}, defaults={"_format": "html"})
     */
    public function member(Request $request, string $slug, string $_format, string $_locale): Response
    {      
        try {
            $cvFile = new \SplFileObject(sprintf('../src/Resources/cv/%s_%s.json', $slug, $_locale), 'r');
        } catch(\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
        }

        $rawJson = $cvFile->fread($cvFile->getSize());
        $cvJson = json_decode($rawJson, true);

        if ('json' === $_format) {
            return new JsonResponse($cvJson);
        }

        if ('html' === $_format) {
            return $this->render('team/show.html.twig', [
                'slug' => $slug,
                'data' => $cvJson,
            ]);
        }

        $response = $this->httpClient->request('POST', '/', [
            'body' => json_encode([
                'contents' => base64_encode($this->renderView('team/pdf.html.twig', [
                    'data' => $cvJson,
                ])),
            ]),
        ]);

        $response = new Response($response->getContent());
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            sprintf('%s.pdf', $slug),
        );
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}