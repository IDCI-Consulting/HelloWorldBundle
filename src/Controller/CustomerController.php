<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}/clients', requirements: ['_locale' => 'fr|en'], name: 'customers_')]
class CustomerController extends AbstractController
{
    private string $customerTestimoniesFilePath;

    public function __construct(string $customerTestimoniesFilePath)
    {
        $this->customerTestimoniesFilePath = $customerTestimoniesFilePath;
    }

    #[Route('/', methods: ['GET'], name: 'list')]
    public function list(Request $request): Response
    {
        $testimonies = json_decode(file_get_contents($this->customerTestimoniesFilePath), true);
        $customersByYear = [];

        usort($testimonies, function ($a, $b) {
            $dateA = \DateTime::createFromFormat("d/m/Y", $a['publicationDate'])->format('Y-m-d');
            $dateB = \DateTime::createFromFormat("d/m/Y", $b['publicationDate'])->format('Y-m-d');

            return strtotime($dateA) - strtotime($dateB);
        });

        $lastTestimonies = array_slice($testimonies, -3);

        foreach ($testimonies as $testimony) {
            if ("" === $testimony['endYear']) {
                $testimony['endYear'] = (new \DateTime('now'))->format('Y');
            }

            while ($testimony['beginYear'] <= $testimony['endYear']) {
                $customersByYear[$testimony['beginYear']][] = $testimony;

                $testimony['beginYear']++;
            }
        }

        krsort($customersByYear);

        return $this->render('customer/list.html.twig', [
            'testimonies' => $testimonies,
            'last_testimonies' => $lastTestimonies,
            'customers_by_year' => $customersByYear
        ]);
    }

    #[Route('/{slug}', methods: ['GET'], name:'show')]
    public function show(Request $request, String $slug, string $_locale): Response
    {
        $testimonies = json_decode(file_get_contents($this->customerTestimoniesFilePath), true);

        foreach ($testimonies as $testimony) {
            if ($testimony['id'] == $slug && !in_array($_locale, $testimony['available_languages'])) {
                $_locale = $testimony['available_languages'][0];
            }
        }

        try {
            $testimony = $this->renderView(sprintf('customer/%s/%s.md.twig', $_locale, $slug));
        } catch (\Exception $e) {
            throw $this->createNotFoundException(sprintf('The page \'%s\' is not a customer testimony', $slug));
        }

        return $this->render('customer/show.html.twig', [
            'slug' => $slug,
            'testimony' => $testimony
        ]);
    }
}
