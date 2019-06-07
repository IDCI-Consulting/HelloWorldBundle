<?php

namespace App\Manager;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;

class BlogManager
{
    private $twig;

    private $parser;

    public function __construct(Environment $twig, MarkdownParserInterface $parser)
    {
        $this->twig = $twig;
        $this->parser = $parser;
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

    public function getArticle(Request $request, string $file)
    {
        $article = new \SplFileObject(sprintf('../src/', $name, $locale), 'r');
        $data = json_decode($cvFile->fread($cvFile->getSize()));

        return $article;
    }
}
