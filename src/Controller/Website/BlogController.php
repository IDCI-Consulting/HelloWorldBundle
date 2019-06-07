<?php

namespace App\Controller\Website;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Manager\BlogManager;
use App\Generator\AsideMenuGenerator;

/**
 * @Route("/{_locale}")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog", methods={"GET"})
     */
    public function blog(Request $request, BlogManager $blogManager, AsideMenuGenerator $asideMenuGenerator)
    {
        return $this->render('blog/blog.html.twig', [
            'lastArticles' => $blogManager->getLastArticles($request),
            'articlesByDate' => $blogManager->getArticleByDate($request),
            'articlesByCategory' => $blogManager->getArticleByCategory($request),
            'asideMenu' => $asideMenuGenerator->generateAsideMenu('blog/blog.html.twig', [
                'lastArticles' => $blogManager->getLastArticles($request),
                'articlesByDate' => $blogManager->getArticleByDate($request),
                'articlesByCategory' => $blogManager->getArticleByCategory($request)
            ])
        ]);
    }

    /**
     * @Route("/blog/article/{file}", name="article", methods={"GET"})
     */
    public function article(Request $request, string $file, BlogManager $blogManager, AsideMenuGenerator $asideMenuGenerator)
    {
        return $this->render('blog/article.html.twig', [
            'article' => $blogManager->getArticleContent($request, $file),
            'articleAsideMenu' => $asideMenuGenerator->generateArticleAsideMenu($request, $file),
            'metaTags' => $blogManager->getMetaTags($request, $file)
        ]);
    }
}
