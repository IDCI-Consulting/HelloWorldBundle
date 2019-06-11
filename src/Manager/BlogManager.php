<?php

namespace App\Manager;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use App\Generator\MetaTagsGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BlogManager
{
    private $twig;

    private $parser;

    private $metaTagsGenerator;

    private $urlGenerator;

    public function __construct(
        Environment $twig,
        MarkdownParserInterface $parser,
        MetaTagsGenerator $metaTagsGenerator,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->twig = $twig;
        $this->parser = $parser;
        $this->metaTagsGenerator = $metaTagsGenerator;
        $this->urlGenerator = $urlGenerator;
    }

    public function getLastArticles(Request $request): array
    {
        $lastArticles = array_slice($this->getArticleByDate($request), 0, 5, true);

        foreach ($lastArticles as $key => $article) {
            $content = $this->twig->render(sprintf('blog/%s/%s.md', $request->getLocale(), $article['file']));
            preg_match("/^(?!#)(?!!)((.)\W*){160} ((\w+\b)){1}/mU", $content, $matches);

            $article['summary'] = $this->parser->transformMarkdown(sprintf('%s ...', $matches[0]));
            $lastArticles[$key] = $article;
        }

        return $lastArticles;
    }

    public function getArticleByDate(Request $request): array
    {
        $articlesByDate = $this->twig->getGlobals()['blog'][$request->getLocale()]['articles'];

        usort($articlesByDate, function ($article1, $article2) {

            $article1['date'] = date_create_from_format('d-m-Y', $article1['date']);
            $article2['date'] = date_create_from_format('d-m-Y', $article2['date']);

            return $article2['date']->getTimestamp() - $article1['date']->getTimestamp();
        });

        return $articlesByDate;
    }

    public function getArticleByCategory(Request $request): array
    {
        $articleByCategory = [];
        $categories = $this->twig->getGlobals()['blog'][$request->getLocale()]['categories'];
        $articles = $this->getArticleByDate($request);
        foreach ($categories as $category) {
            $articleByCategory[$category] = [];

            foreach ($articles as $article) {
                if (in_array($category, $article['categories'])) {
                    array_push($articleByCategory[$category], $article);
                }
            }
        }

        return $articleByCategory;
    }

    public function getArticleContent(Request $request, string $file): string
    {
        return $this->parser->transformMarkdown(
            $this->twig->render(sprintf('blog/%s/%s.md', $request->getLocale(), $file))
        );
    }

    public function getMetaTags(Request $request, string $file): array
    {
        $metaTags = [];

        $articles = $this->twig->getGlobals()['blog'][$request->getLocale()]['articles'];

        foreach ($articles as $key => $article) {
            if ($article['file'] === $file) {
                $metaTags = $this->metaTagsGenerator->generate(
                    $article['title'],
                    $this->urlGenerator->generate('website_article', [
                        'file' => $file
                    ], UrlGeneratorInterface::ABSOLUTE_URL),
                    'article',
                    sprintf('%s/build/images/%s', $request->getHttpHost(), $article['image'])
                );
            }
        }

        return $metaTags;
    }
}
