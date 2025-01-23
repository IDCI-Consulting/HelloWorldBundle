<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name:'seo_')]
class SeoController extends AbstractController
{
    private string $blogPostsFilePath;
    private string $customersFilePath;
    private string $cvDirectoryPath;

    public function __construct(string $blogPostsFilePath, string $customersFilePath, string $cvDirectoryPath)
    {
        $this->blogPostsFilePath = $blogPostsFilePath;
        $this->customersFilePath = $customersFilePath;
        $this->cvDirectoryPath = $cvDirectoryPath;
    }

    #[Route('/sitemap.xml', methods: ['GET'], name: 'sitemap')]
    public function sitemap(Request $request): Response
    {
        $posts = json_decode(file_get_contents($this->blogPostsFilePath), true);

        foreach ($posts as $key => $post) {
            $posts[$post['id']] = $posts[$key];
            unset($posts[$key]);
        }

        $customers = json_decode(file_get_contents($this->customersFilePath), true);

        foreach ($customers as $key => $customer) {
            $customers[$customer['id']] = $customers[$key];
            unset($customers[$key]);
        }

        $cvsFinder = new Finder();
        $cvsFinder->files()->in($this->cvDirectoryPath);
        $cvs = [];

        foreach ($cvsFinder as $key => $file) {
            $cvs[pathinfo($file, PATHINFO_FILENAME)] = $file;
        }

        $response = new Response($this->renderView('seo/sitemap.xml.twig', [
            'posts' => $posts,
            'customers' => $customers,
            'cvs' => $cvs,
        ]));

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }

    #[Route('/robots.txt', methods: ['GET'], name:'robots')]
    public function robots(Request $request): Response
    {
        $response = $this->render('seo/robots.txt.twig');
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}