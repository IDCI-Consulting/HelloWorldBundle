<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/blog", requirements={"_locale": "fr|en"}, name="blog_")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="list")
     */
    public function list(Request $request): Response
    {
        return $this->render('blog/list.html.twig');
    }

    /**
     * @Route("/post/{slug}", methods={"GET"}, name="post")
     */
    public function post(Request $request, String $slug): Response
    {
        return $this->render('blog/show.html.twig', [
            'slug' => $slug
        ]);
    }
}