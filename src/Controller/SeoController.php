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
    #[Route('/robots.txt', methods: ['GET'], name:'robots')]
    public function robots(Request $request): Response
    {
        $response = $this->render('seo/robots.txt.twig');
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}