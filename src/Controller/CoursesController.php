<?php

namespace App\Controller;

use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/courses", name="courses_")
 */
class CoursesController extends AbstractController
{
    private string $coursesPath;

    public function __construct(string $coursesPath)
    {
        $this->coursesPath = $coursesPath;
    }

    /**
     * @Route("/", methods={"GET"}, name="index")
     */
    public function index(Request $request): Response
    {
        $finder = new Finder();
        $finder->files()->in($this->coursesPath);
        $courses = [];

        foreach ($finder as $file) {
            $courses[] = json_decode(file_get_contents($file->getPathName()), true);
        }

        return $this->render('courses/index.html.twig', [
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/{slug}", methods={"GET"}, name="show")
     */
    public function show(Request $request, String $slug): Response
    {
        return $this->render('courses/show.html.twig', [
            'slug' => $slug
        ]);
    }
}