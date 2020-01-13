<?php

namespace Jason\BlockChain\Store;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['store'] = function ($app) {
            // return new Client($app);
        };
    }

}
