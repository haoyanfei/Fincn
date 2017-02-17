<?php
/**
 * Created by PhpStorm.
 * User: haoyanfei <haoyanfei@putao.com>
 * DateTime: 17/2/17 上午10:34
 */

namespace Fincn\Provider;

use Fincn\Route\RouteCollection;
use Fincn\Route\Router;
use Pimple\Container;

use \Fincn\Route\RequestContext;

class RoutingProvider implements \Pimple\ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['route.class'] = '\\Fincn\Route\Route';
        // register some services and parameters
        // on $pimple
        $container['request.context'] = function () {
            return new RequestContext();
        };
        $container['route.collection'] = function () {
            return new RouteCollection();
        };
        $container['router'] = function () use ($container) {
            return new Router($container['route.collection'], $container['request.context']);
        };
    }
}