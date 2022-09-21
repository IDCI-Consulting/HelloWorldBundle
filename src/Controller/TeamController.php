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
 * @Route("/team", name="team_")
 */
class TeamController extends AbstractController
{
    private string $cvDirectoryPath;
    private $httpClient;

    public function __construct(string $cvDirectoryPath, HttpClientInterface $pdfGeneratorClient)
    {
        $this->cvDirectoryPath = $cvDirectoryPath;
        $this->httpClient = $pdfGeneratorClient;
    }

    /**
     * @Route("/", methods={"GET"}, name="index"),
     */
    public function index(Request $request): Response
    {
        return $this->render('team/index.html.twig');
    }

    /**
     * @Route("/{slug}.{_format}", methods={"GET"}, name="member", requirements={"_format"="json|html|pdf"}, defaults={"_format": "html"})
     */
    public function member(Request $request, string $slug, string $_format): Response
    {
        $cvJson = json_decode(file_get_contents(sprintf($this->cvDirectoryPath . '%s.json', $slug)), true);

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