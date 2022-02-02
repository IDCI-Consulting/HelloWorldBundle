<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/homepage", methods={"GET"}, name="homepage")
     */
    public function homepage(Request $request): Response
    {
        return $this->render('home/homepage.html.twig');
    }
}