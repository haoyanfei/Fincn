<?php
/**
 * Created by PhpStorm.
 * User: haoyanfei <haoyanfei@putao.com>
 * DateTime: 17/2/14 下午5:30
 */

namespace Fincn\Route;

class RouteCollection implements \Countable, \IteratorAggregate
{
    public $route;
    public $routesMap = [];
    public $regex = [];
    protected $prefix;
    protected $host;

    const VARIABLE_REGEX = <<<'REGEX'
\{
    \s* ([a-zA-Z][a-zA-Z0-9_]*) \s*
    (?:
        : \s* ([^{}]*(?:\{(?-1)\}[^{}]*)*)
    )?
\}
REGEX;


    const DEFAULT_DISPATCH_REGEX = '\w+';
    const DEFAULT_OPTIONAL_REGEX = '\w*';


    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    public function group($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function count()
    {
        return count($this->routesMap);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->routesMap);
    }

    public function all()
    {
        return $this->routesMap;
    }

    public function get($name)
    {
        return isset($this->routesMap[$name]) ? $this->routesMap[$name] : null;
    }


    public function createRoute($path, $defaults, $methods = [], array $options = [], $host = '', $schemes = []):Route
    {
        return new Route($path, $defaults, $methods, $options, $host ?: $this->host, $schemes);
    }

    public function add($path, $defaults, $methods = ['GET'], $options = [], $host = '', $schemes = [])
    {
        $this->addRoute($path, $defaults, $methods, $options, $host ?: $this->host, $schemes);
        return $this;
    }


    public function addRoute($path, $defaults, $methods = ['POST', 'GET'], $options = [], $host = '', $schemes = [])
    {
        $route = $this->createRoute($path, $defaults, $methods, $options, $host, $schemes);
        preg_match_all('~' . self::VARIABLE_REGEX . '~x', $path, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $path = str_replace($match[0], '(' . (isset($match[2]) ? $match[2] : static::DEFAULT_DISPATCH_REGEX) . ')', $path);
            $route->setRequirement($match[1], isset($match[2]) ? $match[2] : static::DEFAULT_DISPATCH_REGEX);
        }

        $path = rtrim(trim($this->prefix . $path), '/');

        $route->setRegex($path);

        $this->routesMap[$path] = $route;

        return $route;

    }

    public function isStaticRoute($path)
    {
        return false !== strpos($path, '*');
    }

}
