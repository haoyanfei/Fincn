<?php
namespace Fincn\Route;
/**
 * Created by PhpStorm.
 * User: haoyanfei <haoyanfei@putao.com>
 * DateTime: 17/2/14 下午5:08
 */
class Route
{
    public $path = '/';
    public $host = '';
    public $defaults = '';//mca
    public $options;
    public $schemes;

    protected $regex;

    protected $variables;
    protected $requirements;

    //[path, [alias,mca,middleware]]

    public function __construct($path, $defaults, $methods = [], array $options = [], $host = '', $schemes = [])
    {
        $this->path = $path;
        $this->host = $host;
        $this->defaults = $defaults;
        $this->options = $options;
        $this->schemes = $schemes;
        $this->methods = $methods;
    }

    public function setPath($pattern)
    {
        $this->path = '/' . trim(trim($pattern), '/');
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setHost($pattern)
    {
        $this->host = (string)$pattern;
        return $this;
    }

    public function getHost()
    {
        return $this->host;
    }

    /**
     * author haoyanfei <haoyanfei@putao.com>
     * setMethods
     * @param array $methods ['POST','GET']
     * @return array
     */
    public function setMethods(array $methods)
    {
        $this->methods = array_map('strtoupper', $methods);
        return $this;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function setOptions($options)
    {
        $this->options = [];
        $this->addOptions($options);
        return $this;
    }

    public function addOptions($options)
    {
        foreach ($options as $name => $option) {
            $this->options[$name] = $option;
        }
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getOption($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    public function setSchemes($schemes)
    {
        $this->schemes = array_map('strtolower', (array)$schemes);
        return $this;
    }

    public function getSchemes()
    {
        return $this->schemes;
    }

    public function hasScheme($scheme)
    {
        return in_array(strtolower($scheme), $this->schemes, true);
    }

    public function setDefaults(array $defaults)
    {
        $this->defaults = array();

        return $this->addDefaults($defaults);
    }

    public function getDefaults()
    {
        return $this->defaults;//['alias','mca','middleware']
    }

    public function addDefaults(array $defaults)
    {
        foreach ($defaults as $name => $default) {
            $this->defaults[$name] = $default;
        }
        return $this;
    }

    public function setDefault($name, $default)
    {
        $this->defaults[$name] = $default;

        return $this;
    }

    public function getDefault($name)
    {
        return isset($this->defaults[$name]) ? $this->defaults[$name] : null;
    }

    public function hasDefault($name)
    {
        return array_key_exists($name, $this->defaults);
    }

    public function parseDefaults($default)
    {
        if (is_null($default)) {
            throw new \RouteException(printf('Route for [%s] has no action.', $this->path));
        }

        if (!isset($default['mca'])) {
            throw new \RouteException(printf('Route for [%s] has no mca.', $this->path));
        }
        return $default;
    }


    public function setVariable($var)
    {
        array_push($this->variables, $var);
        return $this;
    }

    public function getVariables()
    {
        return $this->variables;
    }

    public function setRequirement($key, $value)
    {
        $this->requirements[$key] = $value;
        return $this;
    }

    public function getRequirements()
    {
        return $this->requirements?:[];
    }

    public function setRegex($regex)
    {
        $this->regex = $regex;
        return $this;
    }

    public function getRegex()
    {
        return $this->regex;
    }

    protected $params;

    public function setParam($k, $name)
    {
        $this->params[$k] = $name;
        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

}