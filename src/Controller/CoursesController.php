<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/courses", name="courses_")
 */
class CoursesController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="courses")
     */
    public function courses(Request $request): Response
    {
        return $this->render('courses/index.html.twig');
    }

    /**
     * @Route("/{slug}", methods={"GET"}, name="course")
     */
    public function course(Request $request, String $slug): Response
    {
        return $this->render('courses/show.html.twig', [
            'slug' => $slug
        ]);
    }
}