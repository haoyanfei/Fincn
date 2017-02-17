<?php
/**
 * Created by PhpStorm.
 * User: haoyanfei <haoyanfei@putao.com>
 * DateTime: 17/2/15 下午10:27
 */

namespace Fincn\Route;


class UrlMatcher
{

    protected $routes;
    protected $context;

    public function __construct(RouteCollection $routes, RequestContext $context)
    {
        $this->routes = $routes;
        $this->context = $context;
    }

    public function match($pathInfo)
    {
        $defaults = $this->matchCollection(rawurldecode($pathInfo), $this->routes);
        return $defaults;
    }

    public function matchRequest(RequestContext $requestContext)
    {
        return $this->match($requestContext->getPathInfo());
    }

    protected function matchCollection($pathinfo, RouteCollection $routes)
    {
        foreach ($routes as $route) {
            if (!in_array($this->context->getMethod(), $route->getMethods())) {
                continue;
            }
            if($route->getHost() != $this->context->getHost()){
                continue;
            }
            if ($pathinfo == $route->getPath()) {
                return $route->getDefaults();
            }
            //匹配
            if (!preg_match('~^' . $route->getRegex() . '$~x', $pathinfo, $matches)) {
                continue;
            }
            unset($matches[0]);
            $i = 1;
            foreach ($route->getRequirements() as $requirement => $pattern) {
                $route->setParam($requirement, $matches[$i++]);
            }
            if (!in_array($this->context->getMethod(), $route->getMethods())) {
                continue;
            }
            return $route;
        }

        return null;
    }
}