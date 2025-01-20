<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/{_locale}/team', name: 'team_')]
class TeamController extends AbstractController
{
    private string $cvDirectoryPath;
    private $httpClient;

    public function __construct(string $cvDirectoryPath, HttpClientInterface $pdfGeneratorClient)
    {
        $this->cvDirectoryPath = $cvDirectoryPath;
        $this->httpClient = $pdfGeneratorClient;
    }

    #[Route('/', methods: ['GET'], name: 'list')]
    public function list(Request $request): Response
    {
        return $this->render('team/list.html.twig');
    }

    #[Route('/{slug}.{_format}', methods: ['GET'], name:'member', requirements: ['_format' => 'json|html|pdf'], defaults: ['_format' => 'html'])]
    public function member(Request $request, string $slug, string $_format): Response
    {
        $filesystem = new Filesystem();
        $jsonFilePath = sprintf('%s%s.json', $this->cvDirectoryPath, $slug);

        if (!$filesystem->exists($jsonFilePath)) {
            throw $this->createNotFoundException(sprintf('The member \'%s\' does\'t exists', $slug));
        }

        $cv = json_decode(file_get_contents(sprintf($this->cvDirectoryPath . '%s.json', $slug)), true);

        if ('json' === $_format) {
            return new JsonResponse($cv);
        }

        if ('html' === $_format) {
            return $this->render('team/show.html.twig', [
                'slug' => $slug,
                'data' => $cv,
            ]);
        }

        if ((bool)$request->query->get('debug', false)) {
            return $this->render('team/pdf.html.twig', [
                'data' => $cv,
            ]);
        }

        $response = $this->httpClient->request('POST', '/1/pdf', [
            'body' => json_encode([
                'html' => $this->renderView('team/pdf.html.twig', [
                    'data' => $cv,
                ]),
                'export' => [
                    'format' => 'A4',
                    'margin' => [
                        'bottom' => '20px',
                        'left' => '20px',
                        'right' => '20px',
                        'top' => '20px'
                    ]
                ],
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