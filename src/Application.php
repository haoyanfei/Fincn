<?php
namespace Fincn;
/**
 * Created by PhpStorm.
 * User: haoyanfei <haoyanfei@putao.com>
 * DateTime: 17/2/17 下午3:25
 */
class Application extends \Pimple\Container
{
    static $app;

    public function __construct(array $values = array())
    {
        parent::__construct();
        $this->boot();
        $this->registerBundle();
        foreach ($values as $key => $value) {
            $this[$key] = $value;
        }
    }

    public function boot()
    {
        static::$app = $this;
    }

    public function registerBundle()
    {
        $this->register(new \Fincn\Provider\RoutingProvider());
    }
}