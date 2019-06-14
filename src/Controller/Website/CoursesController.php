<?php

namespace App\Controller\Website;

use App\Manager\CoursesManager;
use App\Generator\PdfGenerator;
use App\Generator\AsideMenuGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/{_locale}")
 */
class CoursesController extends AbstractController
{
    /**
     * @Route("/courses/{name}.pdf", name="coursespdf", methods={"GET"})
     */
    public function coursespdf(Request $request, PdfGenerator $pdfGenerator ,string $name)
    {
        return $pdfGenerator->generateCourses($request, $name);
    }

    /**
     * @Route("/courses", name="courses", methods={"GET"})
     */
    public function courses(Request $request, AsideMenuGenerator $asideMenuGenerator, CoursesManager $coursesManager)
    {
        return $this->render('courses/courses.html.twig', [
            'coursesContent' => $coursesManager->getTabCourses($request),
        ]);
    }
}

