<?php

require_once '../vendor/autoload.php';
 
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

try
{
    // Init basic route
    $route1 = new Route(
      '/api/castings',
      [
          'controller' => My\Controller\CastingController::class,
          'method'     => 'getCastings'
      ]
    );
 
    // Init route with dynamic placeholders
    $route2 = new Route(
        '/api/castings/{id}',
        [
            'controller' => My\Controller\CastingController::class,
            'method'     => 'getCasting',
        ],
        [
            'id' => '[0-9]+'
        ]
    );
    
 
    // Add Route object(s) to RouteCollection object
    $routes = new RouteCollection();
    $routes->add('api_castings', $route1);
    $routes->add('api_casting', $route2);
 
    // Init RequestContext object
    $context = new RequestContext();
    
    $request = Request::createFromGlobals();

    $context->fromRequest($request);

    // Init UrlMatcher object
    $matcher = new UrlMatcher($routes, $context);

    // Find the current route
    $parameters = $matcher->match($context->getPathInfo());
    
    $controllerName = $parameters['controller'];
    $method         = $parameters['method'];
    
    unset($parameters['controller']);
    unset($parameters['method']);
    unset($parameters['_route']);
    
    $controller = new $controllerName();
    
    call_user_func_array([$controller, $method], $parameters);
}
catch (ResourceNotFoundException $e)
{
  echo $e->getMessage();
}