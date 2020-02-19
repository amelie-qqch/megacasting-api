<?php

require_once '../vendor/autoload.php';
 
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

//TODO requête avec authentification

try
{
    
    ## Routes Castings
    $route1 = new Route(
      '/api/castings/p={pageCourante}',
      [
          'controller' => My\Controller\CastingController::class,
          'method'     => 'getCastings',
      ], 
      [
           'pageCourante' => '[0-9]+'
      ]
    );
    
//        $route1 = new Route(
//      '/api/id={email}:pswd={pswd}/castings/p={pageCourante}',
//      [
//          'controller' => My\Controller\CastingController::class,
//          'method'     => 'getCastings',
//      ], 
//      [
//           'pageCourante' => '[0-9]+'
//      ]
//    );
 
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
    
    $route3 = new Route(
        '/api/castings/domaine={domaine}/p={pageCourante}',
        [
            'controller' => My\Controller\CastingController::class,
            'method'     => 'getCastingsByDomaine',
        ],
        [
            'domaine' => '[a-zA-Z]+'
        ],
        [
            'pageCourante' => '[0-9]+'
        ]          
    );
    
    $route4 = new Route(
        '/api/castings/annonceur={annonceur}/p={pageCourante}',
        [
            'controller' => My\Controller\CastingController::class,
            'method'     => 'getCastingsByAnnonceur',
        ],
        [
            'annonceur' => '[a-zA-Z]+'
        ],
        [
            'pageCourante' => '[0-9]+'
        ]            
    );
    
    $route5 = new Route(
        '/api/castings',
        [
            'controller' => My\Controller\CastingController::class,
            'method'     => 'getLastCastings',
        ]
    );
    
    $route6 = new Route(
        '/api/castings/count',
        [
            'controller' => My\Controller\CastingController::class,
            'method'     => 'getCountCastings',
        ]
    );
    
    ## Routes Annonceur
    $route7 = new Route(
        '/api/annonceurs',
        [
            'controller' => My\Controller\AnnonceurController::class,
            'method'     => 'getAnnonceursLibelle',
        ]         
    );
    
    $route8 = new Route(
        '/api/annonceurs/p={pageCourante}' ,
        [
            'controller'    =>  My\Controller\AnnonceurController::class,
            'method'        =>  'getAnnonceurs'
        ],
            [
           'pageCourante' => '[0-9]+'
        ] 
       
    );
    $route9 = new Route(
        '/api/annonceur/id={id}' ,
        [
            'controller'    =>  My\Controller\AnnonceurController::class,
            'method'        =>  'getAnnonceur'
        ],
        [
           'id' => '[0-9]+'
        ] 
       
    );
    
    $route10 = new Route(
        '/api/annonceurs/count',
        [
            'controller' => My\Controller\AnnonceurController::class,
            'method'     => 'getCountAnnonceurs',
        ]
    );
    
    ## Routes Domaine
    
    $route11 = new Route(
      '/api/domaines',
      [
          'controller' => My\Controller\DomaineController::class,
          'method'     => 'getDomaines',
      ]
    );
    
    $route12 = new Route(
        '/api/castings/search={recherche}',
        [
            'controller' => My\Controller\CastingController::class,
            'method'     => 'getCastingsSearch',
        ],
        [
            'recherche' => '[a-zA-Z]+'
        ]
    );
    
    ## Routes Metiers
    
    $route13 = new Route(
        '/api/metiers/count',
        [
            'controller' => My\Controller\MetierController::class,
            'method'     => 'getCountMetiers',
        ]
    );
 
    // Add Route object(s) to RouteCollection object
    $routes = new RouteCollection();
    $routes->add('api_castings', $route1);
    $routes->add('api_casting', $route2);
    $routes->add('api_castingsByDomaine', $route3);
    $routes->add('api_castingsByAnnonceur', $route4);
    $routes->add('api_lastCastings', $route5);
    $routes->add('api_countCastings', $route6);
    $routes->add('api_annonceursLibelle', $route7);
    $routes->add('api_annonceurs', $route8);
    $routes->add('api_annonceur',$route9);
    $routes->add('api_countAnnonceurs', $route10);
    $routes->add('api_domaines', $route11);
    $routes->add('api_castingSearch',$route12);
    $routes->add('api_castingSearch',$route13);
    
    
 
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