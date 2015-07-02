<?php

namespace Generator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * I18nRouteGenerator.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 *
 */
class I18nRouteGenerator
{
    /**
     * @var array
     */
    private $languages;

    /**
     * @var array
     */
    private $routeParameters;

    /**
     * @var RouteCollection
     */
    private $routes;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * Constructor.
     *
     * @param RouteCollection $routes       The route to internationalize
     * @param UrlGenerator    $urlGenerator The url generator
     */
    public function __construct(RouteCollection $routes, UrlGenerator $urlGenerator)
    {
        $this->routes       = $routes;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Generate international routes.
     *
     * @param Request $request The request
     *
     * @return array
     */
    public function generate(Request $request)
    {
        $path       = str_replace($request->getBaseUrl(), '', $request->getRequestUri());
        $pathChunks = explode('?', $path);

        $requestContext = new RequestContext($request->getRequestUri());

        $matcher = new UrlMatcher($this->routes, $requestContext);
        $route   = $matcher->match($pathChunks[0]);

        $this->setRouteParameters($route);

        if (!in_array('_locale', array_keys($route))) {
            throw new \LogicException('You\'re route does not have a locale');
        }

        $routeName = $route['_route'];

        $generatedRoutes = $this->doGeneration($routeName);

        return $generatedRoutes;
    }

    /**
     * Do internationalization generation
     *
     * @param $routeName The route name
     *
     * @return array
     */
    private function doGeneration($routeName)
    {
        $routes     = array();
        $this->routeParameters;

        foreach ($this->languages as $language) {
            $this->routeParameters['_locale'] = $language;
            $generatedRoute = $this->urlGenerator->generate($routeName, $this->routeParameters);

            $routes[$language] = $generatedRoute;
        }

        return $routes;
    }

    /**
     * Set languages
     *
     * @param $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    /**
     * Set route parameters
     *
     * @param array $route
     */
    public function setRouteParameters($route)
    {
        unset($route['_controller'], $route['_route']);

        $this->routeParameters = $route;
    }
}
