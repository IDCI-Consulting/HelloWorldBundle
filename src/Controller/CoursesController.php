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
    private string $coursesPath;
    private $httpClient;

    public function __construct(string $coursesPath, HttpClientInterface $pdfGeneratorClient)
    {
        $this->coursesPath = $coursesPath;
        $this->httpClient = $pdfGeneratorClient;
    }

    /**
     * @Route("/", methods={"GET"}, name="index")
     */
    public function index(Request $request, string $_locale): Response
    {
        $finder = new Finder();
        $finder->files()->in($this->coursesPath);
        $courses = [];
        $pattern = sprintf('/%s.json$/', $_locale);

        foreach ($finder as $file) {
            if (1 == preg_match($pattern, $file->getFileName())) {
                $courses[] = json_decode(file_get_contents($file->getPathName()), true);
            }
        }

        return $this->render('courses/index.html.twig', [
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/{slug}.{_format}", methods={"GET"}, name="show", requirements={"_format"="json|pdf"}, defaults={"_format": "pdf"})
     */
    public function show(Request $request, String $slug, string $_format, string $_locale): Response
    {
        try {
            $courseFile = new \SplFileObject(sprintf('../src/Resources/courses/%s_%s.json', $slug, $_locale), 'r');
        } catch(\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
        }

        $rawJson = $courseFile->fread($courseFile->getSize());
        $courseJson = json_decode($rawJson, true);

        if ('json' === $_format) {
            return new JsonResponse($courseJson);
        }

        // if ('html' === $_format) {
        //     return $this->render('courses/show.html.twig', [
        //         'slug' => $slug,
        //         'data' => $courseJson,
        //     ]);
        // }

        $response = $this->httpClient->request('POST', '/', [
            'body' => json_encode([
                'contents' => base64_encode($this->renderView('courses/pdf.html.twig', [
                    'course' => $courseJson,
                ])),
            ]),
        ]);

        //dd($response);

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