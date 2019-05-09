<?php

namespace App\Controller\Website;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}")
 */
class TeamController extends AbstractController
{
    /**
     * @Route("/team", name="team", methods={"GET"})
     */
    public function team()
    {
        return $this->render('team/team.html.twig');
    }

    /**
     * @Route("/cv/{name}.{_format}", name="vcard", methods={"GET"})
     */
    public function vcard($name, $_format=NULL)
    {
        return $this->render('team/cv.html.twig', [
            'name' => $name,
            'format' => $_format,
        ]);
    }
}
