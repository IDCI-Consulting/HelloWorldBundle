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
        $articles = json_decode(file_get_contents('../src/Resources/blog/articles.json'), true);

        //dd($articles);

        $articlesByCategory = [];

        foreach ($articles as $articleTab) {
            foreach ($articleTab as $article) {
                if (!in_array($article["category"], $articlesByCategory)) {
                    $articlesByCategory[$article["category"]] = [];
                }
            }
        }

        foreach ($articles as $articleTab) {
            foreach ($articleTab as $article) {
                $articleObject = (object)$article;
                $articlesByCategory[$article["category"]] = [$articleObject];
                dump($articlesByCategory);
            }
        }

        //dd($articlesByCategory);

        return $this->render('blog/index.html.twig', [
            'articles' => $articles
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