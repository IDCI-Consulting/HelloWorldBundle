<?php

namespace App\Controller;

use App\Utils\TagsAttributesGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}/clients', requirements: ['_locale' => 'fr|en'], name: 'customers_')]
class CustomerController extends AbstractController
{
    private string $customersFilePath;

    public function __construct(string $customersFilePath)
    {
        $this->customersFilePath = $customersFilePath;
    }

    #[Route('/', methods: ['GET'], name: 'list')]
    public function list(Request $request): Response
    {
        $customers = json_decode(file_get_contents($this->customersFilePath), true);
        $testimonies = array_filter($customers, function($customer) {
            if (!empty($customer['publicationDate'])) {
                return $customer;
            }
        });

        usort($testimonies, function ($a, $b) {
            $dateA = \DateTime::createFromFormat("d/m/Y", $a['publicationDate'])->format('Y-m-d');
            $dateB = \DateTime::createFromFormat("d/m/Y", $b['publicationDate'])->format('Y-m-d');

            return strtotime($dateA) - strtotime($dateB);
        });

        $lastTestimonies = array_slice($testimonies, -3);

        return $this->render('customer/list.html.twig', [
            'testimonies' => $testimonies,
            'last_testimonies' => $lastTestimonies,
            'customers' => $customers,
        ]);
    }

    #[Route('/{slug}', methods: ['GET'], name:'show')]
    public function show(Request $request, String $slug, string $_locale): Response
    {
        $customers = json_decode(file_get_contents($this->customersFilePath), true);
        $testimonies = array_filter($customers, function($customer) {
            if (!empty($customer['publicationDate'])) {
                return $customer;
            }
        });

        $tools = [];
        foreach ($testimonies as $testimony) {
            if ($testimony['id'] == $slug) {
                if (!in_array($_locale, $testimony['available_languages'])) {
                    $_locale = $testimony['available_languages'][0];
                }

                $tools = $testimony['tools'];
            }
        }

        try {
            $testimony = $this->renderView(sprintf('customer/%s/%s.md.twig', $_locale, $slug), [
                'tags' => TagsAttributesGenerator::generate($tools)
            ]);
        } catch (\Exception $e) {
            throw $this->createNotFoundException(sprintf('The page \'%s\' is not a customer testimony', $slug));
        }

        return $this->render('customer/show.html.twig', [
            'slug' => $slug,
            'testimony' => $testimony
        ]);
    }
}
