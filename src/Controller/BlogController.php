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

        $articlesByCategories = [];

        foreach ($articles as $articleTab) {
            foreach ($articleTab as $article) {
                if (!in_array($article["category"], $articlesByCategories)) {
                    $articlesByCategories[$article["category"]] = [];
                }
            }
        }

        foreach ($articles as $articleTab) {
            foreach ($articleTab as $article) {
                $articlesByCategories[$article["category"]][] = $article;
            }
        }

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
            'articles_by_categories' => $articlesByCategories
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