<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/team", name="team_")
 */
class TeamController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="index")
     */
    public function index(Request $request): Response
    {
        return $this->render('team/index.html.twig');
    }

    /**
     * @Route("/{slug}", methods={"GET"}, name="member")
     */
    public function member(Request $request, String $slug): Response
    {
        return $this->render('team/show.html.twig', [
            'slug' => $slug
        ]);
    }
}