<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/{_locale}")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog" )
     */
    public function blog(Request $request)
    {
        return $this->render('blog/blog.html.twig', [

        ]);
    }

    /**
     * @Route("/blog/{article}", name="blog_show")
     */
    public function show($article)
    {
        return $this->render('blog/article.html.twig', [
            'article' => $article,
        ]);
    }
}
