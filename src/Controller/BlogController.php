<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}/blog', requirements: ['_locale' => 'fr|en'], name: 'blog_')]
class BlogController extends AbstractController
{
    private string $blogPostsFilePath;

    public function __construct(string $blogPostsFilePath)
    {
        $this->blogPostsFilePath = $blogPostsFilePath;
    }

    #[Route('/', methods: ['GET'], name: 'list')]
    public function list(Request $request): Response
    {
        $posts = json_decode(file_get_contents($this->blogPostsFilePath), true);
        $postsByYears = [];
        $postsByCategories = [];
        $testimonies = [];
        $articles = [];

        usort($posts, function ($a, $b) {
            $dateA = \DateTime::createFromFormat("d/m/Y", $a['publicationDate'])->format('Y-m-d');
            $dateB = \DateTime::createFromFormat("d/m/Y", $b['publicationDate'])->format('Y-m-d');

            return strtotime($dateA) - strtotime($dateB);
        });

        foreach ($posts as $post) {
            $postYear = \DateTime::createFromFormat('d/m/Y', $post['publicationDate'])->format('Y');
            $postsByYears[$postYear][] = $post;
            $postsByCategories[$post['category']][] = $post;

            if ('Témoignage' === $post['category']) {
                $testimonies[] = $post;
            } else {
                $articles[] = $post;
            }
        }

        $lastTestimonies = array_slice($testimonies, -3);
        $lastArticles = array_slice($articles, -3);

        return $this->render('blog/list.html.twig', [
            'posts_by_years' => $postsByYears,
            'posts_by_categories' => $postsByCategories,
            'last_articles' => $lastArticles,
            'last_testimonies' => $lastTestimonies,
        ]);
    }

    #[Route('/post/{slug}', methods: ['GET'], name:'post')]
    public function post(Request $request, String $slug, string $_locale): Response
    {
        $posts = json_decode(file_get_contents($this->blogPostsFilePath), true);

        foreach ($posts as $post) {
            if ($post['id'] == $slug && !in_array($_locale, $post['available_languages'])) {
                $_locale = $post['available_languages'][0];
            }
        }

        $post = $this->renderView(sprintf('blog/%s/%s.md.twig', $_locale, $slug));

        return $this->render('blog/show.html.twig', [
            'slug' => $slug,
            'post' => $post
        ]);
    }
}