<?php
/**
 * Created by PhpStorm.
 * User: haoyanfei <haoyanfei@putao.com>
 * DateTime: 17/2/15 下午2:51
 */

namespace Fincn\Route;


class RequestContext
{

    private $baseUrl;
    private $method;
    private $host;
    private $scheme;
    private $httpPort;
    private $httpsPort;
    private $pathInfo;
    private $queryString;

    protected $params;

    public function __construct($baseUrl = '', $method = 'GET', $host = 'localhost', $scheme = 'http', $httpPort = 80, $httpsPort = 443, $path = '/', $queryString = '')
    {
        $this->setBaseUrl($baseUrl);
        $this->setMethod($method);
        $this->setHost($host);
        $this->setScheme($scheme);
        $this->setHttpPort($httpPort);
        $this->setHttpsPort($httpsPort);
        $this->setPathInfo($path);
        $this->setQueryString($queryString);//args
    }

    public function fromRequest($baseUrl = '', $method = 'GET', $host = 'localhost', $scheme = 'http', $httpPort = 80, $httpsPort = 443, $path = '/', $queryString = '')
    {
        return new self($baseUrl, $method, $host, $scheme, $httpPort, $httpsPort, $path, $queryString);
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
        return $this;
    }

    public function setHttpPort($httpPort)
    {
        $this->httpPort = $httpPort;
        return $this;
    }

    public function setHttpsPort($httpsPort)
    {
        $this->httpsPort = $httpsPort;
        return $this;
    }

    public function setPathInfo($path)
    {
        $this->pathInfo = $path;
        return $this;
    }

    public function getPathInfo()
    {
        return $this->pathInfo;
    }

    public function setQueryString($queryString)
    {
        $this->queryString = $queryString;
        parse_str($queryString, $params);
        $this->setParams($params);
        return $this;
    }

    public function setParam($k, $name)
    {
        $this->params[$k] = $name;
        return $this;
    }

    public function setParams(array $params)
    {
        foreach ($params as $k => $v) {
            $this->params[$k] = $v;
        }
        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }
}