<?php
require_once 'vendor/autoload.php';
echo '<pre>';


$application = new \Fincn\Application();


var_dump($application::$app['request.context']);


//var_dump($_SERVER);
$request = createRequestFromGlobal($_SERVER);


$application->register(new \Fincn\Provider\RequestContextProvider(), $request);

var_dump($application);

//
////var_dump($request);
//$routeCollection = new \Fincn\Route\RouteCollection();
//
//$routeCollection->setHost('remote')->group('/admin')
//    ->add('/', ['alias' => 'homepage', 'mca' => '\Fincn\App\Demo::test1Action', 'middleware' => 'route.auth'])
//    ->add('/xx', ['mca' => '\Fincn\App\Demo::test2Action',])
//    ->add('/test/{haoyanfei}', ['mca' => '\Fincn\App\Demo::test3Action'])
//    ->add('/test/{name}/{te}', ['mca' => '\Fincn\App\Demo::test4Action']);
//
//
//$routeCollection->group('/api')
//    ->add('/', []);
//
////$baseUrl = '', $method = 'GET', $host = 'localhost', $scheme = 'http', $httpPort = 80, $httpsPort = 443, $path = '/', $queryString = ''
////var_dump($routeCollection->all());
//$requestContext = new \Fincn\Route\RequestContext(
//    $request['base_url'],
//    $request['method'],
//    $request['domain'],
//    $request['scheme'],
//    0,
//    0,
//    $request['path'],
//    $request['query_string']
//);
//$route = new \Fincn\Route\Router($routeCollection, $requestContext);
//
//
//$res = $route->getMatcher()->matchRequest($request);
//
//var_dump($requestContext->setParams($res->getParams())->getParams());

function createRequestFromGlobal($serverParams)
{
    $request = [];
    if (isset($serverParams['REQUEST_SCHEME'])) {
        $request['scheme'] = strtolower($serverParams['REQUEST_SCHEME']) . '://';
    } else {
        if (isset($serverParams['HTTPS']) && 'on' === $serverParams['HTTPS']) {
            $request['scheme'] = 'https://';
        }
    }
    if (!isset($request['scheme'])) {
        $request['scheme'] = 'http://';
    }
    if (isset($serverParams['HTTP_HOST'])) {
        $request['base_url'] = $serverParams['HTTP_HOST'];
        list($request['domain'],) = explode(':', $request['base_url']);
    }

    if (isset($serverParams['SERVER_PORT']) && !empty($serverParams['SERVER_PORT'])) {
        $request['port'] = $serverParams['SERVER_PORT'];
    }

    if (isset($serverParams['REQUEST_METHOD'])) {
        $request['method'] = $serverParams['REQUEST_METHOD'];
    }
    if (isset($serverParams['PATH_INFO'])) {
        $request['path'] = $serverParams['PATH_INFO'];
    }

    if (isset($serverParams['REQUEST_URI'])) {
        $request['query_uri'] = $serverParams['REQUEST_URI'];
    }
    if (isset($serverParams['QUERY_STRING']) && !empty($serverParams['QUERY_STRING'])) {
        $request['query_string'] = $serverParams['QUERY_STRING'];
    }
    return $request;
}

