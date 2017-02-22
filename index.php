<?php
require_once 'vendor/autoload.php';
echo '<pre>';
echo 'test';

$application = new \Fincn\Application();


//var_dump($_SERVER);
$request_context = createRequestFromGlobal($_SERVER);


$application->register(new \Fincn\Provider\RequestContextProvider(), ['request_context_params'=>$request_context]);

var_dump($request = $application['request_context_params']);


//var_dump($request);
$routeCollection = new \Fincn\Route\RouteCollection();

$routeCollection->setHost('192.168.1.128')->group('/admin')
    ->add('/', ['alias' => 'homepage', 'mca' => 'Cms\Test1::test1Action', 'middleware' => 'route.auth'])
    ->add('/xx', ['mca' => '\Fincn\App\Demo::test2Action',])
    ->add('/test/{haoyanfei}', ['mca' => '\Fincn\App\Demo::test3Action'])
    ->add('/test/{name}/{te}', ['mca' => '\Fincn\App\Demo::test4Action']);


$routeCollection->group('/api')
    ->add('/', []);

//$baseUrl = '', $method = 'GET', $host = 'localhost', $scheme = 'http', $httpPort = 80, $httpsPort = 443, $path = '/', $queryString = ''
//var_dump($routeCollection->all());
$requestContext = new \Fincn\Route\RequestContext(
    $request['base_url'],
    $request['method'],
    $request['domain'],
    $request['scheme'],
    0,
    0,
    $request['path'],
    $request['query_string']??''
);
$route = new \Fincn\Route\Router($routeCollection, $requestContext);


$res = $route->getMatcher()->matchRequest($requestContext);

list($controller,$action) = explode('::',$res->getDefault('mca'));
list($module,$c) = explode("\\",$controller);
$new = "\\App\\{$module}\\Controller\\{$c}Controller";
$cls = (new $new())->$action;


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

