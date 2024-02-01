<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\TagsAttributesGenerator;

#[Route('/{_locale}/courses', requirements: ['_locale' => 'fr|en'], name: 'courses_')]
class CoursesController extends AbstractController
{
    #[Route('/', methods: ['GET'], name: 'index')]
    public function index(Request $request): Response
    {
        return $this->render('page/courses.html.twig', [
            //tags => $this->tagsAttributeGenerator->generate('courses')
            'tags' => TagsAttributesGenerator::generate([
                'SASS', 'PHP', 'HTML', 'CSS', 'Docker', 'Gestion de projet', 'Git', 'Programmation orientée objet', 'Application web', 'Symfony',
                'SQL', 'GitLab', 'Versionning', 'Architecture MVC', 'Fullstack', 'Back-End', 'Front-End', 'Développement web', 'Responsive Design',
                'CI/CD', 'Microservices'
            ]),
        ]);
    }
}