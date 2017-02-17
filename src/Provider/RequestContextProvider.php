<?php
/**
 * Created by PhpStorm.
 * User: haoyanfei <haoyanfei@putao.com>
 * DateTime: 17/2/17 下午3:38
 */

namespace Fincn\Provider;


use Fincn\Route\RequestContext;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RequestContextProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['request'] = function () {
            return new RequestContext();
        };
    }
}