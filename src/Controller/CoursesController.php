<?php

namespace App\Controller;

use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/courses", name="courses_")
 */
class CoursesController extends AbstractController
{
    private string $coursesDirectoryPath;
    private $httpClient;

    public function __construct(string $coursesDirectoryPath, HttpClientInterface $pdfGeneratorClient)
    {
        $this->coursesDirectoryPath = $coursesDirectoryPath;
        $this->httpClient = $pdfGeneratorClient;
    }

    /**
     * @Route("/", methods={"GET"}, name="index")
     */
    public function index(Request $request): Response
    {
        $finder = new Finder();
        $finder->files()->in($this->coursesDirectoryPath);
        $courses = [];

        foreach ($finder as $file) {
            $courses[] = json_decode(file_get_contents($file->getPathName()), true);
        }

        return $this->render('courses/index.html.twig', [
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/{slug}.{_format}", methods={"GET"}, name="show", requirements={"_format"="json|pdf"}, defaults={"_format": "pdf"})
     */
    public function show(Request $request, String $slug, string $_format): Response
    {
        $course = json_decode(file_get_contents(sprintf($this->coursesDirectoryPath . '%s.json', $slug)), true);

        if ('json' === $_format) {
            return new JsonResponse($course);
        }

        $response = $this->httpClient->request('POST', '/', [
            'body' => json_encode([
                'contents' => base64_encode($this->renderView('courses/pdf.html.twig', [
                    'course' => $course,
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

        return $this->render('courses/show.html.twig', [
            'slug' => $slug
        ]);
    }
}