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
    private string $postsConfigPath;
    private string $coursesConfigPath;
    private string $teamConfigPath;

    public function __construct(string $postsConfigPath, string $coursesConfigPath, string $teamConfigPath)
    {
        $this->postsConfigPath = $postsConfigPath;
        $this->coursesConfigPath = $coursesConfigPath;
        $this->teamConfigPath = $teamConfigPath;
    }

    /**
     * @Route("/sitemap.xml", methods={"GET"}, name="sitemap")
     */
    public function sitemap(Request $request): Response
    {
        $posts = json_decode(file_get_contents($this->postsConfigPath), true);
        $postsID = [];
        foreach ($posts as $post) {
            $postsID[] = $post['id'];
        }

        $coursesFinder = new Finder();
        $coursesFinder->files()->in($this->coursesConfigPath);
        $courses = [];
        $coursesID = [];
        $pattern = sprintf('/%s.json$/', 'fr');

        foreach ($coursesFinder as $key => $file) {
            if (1 == preg_match($pattern, $file->getFileName())) {
                $courses[$key] = json_decode(file_get_contents($file->getPathName()), true);
                $coursesID[] = $courses[$key]['id'];
            }
        }

        // $cvsFinder = new Finder();
        // $cvsFinder->files()->in($this->teamConfigPath);
        // $cvs = [];
        // $cvsID = [];

        // foreach ($cvsFinder as $key => $file) {
        //     if (1 == preg_match($pattern, $file->getFileName())) {
        //         $cvs[$key] = json_decode(file_get_contents($file->getPathName()), true);
        //         $cvsID[] = $cvs[$key]['id'];
        //     }
        // }

        $response = new Response($this->renderView('seo/sitemap.xml.twig', [
            'posts' => $postsID,
            'courses' => $coursesID
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