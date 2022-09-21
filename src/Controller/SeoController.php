<?php

namespace App\Controller;

use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="seo_")
 */
class SeoController extends AbstractController
{
    private string $blogPostsFilePath;
    private string $coursesDirectoryPath;
    private string $cvDirectoryPath;

    public function __construct(string $blogPostsFilePath, string $coursesDirectoryPath, string $cvDirectoryPath)
    {
        $this->blogPostsFilePath = $blogPostsFilePath;
        $this->coursesDirectoryPath = $coursesDirectoryPath;
        $this->cvDirectoryPath = $cvDirectoryPath;
    }

    /**
     * @Route("/sitemap.xml", methods={"GET"}, name="sitemap")
     */
    public function sitemap(Request $request): Response
    {
        $posts = json_decode(file_get_contents($this->blogPostsFilePath), true);
        foreach ($posts as $key => $post) {
            $posts[$post['id']] = $posts[$key];
            unset($posts[$key]);
        }

        $coursesFinder = new Finder();
        $coursesFinder->files()->in($this->coursesDirectoryPath);
        $courses = [];

        foreach ($coursesFinder as $key => $file) {
            $courses[pathinfo($file, PATHINFO_FILENAME)] = $file;
        }

        $cvsFinder = new Finder();
        $cvsFinder->files()->in($this->cvDirectoryPath);
        $cvs = [];

        foreach ($cvsFinder as $key => $file) {
            $cvs[pathinfo($file, PATHINFO_FILENAME)] = $file;
        }

        // dd($posts);
        // dd($courses);
        // dd($cvs);

        $response = new Response($this->renderView('seo/sitemap.xml.twig', [
            'posts' => $posts,
            'courses' => $courses,
            'cvs' => $cvs
        ]));
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }

    /**
     * @Route("/robots.txt", methods={"GET"}, name="robots")
     */
    public function robotsTxt(Request $request): Response
    {
        $response = $this->render('seo/robots_txt.html.twig');
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}