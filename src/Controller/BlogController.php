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
    private string $blogPostsFilePath;

    public function __construct(string $blogPostsFilePath)
    {
        $this->blogPostsFilePath = $blogPostsFilePath;
    }

    /**
     * @Route("/", methods={"GET"}, name="list")
     */
    public function list(Request $request): Response
    {
        $posts = json_decode(file_get_contents($this->blogPostsFilePath), true);
        $postsByYears = [];
        $postsByCategories = [];

        foreach ($posts as $post) {
            $postYear = \DateTime::createFromFormat('d/m/Y', $post['publicationDate'])->format('Y');
            $postsByYears[$postYear][] = $post;
            $postsByCategories[$post['category']][] = $post;
        }

        return $this->render('blog/list.html.twig', [
            'posts_by_years' => $postsByYears,
            'posts_by_categories' => $postsByCategories
        ]);
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