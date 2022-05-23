<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog", name="blog_")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="index")
     */
    public function index(Request $request): Response
    {
        $articlesDatesString = file_get_contents('../src/Resources/blog/articles_dates.json');
        $articlesDates = json_decode($articlesDatesString, true);

        //dd($articlesDates);

        return $this->render('blog/index.html.twig', [
            'articles_dates' => $articlesDates
        ]);
    }

    /**
     * @Route("/article/{slug}", methods={"GET"}, name="article")
     */
    public function article(Request $request, String $slug): Response
    {
        return $this->render('blog/show.html.twig', [
            'slug' => $slug
        ]);
    }
}