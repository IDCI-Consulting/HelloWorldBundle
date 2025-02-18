<?php

namespace App\Generator;

use ECorp\Bundle\SEOContentGeneratorBundle\Model\SitemapIndexItem;
use ECorp\Bundle\SEOContentGeneratorBundle\Model\SitemapUrlItem;
use ECorp\Bundle\SEOContentGeneratorBundle\SitemapGenerator\AbstractSitemapGenerator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PagesSitemapGenerator extends AbstractSitemapGenerator
{
    const GENERATOR_ALIAS = 'pages';
    const SITEMAP_UPDATED_AT = '2025-02-17';

    private string $blogPostsFilePath;
    private string $customersFilePath;
    private string $cvDirectoryPath;

    public function __construct(string $blogPostsFilePath, string $customersFilePath, string $cvDirectoryPath)
    {
        $this->blogPostsFilePath = $blogPostsFilePath;
        $this->customersFilePath = $customersFilePath;
        $this->cvDirectoryPath = $cvDirectoryPath;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired('route_names')->setAllowedTypes('route_names', ['array'])
        ;
    }

    protected function doGenerateSitemapIndexItems(array $options): array
    {
        $items = [];

        $items[] = (new SitemapIndexItem())
            ->setLocation(
                $this->router->generate(
                    'ecorp_seo_content_generator_sitemap',
                    ['alias' => self::GENERATOR_ALIAS, '_format' => $options['format']],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            )
        ;

        return $items;
    }

    protected function doGenerateSitemapUrlItems(array $options): array
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);

        $elements = $this->getElements($resolvedOptions);

        $items = [];
        foreach ($elements['route_names'] as $element) {
            if (!isset($elements['slugs'][$element])) {
                $items[] = (new SitemapUrlItem())
                    ->setLocation(
                        $this->router->generate($element, [], UrlGeneratorInterface::ABSOLUTE_URL)
                    )
                    ->setLastModification(new \DateTime(self::SITEMAP_UPDATED_AT))
                    ->setChangeFrequency(SitemapUrlItem::CHANGE_FREQUENCY_YEARLY)
                    ->setPriority(1)
                ;

                continue;
            }

            foreach ($elements['slugs'][$element] as $slug) {
                $items[] = (new SitemapUrlItem())
                    ->setLocation(
                        $this->router->generate($element, ['slug' => $slug], UrlGeneratorInterface::ABSOLUTE_URL)
                    )
                    ->setLastModification(new \DateTime(self::SITEMAP_UPDATED_AT))
                    ->setChangeFrequency(SitemapUrlItem::CHANGE_FREQUENCY_YEARLY)
                    ->setPriority(1)
                ;
            }
        }

        return $items;
    }

    private function getElements(array $options): array
    {
        $posts = json_decode(file_get_contents($this->blogPostsFilePath), true);
        $customers = json_decode(file_get_contents($this->customersFilePath), true);

        foreach ($posts as $post) {
            $options['slugs']['app_blog_post'][] = $post['id'];
        }

        foreach ($customers as $customer) {
            if (empty($customer['publicationDate'])) {
                continue;
            }

            $options['slugs']['app_customers_show'][] = $customer['id'];
        }

        $cvsFinder = new Finder();
        $cvsFinder->files()->in($this->cvDirectoryPath);

        foreach ($cvsFinder as $key => $file) {
            $options['slugs']['app_team_member'][] = pathinfo($file, PATHINFO_FILENAME);
        }

        return $options;
    }
}
