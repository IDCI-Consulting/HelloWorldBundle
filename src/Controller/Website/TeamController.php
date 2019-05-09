<?php

namespace App\Controller\Website;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}", methods="GET")
 */
class TeamController extends AbstractController
{
    /**
     * @Route("/team", name="team")
     */
    public function team()
    {
        return $this->render('team/team.html.twig');
    }

    /**
     * @Route("/cv/{name}.{_format}", name="vcard")
     */
    public function vcard($name, $_format=NULL)
    {
        return $this->render('team/cv.html.twig', [
            'name' => $name,
            'format' => $_format,
        ]);
    }
}
