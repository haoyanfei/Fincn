<?php
/**
 * Created by PhpStorm.
 * User: haoyanfei <haoyanfei@putao.com>
 * DateTime: 17/2/15 下午2:50
 */

namespace Fincn\Route;


use Fincn\Http\Request;

class Router
{
    protected $requestContext;
    protected $routeCollection;
    protected $matcher;


    public function __construct(RouteCollection $routeCollection, RequestContext $requestContext = null)
    {
        $this->requestContext = $requestContext ?: (new RequestContext());
        $this->routeCollection = $routeCollection;
    }

    public function setContext(RequestContext $context)
    {
        $this->context = $context;

        if (null !== $this->matcher) {
            $this->getMatcher()->setContext($context);
        }
    }

    public function getRouteCollection():RouteCollection
    {
        return $this->routeCollection;
    }

    public function getMatcher():UrlMatcher
    {
        return new UrlMatcher($this->routeCollection, $this->requestContext);
    }

    public function match($pathinfo)
    {
        return $this->getMatcher()->match($pathinfo);//['alias','mca','middleware']
    }

    public function matchRequest(RequestContext $request)
    {
        return $this->getMatcher()->matchRequest($request);
    }
}