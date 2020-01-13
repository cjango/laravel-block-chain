<?php

namespace Jason\BlockChain\Block;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['block'] = function ($app) {
            return new Client($app);
        };
    }

}
