<?php

namespace Jason\BlockChain;

use Pimple\Container;
use Jason\Kernel\Config;

class BlockChain extends Container
{

    protected $providers = [
        Account\ServiceProvider::class,
        Block\ServiceProvider::class,
        Consensus\ServiceProvider::class,
        Store\ServiceProvider::class,
        Validation\ServiceProvider::class,
    ];

    /**
     * 初始化，注入配置信息
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this['config'] = function () use ($config) {
            return new Config($config);
        };

        foreach ($this->providers as $provider) {
            $this->register(new $provider());
        }
    }

    /**
     * 获取服务
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this[$name];
    }

    /**
     * 设置服务
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value)
    {
        $this[$name] = $value;
    }
}
