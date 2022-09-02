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
    // private string $postsPath;

    // public function __construct(string $postsPath)
    // {
    //     $this->postsPath = $postsPath;
    // }

    /**
     * @Route("/", methods={"GET"}, name="index")
     */
    public function index(Request $request): Response
    {
        $posts = json_decode(file_get_contents('../src/Resources/blog/posts.json'), true);
        $postsByYears = [];
        $postsByCategories = [];

        foreach ($posts as $post) {
            $postYear = date("Y",strtotime($post['publicationDate']));
            $postsByYears[$postYear][] = $post;
            $postsByCategories[$post['category']][] = $post;
        }

        return $this->render('blog/index.html.twig', [
            'posts_by_years' => $postsByYears,
            'posts_by_categories' => $postsByCategories
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